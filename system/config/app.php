<?php

$service = [

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [
        /*
         * CanThink Core Files...
        */
        "config/settings",
        "config/auditTrail",
        "core/MVC/App",
        "core/MVC/ExtendedReflectionClass",
        "core/MVC/Controller",
        "core/MVC/Model",
        "core/MVC/Seeder",
        "core/MVC/Database",
        "core/MVC/SessionInterface",
        "core/MVC/SessionManager",
        "core/MVC/DbObject",
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [],

];

?>