<?php
declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$localConfig = is_file(__DIR__ . '/config.local.php')
    ? require __DIR__ . '/config.local.php'
    : [];

$dbHost = $localConfig['db_host'] ?? getenv('CISC3003_DB_HOST') ?: 'sql200.infinityfree.com';
$dbUser = $localConfig['db_user'] ?? getenv('CISC3003_DB_USER') ?: 'if0_41895031';
$dbPass = $localConfig['db_password'] ?? getenv('CISC3003_DB_PASS') ?: '';
$dbName = $localConfig['db_name'] ?? getenv('CISC3003_DB_NAME_A') ?: 'if0_41895031_paper02a';
$dbPort = (int) ($localConfig['db_port'] ?? getenv('CISC3003_DB_PORT') ?: 3306);

try {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $exception) {
    http_response_code(500);
    exit('Database connection failed. Please import db/database.sql in phpMyAdmin first.');
}
