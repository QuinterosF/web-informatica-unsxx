<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Extension
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Obtiene todas las extensiones con el nombre del creador (LEFT JOIN usuario)
     */
    public function all(): array
    {
        $sql = "
            SELECT 
                e.*,
                u.usu_nom AS creador_nombre
            FROM extension e
            LEFT JOIN usuario u ON e.creado_por = u.usu_id
            ORDER BY e.fech_ext DESC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca una extensión por ID con el nombre del creador
     */
    public function findById(int $id): ?array
    {
        $sql = "
            SELECT 
                e.*,
                u.usu_nom AS creador_nombre
            FROM extension e
            LEFT JOIN usuario u ON e.creado_por = u.usu_id
            WHERE e.id_ext = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Crea una nueva extensión
     * @param array $data Array con: nom_ext, fech_ext, desc_ext, creado_por
     * @return int ID del registro insertado
     * @throws \Exception si validación falla
     */
    public function create(array $data): int
    {
        // Validaciones
        $required = ['nom_ext', 'fech_ext', 'desc_ext', 'creado_por'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        // Validar fecha
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fech_ext'])) {
            throw new \Exception("La fecha debe tener formato YYYY-MM-DD");
        }

        // Validar unicidad del nombre (opcional, según reglas de negocio)
        if ($this->nombreExists($data['nom_ext'])) {
            throw new \Exception("Ya existe una extensión con ese nombre");
        }

        $sql = "
            INSERT INTO extension (nom_ext, fech_ext, desc_ext, creado_por)
            VALUES (?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nom_ext'],
            $data['fech_ext'],
            $data['desc_ext'],
            (int)$data['creado_por']
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Actualiza una extensión existente
     */
    public function update(int $id, array $data): bool
    {
        // Verificar existencia
        $existing = $this->findById($id);
        if (!$existing) {
            throw new \Exception("La extensión no existe");
        }

        // Validaciones
        $required = ['nom_ext', 'fech_ext', 'desc_ext'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        // Validar fecha
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fech_ext'])) {
            throw new \Exception("La fecha debe tener formato YYYY-MM-DD");
        }

        if ($this->nombreExists($data['nom_ext'], $id)) {
            throw new \Exception("Ya existe una extensión con ese nombre");
        }

        $sql = "
            UPDATE extension 
            SET nom_ext = ?, fech_ext = ?, desc_ext = ?
            WHERE id_ext = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nom_ext'],
            $data['fech_ext'],
            $data['desc_ext'],
            $id
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Elimina una extensión (DELETE físico)
     */
    public function delete(int $id): bool
    {
        $existing = $this->findById($id);
        if (!$existing) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM extension WHERE id_ext = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Verifica si un nombre ya existe (opcionalmente excluyendo un ID)
     */
    public function nombreExists(string $nombre, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM extension WHERE nom_ext = ? AND id_ext != ?");
            $stmt->execute([$nombre, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM extension WHERE nom_ext = ?");
            $stmt->execute([$nombre]);
        }
        return (int)$stmt->fetchColumn() > 0;
    }
}