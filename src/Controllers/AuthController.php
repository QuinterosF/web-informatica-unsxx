<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/login', ['error' => $_GET['error'] ?? null]);
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $this->redirect('/login?error=empty');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirect('/login?error=invalid');
        }

        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user['usu_pass'])) {
            Session::regenerate();
            Session::set('user', [
                'id'       => $user['usu_id'],
                'name'     => $user['usu_nom'],
                'email'    => $user['usu_correo'],
                'role_id'  => $user['rol_id'],
                'role'     => $user['rol_nombre'],
                'logged_at'=> time()
            ]);
            $this->redirect($user['rol_nombre'] === 'admin' ? '/admin' : '/dashboard');
        }

        $this->redirect('/login?error=invalid');
    }

    public function logout(): void
    {
        Session::destroy();
        $this->redirect('/login?logout=1');
    }
}