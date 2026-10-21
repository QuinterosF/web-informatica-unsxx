<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Session;

class AuthMiddleware
{
    public function handle(): bool
    {
        Session::start();
        
        if (!Session::isLoggedIn()) {
            header('Location: /login');
            exit;
        }
        
        // Extraer la fecha de login directamente del array guardado por el AuthController
        $userData = Session::get('user');
        $loggedAt = is_array($userData) && isset($userData['logged_at']) ? $userData['logged_at'] : null;
        $lifetime = (int)($_ENV['SESSION_LIFETIME'] ?? 7200);
        
        if ($loggedAt && (time() - $loggedAt > $lifetime)) {
            Session::destroy();
            header('Location: /login?expired=1');
            exit;
        }
        
        return true;
    }

    public function requireRole(string ...$roles): void
    {
        // 1. Validar primero que esté logueado
        $this->handle();

        // 2. Extraer el rol del usuario desde la sesión
        $userRole = Session::getRole() ?? '';

        // 3. Verificar si su rol está en la lista de permitidos
        if (!in_array($userRole, $roles, true)) {
            header('Location: /unauthorized');
            exit;
        }
    }

    /**
     * Verifica que el usuario tenga acceso a al menos uno de los módulos indicados.
     * El administrador siempre pasa. Para laboratorios, también se permite el
     * acceso cuando el usuario es responsable de uno o más laboratorios.
     */
    public function requireModule(string ...$modules): void
    {
        $this->handle();

        foreach ($modules as $module) {
            if (Auth::canAccessModule($module)) {
                return;
            }
        }

        $_SESSION['error'] = 'No tienes permisos para acceder a este módulo.';
        header('Location: /dashboard');
        exit;
    }
}