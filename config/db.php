<?php

return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=127.0.0.1;dbname=iconic_interiors',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',

    // Schema cache options (for production environment)
    'enableSchemaCache' => !YII_DEBUG,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];
