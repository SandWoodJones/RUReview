<?php

require_once __DIR__ . '/../database.php';

function find_user_by_username(string $username): array|false
{
    $db   = db();
    $stmt = $db->prepare('SELECT * FROM user WHERE username = :username');
    $stmt->execute([':username' => $username]);

    return $stmt->fetch();
}


function create_user(string $username, string $password): bool
{
    $db   = db();

    try {
        $stmt = $db->prepare('INSERT INTO user (username, password) VALUES (:username, :password)');
        $stmt->execute([':username' => $username, ':password' => $password]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}
