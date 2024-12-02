<?php

require_once 'constants.php';
/**
 * Load params from .conf file
 * @throws \app\utils\SystemException
 * @return void
 */
function loadConfig(): void
{
    $configFile = '..' . DIRECTORY_SEPARATOR . '.conf';
    if (!file_exists($configFile)) {
        throw new app\utils\SystemException('Internal Server Error', 500, null, 'Service currently unavailable, try again later');
    }
    $handle = fopen($configFile, 'r');
    if (!$handle) {
        throw new app\utils\SystemException('Internal Server Error', 500, null, 'Service currently unavailable, try again later');
    }
    while (!feof($handle)) {
        $row = trim(fgets($handle));
        if (!empty($row) && strpos($row, '#') !== 0) {
            putenv($row);
        }
    }
}

/**
 * Return value of conf param
 * @param string $key
 * @return array|bool|string
 */
function conf(string $key)
{
    return getenv($key);
}

spl_autoload_register(function ($class) {
    $path = '..' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($path)) {
        include_once $path;
        return true;
    }
    return false;
});

loadConfig();
define('BASE_URL', conf('BASE_URL'));
define('API_KEY', conf('API_KEY'));

$router = new \app\core\Router();
$router->init();
