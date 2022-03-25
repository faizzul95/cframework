<?php

// set configuration apps
$config = [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => (isset($_ENV['APP_NAME'])) ? $_ENV['APP_NAME'] : 'CanThink Framework',

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => (isset($_ENV['ENVIRONMENT'])) ? $_ENV['ENVIRONMENT'] : 'production',


    /*
    |--------------------------------------------------------------------------
    | Application Maintenance Mode
    |--------------------------------------------------------------------------
    */

    'maintenance' => (isset($_ENV['MAINTENANCE_MODE'])) ? filter_var($_ENV['MAINTENANCE_MODE'], FILTER_VALIDATE_BOOLEAN) : false,

    /*
    |--------------------------------------------------------------------------
    | Blade Templating Mode
    |--------------------------------------------------------------------------
    */

    'blade' => (isset($_ENV['blade_mode'])) ? $_ENV['blade_mode'] : '0',

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => (isset($_ENV['timezone'])) ? $_ENV['timezone'] : 'Asia/Kuala_Lumpur',


    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Security : CSRF
    |--------------------------------------------------------------------------
    */

    'csrf' => (isset($_ENV['CSRF'])) ? filter_var($_ENV['CSRF'], FILTER_VALIDATE_BOOLEAN) : false,

];

// set configuration database variable
define('DB_HOST', ($config['env'] == 'development') ? $_ENV['local.hostname'] : (($config['env'] == 'staging') ? $_ENV['test.hostname'] : $_ENV['server.hostname'])); // set db host
define('DB_NAME', ($config['env'] == 'development') ? $_ENV['local.db'] : (($config['env'] == 'staging') ? $_ENV['test.db'] : $_ENV['server.db'])); // set db name
define('DB_USERNAME', ($config['env'] == 'development') ? $_ENV['local.username'] : (($config['env'] == 'staging') ? $_ENV['test.username'] : $_ENV['server.username'])); // set db username
define('DB_PASSWORD', ($config['env'] == 'development') ? $_ENV['local.password'] : (($config['env'] == 'staging') ? $_ENV['test.password'] : $_ENV['server.password'])); // set db password
define('DB_CHARSET', ($config['env'] == 'development') ? $_ENV['local.charset'] : (($config['env'] == 'staging') ? $_ENV['test.charset'] : $_ENV['server.charset'])); // set db charset
define('DB_PORT', ($config['env'] == 'development') ? $_ENV['local.port'] : (($config['env'] == 'staging') ? $_ENV['test.port'] : $_ENV['server.port'])); // set db port
define('DB_SOCKET', ($config['env'] == 'development') ? $_ENV['local.socket'] : (($config['env'] == 'staging') ? $_ENV['test.socket'] : $_ENV['server.socket'])); // set db socket

// set base url
define('base_url', ($config['env'] == 'development') ? $_ENV['local_url'] : (($config['env'] == 'staging') ? $_ENV['staging_url'] : $_ENV['server_url']));

// set default local
define('TIMEZONE', $config['timezone']);
date_default_timezone_set(TIMEZONE);

// set error reporting
error_reporting(($config['env'] == 'development') ? E_ALL : '0');

// set default templating blade mode
define('BLADE_MODE', $config['blade']);