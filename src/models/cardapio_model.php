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

function get_month_meals(string $year_month): array
{
    $db = db();

    $stmt = $db->prepare('SELECT dm.date, m.id, m.type, m.protein, m.protein_vegan, m.beans, m.carb_extra, m.salad_extra, m.dessert
                          FROM meal m JOIN daily_menu dm ON m.daily_menu_id = dm.id WHERE dm.date LIKE :pattern ORDER BY dm.date, m.type');
    $stmt->bindValue(':pattern', $year_month . '-%');
    $result = $stmt->execute();

    $meals = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $meals[$row['date']][$row['type']] = $row;
    }

    return $meals;
}

function get_meal_by_id(int $id): array|false
{
    $db = db();
    $stmt = $db->prepare('SELECT m.*, dm.date FROM meal m JOIN daily_menu dm ON m.daily_menu_id = dm.id WHERE m.id = :id');
    $stmt->bindValue(':id', $id);

    return $stmt->execute()->fetchArray(SQLITE3_ASSOC);
}

function update_meal(int $id, array $fields): void
{
    $db = db();

    $stmt = $db->prepare('UPDATE meal
                          SET protein = :protein, protein_vegan = :protein_vegan, beans = :beans, carb_extra = :carb_extra, salad_extra = :salad_extra, dessert = :dessert
                          WHERE id = :id');
    $stmt->bindValue(':protein', $fields['protein']);
    $stmt->bindValue(':protein_vegan', $fields['protein_vegan']);
    $stmt->bindValue(':beans', $fields['beans']);
    $stmt->bindValue(':carb_extra', $fields['carb_extra']);
    $stmt->bindValue(':salad_extra', $fields['salad_extra']);
    $stmt->bindValue(':dessert', $fields['dessert']);
    $stmt->bindValue(':id', $id);

    $stmt->execute();
}

function delete_meal(int $id): void {
    $db = db();

    $stmt = $db->prepare('DELETE FROM meal WHERE id = :id');
    $stmt->bindValue(':id', $id);
    $stmt->execute();
}
