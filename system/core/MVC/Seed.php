<?php

class Seed
{
    public function __construct()
    {
        if (filter_var($_ENV['MIGRATION_ENABLE'], FILTER_VALIDATE_BOOLEAN) === TRUE) {
            // session();
            $this->seeding();
        }
    }

    public function index()
    {
        error('404'); // redirect to page error 404
    }

    // Seed
    public function seeding()
    {
        echo "===== Seeders start ===== <br><br>";
        foreach (glob('../database/seeders/*.php') as $filename) {
            if (file_exists($filename)) {
                require_once(str_replace('\\', '/', $filename));
                $classes = get_declared_classes();
                $class = end($classes);

                $obj = new $class; // create new object

                // check if function up is exist
                if (method_exists($obj, 'run')) {
                    $obj->run();
                }
            } else {
                echo "The file $filename does not exist";
            }
        }
        echo "<br> ===== Seeders ended =====";
        die;
    }
}
