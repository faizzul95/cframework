<?php

$dontLoadFiles = array(
    '.',
    '..',
    'autoload.php'
);

$pathToCT = '../system/core/CanThink';
$CTFiles = array_diff(scandir($pathToCT), $dontLoadFiles);
foreach ($CTFiles as $key => $ctf) {
    require_once($pathToCT . '/' . $ctf);
}
