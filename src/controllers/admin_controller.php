<?php

require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../models/cardapio_model.php';

function admin_cardapio_page(): void
{
    $error   = $_SESSION['flash_error']   ?? null;
    $success = $_SESSION['flash_success'] ?? null;
    unset($_SESSION['flash_error'], $_SESSION['flash_success']);

    render('admin_cardapio_view', ['error' => $error, 'success' => $success]);
}

function admin_cardapio_store(): void
{
    $date = $_POST['date'] ?? '';
    $type = $_POST['type'] ?? '';
    $fields = [
        'protein' => trim($_POST['protein'] ?? ''),
        'protein_vegan' => trim($_POST['protein_vegan'] ?? ''),
        'beans' => $_POST['beans'] ?? '',
        'carb_extra' => trim($_POST['carb_extra'] ?? ''),
        'salad_extra' => trim($_POST['salad_extra'] ?? ''),
        'dessert' => trim($_POST['dessert'] ?? ''),
    ];

    if (!$date || !strtotime($date)) {
        $_SESSION['flash_error'] = 'Data inválida.';
        redirect('/admin/cardapio');
    }
    if (!in_array($type, ['almoco', 'janta'], true)) {
        $_SESSION['flash_error'] = 'Tipo de refeição inválido.';
        redirect('/admin/cardapio');
    }
    foreach ($fields as $key => $value) {
        if ($value === '') {
            $_SESSION['flash_error'] = 'Preencha todos os campos.';
            redirect('/admin/cardapio');
        }
    }
    if (!in_array($fields['beans'], ['preto', 'carioca'], true)) {
        $_SESSION['flash_error'] = 'Tipo de feijão inválido.';
        redirect('/admin/cardapio');
    }
    if (meal_exists($date, $type)) {
        $_SESSION['flash_error'] = 'Já existe um cardápio cadastrado para esta data e tipo.';
        redirect('/admin/cardapio');
    }

    store_meal($date, $type, $fields);

    $_SESSION['flash_success'] = 'Cardápio salvo com sucesso.';
    redirect('/admin/cardapio');
}

function admin_reviews_page(): void
{
    $reviews = get_all_reviews();
    render('admin_reviews_view', ['reviews' => $reviews]);
}
