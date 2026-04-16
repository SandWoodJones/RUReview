<?php
require_once __DIR__ . '/../src/database.php';

$db = db();
$db->exec("INSERT INTO user (username, password, is_admin) VALUES ('admin', 'admin123', 1)");

echo "Admin criado com sucesso.\n";