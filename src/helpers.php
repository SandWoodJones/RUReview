<?php

require_once __DIR__ . '/app.php';

function render(string $view, array $data = []): void
{
    extract($data);
?>
    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <?php require_once __DIR__ . '/views/header.php'; ?>
    </head>

    <body class="bg-gray-50 min-h-screen">
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php require_once __DIR__ . '/views/navbar.php'; ?>
        <?php endif; ?>
        <?php require __DIR__ . "/views/{$view}.php"; ?>
    </body>

    </html>
<?php
}

function redirect(string $url): void
{
    header("Location: {$url}");
    exit();
}

function require_auth(): void
{
    if (!isset($_SESSION['user_id'])) {
        redirect('/login');
    }
}

function require_admin(): void
{
    require_auth();
    if (empty($_SESSION['is_admin'])) {
        http_response_code(403);
        exit('Acesso negado.');
    }
}

function format_date(string $date): string {
    return date('d/m/Y', strtotime($date));
}
