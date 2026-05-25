<?php

namespace App\Database;

use PDO;
use Dotenv\Dotenv;

class Connection
{
    private static ?PDO $instance = null;

    public static function get(): PDO
    {
        if (self::$instance === null) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();

            $root = realpath(__DIR__ . '/../../');
            $path = $root . '/' . $_ENV['DB_PATH'];

            self::$instance = new PDO('sqlite:' . $path);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$instance->exec('PRAGMA foreign_keys = ON');
        }

        return self::$instance;
    }
}
