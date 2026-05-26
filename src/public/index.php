<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../helpers.php';

use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\AvaliarController;
use App\Controllers\ImageController;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

session_start();

$routeDefs = [
    ['GET',  '/login',                 false, false, [AuthController::class,  'loginPage']],
    ['POST', '/login',                 false, false, [AuthController::class,  'loginAction']],
    ['GET',  '/cadastro',              false, false, [AuthController::class,  'cadastroPage']],
    ['POST', '/cadastrar',             false, false, [AuthController::class,  'cadastrarAction']],
    ['POST', '/logout',                false, true,  [AuthController::class,  'logoutAction']],

    ['GET',  '/',                      false, true,  [AvaliarController::class, 'avaliarPage']],
    ['POST', '/',                      false, true,  [AvaliarController::class, 'avaliarAction']],

    ['GET',  '/admin/cardapio',        true,  true,  [AdminController::class, 'cardapioPage']],
    ['POST', '/admin/cardapio',        true,  true,  [AdminController::class, 'cardapioStore']],
    ['POST', '/admin/cardapio/update', true,  true,  [AdminController::class, 'cardapioUpdate']],
    ['POST', '/admin/cardapio/delete', true,  true,  [AdminController::class, 'cardapioDelete']],

    ['GET',  '/admin/avaliacoes',      true,  true,  [AdminController::class, 'reviewsPage']],

    ['GET',  '/imagem/{id:\d+}',       false, true,  [ImageController::class, 'serve']],
];

$dispatcher = simpleDispatcher(function (RouteCollector $r) use ($routeDefs) {
    foreach ($routeDefs as [$method, $path, , , $action]) {
        $r->addRoute($method, $path, $action);
    }
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri        = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        exit('Página não encontrada.');

    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        exit('Método não permitido.');

    case Dispatcher::FOUND:
        [$class, $method] = $routeInfo[1];
        $vars = $routeInfo[2]; 

        $requireAdmin = false;
        $requireAuth  = false;
        foreach ($routeDefs as [$defMethod, $defPath, $admin, $auth, $action]) {
            if ($action[0] === $class && $action[1] === $method && $defMethod === $httpMethod) {
                $requireAdmin = $admin;
                $requireAuth  = $auth;
                break;
            }
        }

        if ($requireAdmin) {
            require_admin();
        } elseif ($requireAuth) {
            require_auth();
        }

        $class::$method($vars);
        break;
}
