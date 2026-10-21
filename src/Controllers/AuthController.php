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

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['usu_pass'])) {
            if ((int) $user['estado'] !== 1) {
                $this->redirect('/login?error=disabled');
            }

            Session::regenerate();

            $modules = json_decode($user['modulos'] ?? '[]', true);
            if (!is_array($modules)) {
                $modules = [];
            }

            $academicId = $userModel->getAcademicIdByUserId((int) $user['usu_id']);
            $labIds = $academicId !== null
                ? $userModel->getLabIdsByAcademicId($academicId)
                : [];

            Session::set('user', [
                'id'       => (int) $user['usu_id'],
                'name'     => $user['usu_nom'],
                'email'    => $user['usu_correo'],
                'role_id'  => (int) $user['rol_id'],
                'role'     => $user['rol_nombre'],
                'logged_at'=> time()
            ]);
            Session::set('user_id', (int) $user['usu_id']);
            Session::set('user_name', $user['usu_nom']);
            Session::set('user_role', $user['rol_nombre']);
            Session::set('user_modules', $modules);
            Session::set('user_acad_id', $academicId);
            Session::set('user_lab_ids', $labIds);

            $this->redirect('/dashboard');
        }

        $this->redirect('/login?error=invalid');
    }

    public function logout(): void
    {
        Session::destroy();
        $this->redirect('/login?logout=1');
    }
}