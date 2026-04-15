<?php

require_once __DIR__ . '/../database.php';

function find_user_by_username(string $username): array|false
{
    $db   = db();
    $stmt = $db->prepare('SELECT * FROM user WHERE username = :username');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();

    return $result->fetchArray(SQLITE3_ASSOC);
}


function create_user(string $username, string $password): bool
{
    $db   = db();
    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $db->prepare('INSERT INTO user (username, password) VALUES (:username, :password)');
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->bindValue(':password', $hash,     SQLITE3_TEXT);
        $stmt->execute();
        return true;
    } catch (Exception $e) {
        return false;
    }
}