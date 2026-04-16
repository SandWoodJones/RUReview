<?php

require_once __DIR__ . '/../models/user_model.php';

function render(string $view, array $data = []): void
{
    extract($data);
    require_once __DIR__ . '/../app.php';
?>
    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <?php require_once __DIR__ . '/../views/header.php'; ?>
    </head>

    <body class="bg-gray-50 min-h-screen">

        <?php
        if (isset($_SESSION['user_id'])) {
            require_once __DIR__ . '/../views/navbar.php';
        }
        ?>

        <?php require_once __DIR__ . "/../views/{$view}.php"; ?>

    </body>

    </html>
<?php
}


function redirect(string $url): void
{
    header("Location: {$url}");
    exit();
}


function login_page(): void
{
    $error = session_get_error();
    render('login_view', ['error' => $error]);
}

function cadastro_page(): void
{
    $error = session_get_error();
    render('cadastro_view', ['error' => $error]);
}


function login_action(): void
{
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';


    if ($username === '' || $password === '') {
        session_set_error('Preencha todos os campos.');
        redirect('/login');
    }

    $user = find_user_by_username($username);

    if (!$user || $user['password'] !== $password) {
        session_set_error('Usuário ou senha inválidos.');
        redirect('/login');
    }


    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username']  = $user['username'];
    $_SESSION['is_admin']  = (bool) $user['is_admin'];

    redirect('/');
}

function cadastrar_action(): void
{
    $username         = trim($_POST['username']         ?? '');
    $password         = $_POST['password']         ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';


    if ($username === '' || $password === '') {
        session_set_error('Preencha todos os campos.');
        redirect('/cadastro');
    }

    if (strlen($password) < 6) {
        session_set_error('A senha deve ter pelo menos 6 caracteres.');
        redirect('/cadastro');
    }

    if ($password !== $password_confirm) {
        session_set_error('As senhas não coincidem.');
        redirect('/cadastro');
    }

    $ok = create_user($username, $password);

    if (!$ok) {
        session_set_error('Este nome de usuário já está em uso.');
        redirect('/cadastro');
    }


    $_SESSION['success'] = 'Conta criada! Faça login para continuar.';
    redirect('/login');
}

function logout_action(): void
{
    session_destroy();
    redirect('/login');
}

function session_set_error(string $msg): void
{
    $_SESSION['error'] = $msg;
}

function session_get_error(): ?string
{
    $error = $_SESSION['error'] ?? null;
    unset($_SESSION['error']);
    return $error;
}
