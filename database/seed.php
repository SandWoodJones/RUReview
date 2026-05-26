<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\Connection;

$db = Connection::get();

$password = 'admin123';
$hash     = password_hash($password, PASSWORD_DEFAULT);

$stmt = $db->prepare(
    'INSERT OR IGNORE INTO user (username, password, is_admin) VALUES (:username, :password, 1)'
);
$stmt->execute([':username' => 'admin', ':password' => $hash]);

if ($stmt->rowCount()) {
    echo "Admin criado com sucesso. Senha: {$password}\n";
} else {
    echo "Usuário 'admin' já existe. Nenhuma alteração feita.\n";
}