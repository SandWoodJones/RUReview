<?php

function render(string $view, array $data = []): void
{
    extract($data);
    require_once __DIR__ . '/app.php';
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
        <?php require_once __DIR__ . "/views/{$view}.php"; ?>
    </body>

    </html>
<?php
}

function redirect(string $url): void
{
    header("Location: {$url}");
    exit();
}
