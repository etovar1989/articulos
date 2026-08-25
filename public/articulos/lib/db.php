<?php
declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $config = require __DIR__ . '/../config/config.php';
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $config['db_host'],
            $config['db_port'],
            $config['db_name']
        );
        $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $pdo->exec("SET client_encoding TO 'UTF8'");
    }
    return $pdo;
}
