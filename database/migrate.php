<?php

require_once __DIR__ . '/../src/database.php';

$db = db();
$schema = file_get_contents(__DIR__ . '/schema.sql');

$statements = array_filter(
    array_map('trim', explode(';', $schema))
);

foreach ($statements as $statement) {
    $db->exec($statement);
}

echo "Banco criado com sucesso.\n";
