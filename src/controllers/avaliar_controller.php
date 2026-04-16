<?php

require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../models/avaliar_model.php';

function avaliar_page(): void
{
    $refeicoes = get_today_meals();
    $errors = $_SESSION['flash_errors'] ?? [];
    $success = $_SESSION['flash_success'] ?? false;

    unset($_SESSION['flash_errors'], $_SESSION['flash_success']);

    render('avaliar_view', [
        'refeicoes' => $refeicoes,
        'errors' => $errors,
        'success' => $success
    ]);
}

function avaliar_action(): void
{
    $meal_id = filter_input(INPUT_POST, 'meal_id', FILTER_VALIDATE_INT);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    $comment = trim($_POST['comment'] ?? '');
    $image = $_FILES['image'] ?? null;

    $errors = [];

    if (!$meal_id) {
        $errors[] = 'Selecione uma refeição.';
    }
    if (!$rating || $rating < 1 || $rating > 5) {
        $errors[] = 'Selecione uma nota de 1 a 5.';
    }
    if (mb_strlen($comment) > 500) {
        $errors[] = 'Comentário deve ter no máximo 500 caracteres.';
    }
    if ($image && $image['error'] === UPLOAD_ERR_OK) {
        if (!in_array($image['type'], ['image/jpeg', 'image/png', 'image/webp'])) {
            $errors[] = 'Formato inválido. Use JPG, PNG ou WEBP.';
        }
        if ($image['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Imagem deve ter no máximo 2 MB.';
        }
    }

    if (empty($errors) && review_exists($_SESSION['user_id'], $meal_id)) {
        $errors[] = "Você já avaliou essa refeição.";
    }

    if (empty($errors)) {
        store_review($_SESSION['user_id'], $meal_id, $rating, $comment ?: null, $image);
        $_SESSION['flash_success'] = true;
    } else {
        $_SESSION['flash_errors'] = $errors;
    }

    header('Location: /');
    exit;
}
