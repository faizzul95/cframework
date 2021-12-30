<?php

require_once 'core/CanThink/autoload.php';
require_once 'core/Config.php';
require_once 'core/Authentication.php';
require_once 'core/App.php';
require_once 'core/API.php';
require_once 'core/ExtendedReflectionClass.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/Database.php';
require_once 'core/Seeder.php';
require_once 'core/Input.php';
require_once 'core/SessionInterface.php';
require_once 'core/SessionManager.php';

require_once 'core/DbObject.php';
require_once 'config/config.php';
require_once '../vendor/autoload.php';

spl_autoload_register(function ($class) {
    $fileName = "../app/models/" . $class . ".php";
    if (file_exists($fileName)) {
        require_once(str_replace('\\', '/', $fileName));
    } else {
        return "The file $class does not exist";
    }
});
