<?php
require_once __DIR__ . '/../app.php';
require_once __DIR__ . '/../controllers/avaliar_controller.php';
?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <?php require_once __DIR__ . '/../views/header.php'; ?>
</head>

<body class="bg-gray-50 min-h-screen">
    <?php require_once __DIR__ . '/../views/navbar.php'; ?>
    <?php avaliar_page(); ?>
</body>

</html>
