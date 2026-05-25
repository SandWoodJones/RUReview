<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../helpers.php';

use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\AvaliarController;

session_start();

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$routes = [
    'GET /login' => ['require_auth' => false, 'action' => [AuthController::class, 'loginPage']],
    'POST /login' => ['require_auth' => false, 'action' => [AuthController::class, 'loginAction']],

    'GET /cadastro' => ['require_auth' => false, 'action' => [AuthController::class, 'cadastroPage']],
    'POST /cadastrar' => ['require_auth' => false, 'action' => [AuthController::class, 'cadastrarAction']],

    'POST /logout' => ['require_auth' => true, 'action' => [AuthController::class, 'logoutAction']],

    'GET /admin/cardapio' => ['require_admin' => true, 'action' => [AdminController::class, 'cardapioPage']],
    'POST /admin/cardapio' => ['require_admin' => true, 'action' => [AdminController::class, 'cardapioStore']],
    'POST /admin/cardapio/update' => ['require_admin' => true, 'action' => [AdminController::class, 'cardapioUpdate']],
    'POST /admin/cardapio/delete' => ['require_admin' => true, 'action' => [AdminController::class, 'cardapioDelete']],

    'GET /' => ['require_auth' => true, 'action' => [AvaliarController::class, 'avaliarPage']],
    'POST /' => ['require_auth' => true, 'action' => [AvaliarController::class, 'avaliarAction']],

    'GET /admin/avaliacoes' => ['require_admin' => true, 'action' => [AdminController::class, 'reviewsPage']],
];

$key = $method . ' ' . $uri;
$route = $routes[$key] ?? null;

if (!$route) {
    http_response_code(404);
    exit('Página não encontrada.');
}

if (!empty($route['require_admin'])) {
    require_admin();
} elseif (!empty($route['require_auth'])) {
    require_auth();
}

[$class, $method] = $route['action'];
$class::$method();
