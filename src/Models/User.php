<?php

namespace App\Models;

use App\Config\Database;

class User
{
    public static function findByEmail(string $email): ?array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
            SELECT u.usu_id, u.usu_nom, u.usu_correo, u.usu_pass, u.estado,
                   ur.rol_id, r.rol_nombre
            FROM usuario u
            JOIN usuario_rol ur ON u.usu_id = ur.usu_id
            JOIN rol r ON ur.rol_id = r.rol_id
            WHERE u.usu_correo = ? AND u.estado = 1
            LIMIT 1
        ");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public static function findById(int $id): ?array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
            SELECT u.usu_id, u.usu_nom, u.usu_correo, u.estado,
                   ur.rol_id, r.rol_nombre
            FROM usuario u
            JOIN usuario_rol ur ON u.usu_id = ur.usu_id
            JOIN rol r ON ur.rol_id = r.rol_id
            WHERE u.usu_id = ? AND u.estado = 1
            LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}