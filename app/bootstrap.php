<?php

require_once 'config.php';
require_once '../key.php';

spl_autoload_register(function ($class) {
    $path = '..' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($path)) {
        include_once $path;
        return true;
    }
    return false;
});

$router = new \app\core\Router();
$router->init();
