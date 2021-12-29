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
        echo "<br> ===== Migration ended =====";
        die;
    }
}
