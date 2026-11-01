<?php

$envFile = dirname(__DIR__) . '/.env';
if (!is_file($envFile)) {
    throw new \RuntimeException('Database configuration file .env is missing.');
}

$environment = parse_ini_file($envFile, false, INI_SCANNER_RAW);
if ($environment === false) {
    throw new \RuntimeException('Unable to parse database configuration file .env.');
}

if (!isset($environment['DB_NAME']) || $environment['DB_NAME'] === '' || !array_key_exists('DB_PASSWORD', $environment)) {
    throw new \RuntimeException('DB_NAME and DB_PASSWORD must be defined in .env.');
}

return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=127.0.0.1;dbname=' . $environment['DB_NAME'],
    'username' => 'root',
    'password' => $environment['DB_PASSWORD'],
    'charset' => 'utf8mb4',

    // Schema cache options (for production environment)
    'enableSchemaCache' => !YII_DEBUG,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];
