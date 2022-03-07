<?php

require_once '../vendor/autoload.php';

// import env
require_once 'core/MVC/Config.php';

use Configuration\Config;

(new Config('../.env'))->load();

require_once 'config/app.php';

// load files
foreach ($service['providers'] as $files) {
    require_once $files . ".php";
}

/*
|--------------------------------------------------------------------------
| Register models
|--------------------------------------------------------------------------
|
| The section will autoload all models class in folder app/models.
|
*/

spl_autoload_register(function ($class) {
    $fileName = "../app/models/" . $class . ".php";
    if (file_exists($fileName)) {
        require_once(str_replace('\\', '/', $fileName));
    } else {
        return "The file $class does not exist";
    }
});

/*
|--------------------------------------------------------------------------
| CanThink Helper (Global Function)
|--------------------------------------------------------------------------
|
| The section will autoload custom function used in this framework.
|
*/

$dontLoadFiles = array(
    '.',
    '..',
);

$pathToCanThink = '../system/core/CanThink';
$CanThinkFiles = array_diff(scandir($pathToCanThink), $dontLoadFiles);

/*
|--------------------------------------------------------------------------
| Custom Helper (Global Function)
|--------------------------------------------------------------------------
|
| The section will autoload custom function used in this framework.
|
*/

$pathToHelper = '../system/core/Helpers';
$HelperFiles = array_diff(scandir($pathToHelper), $dontLoadFiles);


/*
|--------------------------------------------------------------------------
| Merge All files to be include
|--------------------------------------------------------------------------
*/

$allFiles = array_merge($CanThinkFiles, $HelperFiles);

foreach ($allFiles as $key => $helperFile) {
    $pathFile = (file_exists($pathToCanThink . '/' . $helperFile)) ? $pathToCanThink : $pathToHelper;
    require_once($pathFile . '/' . $helperFile);
}
