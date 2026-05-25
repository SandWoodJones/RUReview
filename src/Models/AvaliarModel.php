<?php

namespace App\Models;

use App\Database\Connection;
use PDO;

class AvaliarModel
{
    public static function getTodayMeals(): array
    {
        $db = Connection::get();

        $stmt = $db->prepare('
        SELECT m.*
        FROM meal m
        JOIN daily_menu dm ON m.daily_menu_id = dm.id
        WHERE dm.date = :date
    ');
        $stmt->execute([':date' => date('Y-m-d')]);

        $refeicoes = [];
        while ($row = $stmt->fetch()) {
            $refeicoes[$row['type']] = $row;
        }

        return $refeicoes;
    }

    public static function storeReview(int $user_id, int $meal_id, int $rating, ?string $comment, ?array $image): void
    {
        $db = Connection::get();

        $stmt = $db->prepare('INSERT INTO review (user_id, meal_id, rating, comment) VALUES (:user_id, :meal_id, :rating, :comment)');
        $stmt->execute([
            ':user_id' => $user_id,
            ':meal_id' => $meal_id,
            ':rating' => $rating,
            ':comment' => $comment,
        ]);

        if (!$image || $image['error'] !== UPLOAD_ERR_OK) {
            return;
        }

        $review_id = $db->lastInsertID();
        $stmt = $db->prepare('INSERT INTO image (review_id, image_data, mime_type) VALUES (:review_id, :image_data, :mime_type)');
        $stmt->bindValue(':review_id', $review_id);
        $stmt->bindValue(':image_data', file_get_contents($image['tmp_name']), PDO::PARAM_LOB);
        $stmt->bindValue(':mime_type', $image['type']);
        $stmt->execute();
    }

    public static function reviewExists(int $user_id, int $meal_id): bool
    {
        $db = Connection::get();

        $stmt = $db->prepare('SELECT 1 FROM review WHERE user_id = :user_id AND meal_id = :meal_id');
        $stmt->execute([':user_id' => $user_id, ':meal_id' => $meal_id]);

        return $stmt->fetch() !== false;
    }

    public static function getAllReviews(): array
    {
        $db = Connection::get();

        $stmt = $db->query('
            SELECT r.id, r.rating, r.comment, r.created_at, u.username, m.type AS meal_type, dm.date AS meal_date, (SELECT COUNT(*) FROM image WHERE review_id = r.id) AS has_image
            FROM review r
            JOIN user u ON r.user_id = u.id
            JOIN meal m ON r.meal_id = m.id
            JOIN daily_menu dm ON m.daily_menu_id = dm.id
            ORDER BY r.created_at DESC
        ');

        return $stmt->fetchAll();
    }
}
