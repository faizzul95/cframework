<?php

class Migrate
{
    public function __construct()
    {
        if (filter_var($_ENV['MIGRATION_ENABLE'], FILTER_VALIDATE_BOOLEAN) === TRUE) {
            // session();
            $this->migration();
        }
    }

    public function index()
    {
        error('404'); // redirect to page error 404
    }

    // migrate
    public function migration()
    {
        // check_db_exist(); // check if db not exist create it first.
        removeAllRelation(); // remove all Constrain / relation table first before migrate
        echo "===== Migration start ===== <br><br>";
        foreach (glob('../database/migrations/*.php') as $filename) {
            if (file_exists($filename)) {
                require_once(str_replace('\\', '/', $filename));
                $classes = get_declared_classes();
                $class = end($classes);

                $obj = new $class; // create new object

                // check if function up is exist
                if (method_exists($obj, 'up')) {
                    $obj->up();
                }

                // check if function down is exist
                if (method_exists($obj, 'down')) {
                    $obj->down();
                }
            } else {
                echo "The file $filename does not exist";
            }
        }
        echo "<br> ===== Migration ended ===== <br><br>";
        $this->relation(); // add relation table back
        die;
    }

    // relation
    public function relation()
    {
        // echo "===== Relation table start ===== <br><br>";
        // removeAllRelation(); // remove all Constrain / relation table first before migrate
        foreach (glob('../database/migrations/*.php') as $filename) {
            if (file_exists($filename)) {
                $className = getClassFullNameFromFile(str_replace('', "'\\'", $filename));
                $obj = new $className; // create new object

                // check if function relation is exist
                if (method_exists($obj, 'relation')) {
                    $obj->relation();
                }
            } else {
                echo "The file $filename does not exist";
            }
        }
        // echo "<br> ===== Relation table ended =====";
    }
}
