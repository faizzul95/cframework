<?php

date_default_timezone_set($_ENV['timezone']);

$env = $_ENV['ENVIRONMENT'];

if ($env == 'development') {
   define('base_url', $_ENV['local_url']);
   error_reporting(E_ALL);
} else if ($env == 'staging') {
   define('base_url', $_ENV['staging_url']);
   error_reporting(0);
} else if ($env == 'production') {
   define('base_url', $_ENV['server_url']);
   error_reporting(0);
}
