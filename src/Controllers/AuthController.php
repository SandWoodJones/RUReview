<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController
{
    public static function loginPage(): void
    {
        $error = self::getError();
        render('login_view', ['error' => $error]);
    }

    public static function cadastroPage(): void
    {
        $error = self::getError();
        render('cadastro_view', ['error' => $error]);
    }

    public static function loginAction(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            self::setError('Preencha todos os campos.');
            redirect('/login');
        }

        $user = UserModel::findByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            self::setError('Usuário ou senha inválidos.');
            redirect('/login');
        }

        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = (bool) $user['is_admin'];

        redirect('/');
    }

    public static function cadastrarAction(): void
    {
        $username         = trim($_POST['username'] ?? '');
        $password         = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if ($username === '' || $password === '') {
            self::setError('Preencha todos os campos.');
            redirect('/cadastro');
        }

        if (strlen($password) < 6) {
            self::setError('A senha deve ter pelo menos 6 caracteres.');
            redirect('/cadastro');
        }

        if ($password !== $password_confirm) {
            self::setError('As senhas não coincidem.');
            redirect('/cadastro');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $ok   = UserModel::create($username, $hash);

        if (!$ok) {
            self::setError('Este nome de usuário já está em uso.');
            redirect('/cadastro');
        }

        $_SESSION['success'] = 'Conta criada! Faça login para continuar.';
        redirect('/login');
    }

    public static function logoutAction(): void
    {
        session_destroy();
        redirect('/login');
    }

    public static function setError(string $msg): void
    {
        $_SESSION['error'] = $msg;
    }

    public static function getError(): ?string
    {
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        return $error;
    }
}