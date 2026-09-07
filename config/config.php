<?php
define('BASE_PATH', dirname(__DIR__));
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$scriptDir = rtrim(dirname($scriptName), '/');
$baseUrl = str_replace('/public', '', $scriptDir);
if ($baseUrl === '/' || $baseUrl === '\\' || $baseUrl === '.') {
    $baseUrl = '';
}
define('BASE_URL', rtrim($baseUrl, '/'));
define('APP_NAME', 'Amelie Joyeria');

define('DB_HOST', 'localhost');
define('DB_NAME', 'amelie_joyeria');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
