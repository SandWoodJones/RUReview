<?php

namespace App\Models;

use App\Database\Connection;

class CardapioModel
{
    public static function store(string $date, string $type, array $fields): void
    {
        $db = Connection::get();

        $stmt = $db->prepare('INSERT OR IGNORE INTO daily_menu (date) VALUES (:date)');
        $stmt->execute([':date' => $date]);

        $stmt = $db->prepare('SELECT id FROM daily_menu WHERE date = :date');
        $stmt->execute([':date' => $date]);
        $menu_id = $stmt->fetch()['id'];

        $stmt = $db->prepare('INSERT INTO meal (daily_menu_id, type, protein, protein_vegan, beans, carb_extra, salad_extra, dessert)
                          VALUES (:menu_id, :type, :protein, :protein_vegan, :beans, :carb_extra, :salad_extra, :dessert)');
        $stmt->execute([
            ':menu_id' => $menu_id,
            ':type' => $type,
            ':protein' => $fields['protein'],
            ':protein_vegan' => $fields['protein_vegan'],
            ':beans' => $fields['beans'],
            ':carb_extra' => $fields['carb_extra'],
            ':salad_extra' => $fields['salad_extra'],
            ':dessert' => $fields['dessert'],
        ]);
    }

    public static function exists(string $date, string $type): bool
    {
        $db = Connection::get();

        $stmt = $db->prepare('
        SELECT 1 FROM meal m
        JOIN daily_menu dm ON m.daily_menu_id = dm.id
        WHERE dm.date = :date AND m.type = :type
    ');
        $stmt->execute([':date' => $date, ':type' => $type]);

        return $stmt->fetch() !== false;
    }

    public static function getMonthMeals(string $year_month): array
    {
        $db = Connection::get();

        $stmt = $db->prepare('
            SELECT dm.date, m.id, m.type, m.protein, m.protein_vegan, m.beans, m.carb_extra, m.salad_extra, m.dessert
            FROM meal m JOIN daily_menu dm ON m.daily_menu_id = dm.id WHERE dm.date LIKE :pattern ORDER BY dm.date, m.type
        ');
        $stmt->execute([':pattern' => $year_month . '-%']);

        $meals = [];
        while ($row = $stmt->fetch()) {
            $meals[$row['date']][$row['type']] = $row;
        }

        return $meals;
    }

    public static function getById(int $id): array|false
    {
        $db = Connection::get();

        $stmt = $db->prepare('SELECT m.*, dm.date FROM meal m JOIN daily_menu dm ON m.daily_menu_id = dm.id WHERE m.id = :id');
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    public static function update(int $id, array $fields): void
    {
        $db = Connection::get();

        $stmt = $db->prepare('
            UPDATE meal
            SET protein = :protein, protein_vegan = :protein_vegan, beans = :beans, carb_extra = :carb_extra, salad_extra = :salad_extra, dessert = :dessert
            WHERE id = :id
        ');
        $stmt->execute([
            ':protein' => $fields['protein'],
            ':protein_vegan' => $fields['protein_vegan'],
            ':beans' => $fields['beans'],
            ':carb_extra' => $fields['carb_extra'],
            ':salad_extra' => $fields['salad_extra'],
            ':dessert' => $fields['dessert'],
            ':id' => $id
        ]);
    }

    public static function delete(int $id): void
    {
        $db = Connection::get();

        $stmt = $db->prepare('DELETE FROM meal WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
