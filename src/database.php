<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

function db(): PDO
{
    static $db = null;

    if ($db === null) {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        $db = new PDO('sqlite:' . __DIR__ . '/../' . $_ENV['DB_PATH']);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $db->exec('PRAGMA foreign_keys = ON');
    };

    return $db;
}
