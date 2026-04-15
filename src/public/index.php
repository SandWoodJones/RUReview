<?php
require_once __DIR__ . '/../app.php';
session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

match (true) {
    $uri === '/' && $method === 'GET'             => require_once __DIR__ . '/../controllers/avaliar_controller.php',
    $uri === '/login' && $method === 'GET'        => require_once __DIR__ . '/../controllers/auth_controller.php',
    $uri === '/login' && $method === 'POST'       => require_once __DIR__ . '/../controllers/auth_controller.php',
    $uri === '/cadastro' && $method === 'GET'     => require_once __DIR__ . '/../controllers/auth_controller.php',
    $uri === '/cadastrar' && $method === 'POST'   => require_once __DIR__ . '/../controllers/auth_controller.php',
    default => http_response_code(404) && print("Página não encontrada")
};