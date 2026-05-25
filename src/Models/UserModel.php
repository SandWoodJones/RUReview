<?php

namespace App\Models;

use App\Database\Connection;

class UserModel
{
    public static function findByUsername(string $username): array|false
    {
        $db = Connection::get();
        $stmt = $db->prepare('SELECT * FROM user WHERE username = :username');
        $stmt->execute([':username' => $username]);

        return $stmt->fetch();
    }


    public static function create(string $username, string $password): bool
    {
        $db = Connection::get();

        try {
            $stmt = $db->prepare('INSERT INTO user (username, password) VALUES (:username, :password)');
            $stmt->execute([':username' => $username, ':password' => $password]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
