<?php

require_once __DIR__ . '/../app.php';
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../controllers/auth_controller.php';
require_once __DIR__ . '/../controllers/admin_controller.php';
require_once __DIR__ . '/../controllers/avaliar_controller.php';

session_start();

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$routes = [
    'GET /login' => 'login_page',
    'POST /login' => 'login_action',

    'GET /cadastro' => 'cadastro_page',
    'POST /cadastrar' => 'cadastrar_action',

    'POST /logout' => ['require_auth',  'logout_action'],

    'GET /admin/cardapio' => ['require_admin', 'admin_cardapio_page'],
    'POST /admin/cardapio' => ['require_admin', 'admin_cardapio_store'],
    'POST /admin/cardapio/update' => ['require_admin', 'admin_cardapio_update'],
    'POST /admin/cardapio/delete' => ['require_admin', 'admin_cardapio_delete'],

    'GET /' => ['require_auth',  'avaliar_page'],
    'POST /' => ['require_auth',  'avaliar_action'],

    'GET /admin/avaliacoes' => ['require_admin', 'admin_reviews_page']
];

$key = $method . ' ' . $uri;
$handler = $routes[$key] ?? null;

if (!$handler) {
    http_response_code(404);
    exit('Página não encontrada.');
}

foreach ((array) $handler as $fn) {
    $fn();
}
