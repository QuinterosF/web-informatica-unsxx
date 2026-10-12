<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Announcement
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Obtiene todos los comunicados con el nombre del autor (LEFT JOIN usuario)
     * Incluye tanto borradores como publicados (sin filtro estado)
     */
    public function all(): array
    {
        $sql = "
            SELECT 
                c.*,
                u.usu_nom AS autor_nombre
            FROM comunicado c
            LEFT JOIN usuario u ON c.publicado_por = u.usu_id
            ORDER BY c.fech_com DESC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca un comunicado por ID con el nombre del autor
     */
    public function findById(int $id): ?array
    {
        $sql = "
            SELECT 
                c.*,
                u.usu_nom AS autor_nombre
            FROM comunicado c
            LEFT JOIN usuario u ON c.publicado_por = u.usu_id
            WHERE c.id_com = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Crea un nuevo comunicado
     * @param array $data Array con: nom_com, fech_com, desc_com, doc_com, foto_com, publicado_por, estado
     * @return int ID del registro insertado
     * @throws \Exception si validación falla
     */
    public function create(array $data): int
    {
        // Validaciones
        $required = ['nom_com', 'fech_com', 'publicado_por'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        // Validar fecha
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fech_com'])) {
            throw new \Exception("La fecha debe tener formato YYYY-MM-DD");
        }

        // Validar estado (0 o 1)
        $estado = (int)($data['estado'] ?? 0);
        if (!in_array($estado, [0, 1])) {
            throw new \Exception("El estado debe ser 0 (borrador) o 1 (publicado)");
        }

        // Usar directamente los valores que vienen del controlador (ya procesados por FileUploader)
        $docCom = $data['doc_com'] ?? null;
        $fotoCom = $data['foto_com'] ?? null;

        $sql = "
            INSERT INTO comunicado (nom_com, fech_com, desc_com, doc_com, foto_com, publicado_por, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nom_com'],
            $data['fech_com'],
            $data['desc_com'],
            $docCom,
            $fotoCom,
            (int)$data['publicado_por'],
            (int)($data['estado'] ?? 0)
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Actualiza un comunicado existente
     */
    public function update(int $id, array $data): bool
    {
        // Verificar existencia
        $existing = $this->findById($id);
        if (!$existing) {
            throw new \Exception("El comunicado no existe");
        }

        // Validaciones
        $required = ['nom_com', 'fech_com'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        // Validar fecha
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fech_com'])) {
            throw new \Exception("La fecha debe tener formato YYYY-MM-DD");
        }

        // Validar estado
        $estado = (int)($data['estado'] ?? 0);
        if (!in_array($estado, [0, 1])) {
            throw new \Exception("El estado debe ser 0 (borrador) o 1 (publicado)");
        }

        // Usar directamente los valores que vienen del controlador (ya procesados por FileUploader)
        // Si no vienen en $data, mantener los existentes
        $docCom = $data['doc_com'] ?? $existing['doc_com'] ?? null;
        $fotoCom = $data['foto_com'] ?? $existing['foto_com'] ?? null;

        $sql = "
            UPDATE comunicado 
            SET nom_com = ?, fech_com = ?, desc_com = ?, doc_com = ?, foto_com = ?, estado = ?
            WHERE id_com = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nom_com'],
            $data['fech_com'],
            $data['desc_com'],
            $docCom,
            $fotoCom,
            (int)($data['estado'] ?? 0),
            $id
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Elimina un comunicado (DELETE físico)
     */
    public function delete(int $id): bool
    {
        $existing = $this->findById($id);
        if (!$existing) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM comunicado WHERE id_com = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Verifica si un nombre ya existe (opcionalmente excluyendo un ID)
     */
    public function nombreExists(string $nombre, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM comunicado WHERE nom_com = ? AND id_com != ?");
            $stmt->execute([$nombre, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM comunicado WHERE nom_com = ?");
            $stmt->execute([$nombre]);
        }
        return (int)$stmt->fetchColumn() > 0;
    }
}