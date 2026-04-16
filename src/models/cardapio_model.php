<?php

require_once __DIR__ . '/../database.php';

function store_meal(string $date, string $type, array $fields): void
{
    $db = db();

    $stmt = $db->prepare('INSERT OR IGNORE INTO daily_menu (date) VALUES (:date)');
    $stmt->bindValue(':date', $date);
    $stmt->execute();

    $stmt = $db->prepare('SELECT id FROM daily_menu WHERE date = :date');
    $stmt->bindValue(':date', $date);
    $menu_id = $stmt->execute()->fetchArray(SQLITE3_ASSOC)['id'];

    $stmt = $db->prepare('INSERT INTO meal (daily_menu_id, type, protein, protein_vegan, beans, carb_extra, salad_extra, dessert)
                          VALUES (:menu_id, :type, :protein, :protein_vegan, :beans, :carb_extra, :salad_extra, :dessert)');
    $stmt->bindValue(':menu_id', $menu_id);
    $stmt->bindValue(':type', $type);
    $stmt->bindValue(':protein', $fields['protein']);
    $stmt->bindValue(':protein_vegan', $fields['protein_vegan']);
    $stmt->bindValue(':beans', $fields['beans']);
    $stmt->bindValue(':carb_extra', $fields['carb_extra']);
    $stmt->bindValue(':salad_extra', $fields['salad_extra']);
    $stmt->bindValue(':dessert', $fields['dessert']);
    $stmt->execute();
}

function meal_exists(string $date, string $type): bool
{
    $db = db();

    $stmt = $db->prepare('
        SELECT 1 FROM meal m
        JOIN daily_menu dm ON m.daily_menu_id = dm.id
        WHERE dm.date = :date AND m.type = :type
    ');
    $stmt->bindValue(':date', $date);
    $stmt->bindValue(':type', $type);

    return $stmt->execute()->fetchArray() !== false;
}
