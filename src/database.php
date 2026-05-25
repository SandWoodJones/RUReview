<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

function db(): SQLite3
{
    static $db = null;

    if ($db === null) {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        $db = new SQLite3(__DIR__ . '/../' . $_ENV['DB_PATH']);
        $db->enableExceptions(true);
        $db->exec('PRAGMA foreign_keys = ON');
    };

    return $db;
}
