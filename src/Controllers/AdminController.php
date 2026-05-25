<?php

namespace App\Controllers;

use App\Models\CardapioModel;
use App\Models\AvaliarModel;

class AdminController
{
    public static function cardapioPage(): void
    {
        $month = $_GET['month'] ?? date('Y-m');
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }

        $selected = $_GET['selected'] ?? null;
        if ($selected && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $selected)) {
            $selected = null;
        }

        $error = $_SESSION['flash_error'] ?? null;
        $success = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_error'], $_SESSION['flash_success']);

        $month_meals = CardapioModel::getMonthMeals($month);
        render('admin_cardapio_view', compact('error', 'success', 'month', 'month_meals', 'selected'));
    }

    public static function cardapioStore(): void
    {
        $date = $_POST['date'] ?? '';
        $month = preg_match('/^\d{4}-\d{2}/', $date) ? substr($date, 0, 7) : date('Y-m');
        $redirect_err = "/admin/cardapio?month={$month}&selected={$date}";

        if (!$date || !strtotime($date)) {
            $_SESSION['flash_error'] = 'Data inválida.';
            redirect($redirect_err);
        }

        $type = $_POST['type'] ?? '';
        if (!in_array($type, ['almoco', 'janta'], true)) {
            $_SESSION['flash_error'] = 'Tipo de refeição inválido.';
            redirect($redirect_err);
        }

        if (CardapioModel::exists($date, $type)) {
            $_SESSION['flash_error'] = 'Já existe um cardápio cadastrado para esta data e tipo.';
            redirect($redirect_err);
        }

        $fields = self::validateMealFields($redirect_err);
        CardapioModel::store($date, $type, $fields);

        $_SESSION['flash_success'] = 'Cardápio salvo com sucesso.';
        redirect("/admin/cardapio?month={$month}&selected={$date}");
    }

    public static function cardapioUpdate(): void
    {
        $meal_id = filter_input(INPUT_POST, 'meal_id', FILTER_VALIDATE_INT);

        if (!$meal_id) {
            $_SESSION['flash_error'] = 'Refeição inválida.';
            redirect('/admin/cardapio');
        }

        $meal = CardapioModel::getById($meal_id);
        if (!$meal) {
            $_SESSION['flash_error'] = 'Refeição não encontrada.';
            redirect('/admin/cardapio');
        }

        $month = substr($meal['date'], 0, 7);
        $redirect_err = "/admin/cardapio?month={$month}&selected={$meal['date']}";

        $fields = self::validateMealFields($redirect_err);
        CardapioModel::update($meal_id, $fields);

        $_SESSION['flash_success'] = 'Cardápio atualizado com sucesso.';
        redirect("/admin/cardapio?month={$month}&selected={$meal['date']}");
    }

    public static function cardapioDelete(): void
    {
        $meal_id = filter_input(INPUT_POST, 'meal_id', FILTER_VALIDATE_INT);

        if (!$meal_id) {
            $_SESSION['flash_error'] = 'Refeição inválida.';
            redirect('/admin/cardapio');
        }

        $meal = CardapioModel::getById($meal_id);
        if (!$meal) {
            $_SESSION['flash_error'] = 'Refeição não encontrada.';
            redirect('/admin/cardapio');
        }

        $month = substr($meal['date'], 0, 7);
        CardapioModel::delete($meal_id);

        $_SESSION['flash_success'] = 'Refeição removida com sucesso.';
        redirect("/admin/cardapio?month={$month}&selected={$meal['date']}");
    }

    public static function reviewsPage(): void
    {
        $reviews = AvaliarModel::getAllReviews();
        render('admin_reviews_view', ['reviews' => $reviews]);
    }

    private static function validateMealFields(string $redirect_on_error): array
    {
        $fields = [
            'protein' => trim($_POST['protein'] ?? ''),
            'protein_vegan' => trim($_POST['protein_vegan'] ?? ''),
            'beans' => $_POST['beans'] ?? '',
            'carb_extra' => trim($_POST['carb_extra'] ?? ''),
            'salad_extra' => trim($_POST['salad_extra'] ?? ''),
            'dessert' => trim($_POST['dessert'] ?? ''),
        ];

        foreach ($fields as $value) {
            if ($value === '') {
                $_SESSION['flash_error'] = 'Preencha todos os campos.';
                redirect($redirect_on_error);
            }
        }

        if (!in_array($fields['beans'], ['preto', 'carioca'], true)) {
            $_SESSION['flash_error'] = 'Tipo de feijão inválido.';
            redirect($redirect_on_error);
        }

        return $fields;
    }
}
