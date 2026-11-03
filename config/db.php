<?php

$envFile = dirname(__DIR__) . '/.env';
if (!is_file($envFile)) {
    throw new \RuntimeException('Database configuration file .env is missing.');
}

$environment = parse_ini_file($envFile, false, INI_SCANNER_RAW);
if ($environment === false) {
    throw new \RuntimeException('Unable to parse database configuration file .env.');
}

if (empty($environment['DB_NAME'])) {
    throw new \RuntimeException('DB_NAME must be defined in .env.');
}
if (!array_key_exists('DB_PASSWORD', $environment)) {
    throw new \RuntimeException('DB_PASSWORD key must exist in .env (it may be empty for local XAMPP).');
}

$host = $environment['HOST'] ?? 'localhost';
$port = $environment['DB_PORT'] ?? '3306';
$user = $environment['DB_USER'] ?? 'root';

// Refuse an empty password outside local development
if ($environment['DB_PASSWORD'] === '' && !YII_DEBUG) {
    throw new \RuntimeException('An empty DB_PASSWORD is only allowed when YII_DEBUG is on.');
}

return [
    'class' => \yii\db\Connection::class,
    'dsn' => "mysql:host={$host};port={$port};dbname={$environment['DB_NAME']}",
    'username' => $user,
    'password' => $environment['DB_PASSWORD'],
    'charset' => 'utf8mb4',

    // Schema cache options (for production environment)
    'enableSchemaCache' => !YII_DEBUG,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];