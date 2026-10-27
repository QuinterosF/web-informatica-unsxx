<?php

namespace App\Models;

use App\Config\Database;
use App\Core\FileUploader;
use PDO;

class OfficialDocument
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Obtiene todos los documentos oficiales con el nombre del usuario que los subió
     */
    public function all(): array
    {
        $sql = "
            SELECT 
                d.*,
                u.usu_nom AS subido_por_nombre
            FROM documento_oficial d
            LEFT JOIN usuario u ON d.subido_por = u.usu_id
            ORDER BY d.gestion DESC, d.categoria, d.titulo
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca un documento oficial por ID con el nombre del usuario que lo subió
     */
    public function findById(int $id): ?array
    {
        $sql = "
            SELECT 
                d.*,
                u.usu_nom AS subido_por_nombre
            FROM documento_oficial d
            LEFT JOIN usuario u ON d.subido_por = u.usu_id
            WHERE d.id_doc = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Crea un nuevo documento oficial
     * @param array $data Array con: titulo, categoria, archivo_pdf, gestion, estado, subido_por
     * @return int ID del registro insertado
     * @throws \Exception si validación falla
     */
    public function create(array $data): int
    {
        $required = ['titulo', 'categoria', 'archivo_pdf', 'gestion', 'subido_por'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        $allowedCategories = ['plan_estudios', 'horario_clases', 'reglamento_titulacion', 'reglamento_admision', 'reglamento_interno', 'reglamento_macro', 'otro'];
        // Si la categoría no es válida, asignar 'otro' por defecto
        if (!in_array($data['categoria'], $allowedCategories, true)) {
            $data['categoria'] = 'otro';
        }

        $sql = "
            INSERT INTO documento_oficial (titulo, categoria, archivo_pdf, gestion, estado, subido_por)
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['titulo'],
            $data['categoria'],
            $data['archivo_pdf'],
            (int)$data['gestion'],
            (int)($data['estado'] ?? 1),
            (int)$data['subido_por']
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Actualiza un documento oficial existente
     */
    public function update(int $id, array $data): bool
    {
        $existing = $this->findById($id);
        if (!$existing) {
            throw new \Exception("El documento no existe");
        }

        $required = ['titulo', 'categoria', 'gestion'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        $allowedCategories = ['plan_estudios', 'horario_clases', 'reglamento_titulacion', 'reglamento_admision', 'reglamento_interno', 'reglamento_macro', 'otro'];
        // Si la categoría no es válida, asignar 'otro' por defecto
        if (!in_array($data['categoria'], $allowedCategories, true)) {
            $data['categoria'] = 'otro';
        }

        $archivoPdf = $existing['archivo_pdf'] ?? '';
        if (isset($data['archivo_pdf']) && $data['archivo_pdf'] !== '') {
            $archivoPdf = $data['archivo_pdf'];
        }

        $actualizadoPor = (int) ($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);

        $sql = "
            UPDATE documento_oficial 
            SET titulo = ?, categoria = ?, archivo_pdf = ?, gestion = ?, estado = ?, actualizado_por = ?
            WHERE id_doc = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['titulo'],
            $data['categoria'],
            $archivoPdf,
            (int)$data['gestion'],
            (int)($data['estado'] ?? 1),
            $actualizadoPor,
            $id
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Elimina un documento oficial (DELETE físico)
     */
    public function delete(int $id): bool
    {
        $existing = $this->findById($id);
        if (!$existing) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM documento_oficial WHERE id_doc = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}