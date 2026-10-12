<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Admission
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Obtiene todas las admisiones con el nombre del usuario que las actualizó (LEFT JOIN usuario)
     * Incluye todos los registros (sin filtro por tipo)
     */
    public function all(): array
    {
        $sql = "
            SELECT 
                a.*,
                u.usu_nom AS actualizado_por_nombre
            FROM admision a
            LEFT JOIN usuario u ON a.actualizado_por = u.usu_id
            ORDER BY a.tipo, a.id_adm
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca una admisión por ID con el nombre del usuario que la actualizó
     */
    public function findById(int $id): ?array
    {
        $sql = "
            SELECT 
                a.*,
                u.usu_nom AS actualizado_por_nombre
            FROM admision a
            LEFT JOIN usuario u ON a.actualizado_por = u.usu_id
            WHERE a.id_adm = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Crea una nueva admisión
     * @param array $data Array con: proc_adm, req_adm, img_adm, tipo, actualizado_por, gestion, estado
     * @return int ID del registro insertado
     * @throws \Exception si validación falla
     */
    public function create(array $data): int
    {
        // Validaciones
        $required = ['proc_adm', 'req_adm', 'tipo', 'actualizado_por', 'gestion'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        // Validar tipo
        if (!in_array($data['tipo'], ['prueba', 'preuni'])) {
            throw new \Exception("El tipo debe ser 'prueba' o 'preuni'");
        }

        // Validar gestión (año)
        if ((int)$data['gestion'] < 1900 || (int)$data['gestion'] > 2100) {
            throw new \Exception("La gestión debe ser un año válido");
        }

        $imgAdm = $data['img_adm'] ?? null;

        $sql = "
            INSERT INTO admision (proc_adm, req_adm, img_adm, tipo, actualizado_por, gestion, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['proc_adm'],
            $data['req_adm'],
            $imgAdm,
            $data['tipo'],
            (int)$data['actualizado_por'],
            (int)$data['gestion'],
            (int)($data['estado'] ?? 1)
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        // Verificar existencia
        $existing = $this->findById($id);
        if (!$existing) {
            throw new \Exception("La admisión no existe");
        }

        // Validaciones
        $required = ['proc_adm', 'req_adm', 'tipo', 'gestion'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        // Validar tipo
        if (!in_array($data['tipo'], ['prueba', 'preuni'])) {
            throw new \Exception("El tipo debe ser 'prueba' o 'preuni'");
        }

        // Validar gestión (año)
        if ((int)$data['gestion'] < 1900 || (int)$data['gestion'] > 2100) {
            throw new \Exception("La gestión debe ser un año válido");
        }

        $imgAdm = array_key_exists('img_adm', $data) ? $data['img_adm'] : ($existing['img_adm'] ?? null);

        $sql = "
            UPDATE admision 
            SET proc_adm = ?, req_adm = ?, img_adm = ?, tipo = ?, actualizado_por = ?, gestion = ?, estado = ?
            WHERE id_adm = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['proc_adm'],
            $data['req_adm'],
            $imgAdm,
            $data['tipo'],
            (int)$data['actualizado_por'],
            (int)$data['gestion'],
            (int)($data['estado'] ?? 1),
            $id
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Elimina una admisión (DELETE físico)
     */
    public function delete(int $id): bool
    {
        $existing = $this->findById($id);
        if (!$existing) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM admision WHERE id_adm = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}