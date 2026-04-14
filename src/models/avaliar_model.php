<?php
require_once __DIR__ . '/../database.php';

function get_today_meals(): array
{
    $db = db();

    $stmt = $db->prepare('
        SELECT m.*
        FROM meal m
        JOIN daily_menu dm ON m.daily_menu_id = dm.id
        WHERE dm.date = :date
    ');
    $stmt->bindValue(':date', date('Y-m-d'));
    $result = $stmt->execute();

    $refeicoes = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $refeicoes[$row['type']] = $row;
    }

    return $refeicoes;
}

function store_review(int $user_id, int $meal_id, int $rating, ?string $comment, ?array $image): void
{
    $db = db();

    $stmt = $db->prepare('
        INSERT INTO review (user_id, meal_id, rating, comment)
        VALUES (:user_id, :meal_id, :rating, :comment)
    ');
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':meal_id', $meal_id);
    $stmt->bindValue(':rating', $rating);
    $stmt->bindValue(':comment', $comment, $comment !== null ? SQLITE3_TEXT : SQLITE3_NULL);
    $stmt->execute();

    if (!$image || $image['error'] !== UPLOAD_ERR_OK) {
        return;
    }

    $review_id = $db->lastInsertRowID();
    $stmt = $db->prepare('
        INSERT INTO image (review_id, image_data, mime_type)
        VALUES (:review_id, :image_data, :mime_type)
    ');
    $stmt->bindValue(':review_id', $review_id);
    $stmt->bindValue(':image_data', file_get_contents($image['tmp_name']), SQLITE3_BLOB);
    $stmt->bindValue(':mime_type', $image['type']);
    $stmt->execute();
}

function review_exists(int $user_id, int $meal_id): bool
{
    $db = db();

    $stmt = $db->prepare('
        SELECT 1 FROM review WHERE user_id = :user_id AND meal_id = :meal_id
    ');
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':meal_id', $meal_id);
    $result = $stmt->execute();

    return $result->fetchArray() !== false;
}
