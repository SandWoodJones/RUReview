<?php

require_once __DIR__ . '/../app.php';
require_once __DIR__ . '/../controllers/auth_controller.php';
require_once __DIR__ . '/../controllers/avaliar_controller.php';

session_start();

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/login'  && $method === 'GET') {
    login_page();
    exit();
}
if ($uri === '/login'  && $method === 'POST') {
    login_action();
    exit();
}
if ($uri === '/cadastro'  && $method === 'GET') {
    cadastro_page();
    exit();
}
if ($uri === '/cadastrar' && $method === 'POST') {
    cadastrar_action();
    exit();
}
if ($uri === '/logout' && $method === 'POST') {
    logout_action();
    exit();
}
if ($uri === '/' && $method === 'GET') {
    avaliar_page();
    exit();
}
if ($uri === '/' && $method === 'GET') {
    avaliar_action();
    exit();
}

if (!isset($_SESSION['user_id'])) {
    redirect('/login');
}

http_response_code(404);
echo 'Página não encontrada.';
