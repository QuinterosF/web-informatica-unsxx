<?php

namespace App\Core;

/**
 * Helper de autorización basado en la sesión del usuario.
 * Centraliza la lógica de rol y de permisos por módulo.
 */
class Auth
{
    /**
     * Indica si el usuario autenticado tiene rol de administrador.
     */
    public static function isAdmin(): bool
    {
        $role = $_SESSION['user_role'] ?? $_SESSION['user']['role'] ?? null;
        return $role === 'admin';
    }

    /**
     * Indica si el usuario puede acceder a un módulo del panel.
     * El administrador siempre tiene acceso. Los laboratorios también
     * se habilitan cuando el usuario tiene laboratorios asignados.
     */
    public static function canAccessModule(string $moduleKey): bool
    {
        if (self::isAdmin()) {
            return true;
        }

        if ($moduleKey === 'laboratories' && !empty($_SESSION['user_lab_ids'])) {
            return true;
        }

        $modules = $_SESSION['user_modules'] ?? [];
        if (!is_array($modules)) {
            return false;
        }

        return in_array($moduleKey, $modules, true);
    }
}
