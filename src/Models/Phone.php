<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Phone
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Obtiene todos los celulares
     */
    public function findAll(): array
    {
        $sql = "SELECT id_cel, nom_cel, num_cel, icono, estado FROM celular ORDER BY id_cel";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca un celular por ID
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT id_cel, nom_cel, num_cel, icono, estado FROM celular WHERE id_cel = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Crea un nuevo celular
     * @param array $data Array con: nom_cel, num_cel, icono (opcional), estado
     * @return int ID del registro insertado
     * @throws \Exception si validación falla
     */
    public function create(array $data): int
    {
        $required = ['nom_cel', 'num_cel'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        $icono = $data['icono'] ?? 'fab fa-whatsapp';
        $estado = $data['estado'] ?? 1;

        $sql = "
            INSERT INTO celular (nom_cel, num_cel, icono, estado)
            VALUES (?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nom_cel'],
            $data['num_cel'],
            $icono,
            (int)$estado
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Actualiza un celular existente
     * @param int $id ID del celular
     * @param array $data Array con: nom_cel, num_cel, icono (opcional), estado
     * @return bool true si se actualizó, false si no
     * @throws \Exception si validación falla
     */
    public function update(int $id, array $data): bool
    {
        $existing = $this->findById($id);
        if (!$existing) {
            throw new \Exception("El celular no existe");
        }

        $required = ['nom_cel', 'num_cel'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        $icono = $data['icono'] ?? $existing['icono'];
        $estado = $data['estado'] ?? 1;

        $sql = "
            UPDATE celular 
            SET nom_cel = ?, num_cel = ?, icono = ?, estado = ?
            WHERE id_cel = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nom_cel'],
            $data['num_cel'],
            $icono,
            (int)$estado,
            $id
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Elimina un celular (DELETE físico)
     */
    public function delete(int $id): bool
    {
        $existing = $this->findById($id);
        if (!$existing) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM celular WHERE id_cel = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Alias para findAll() - compatibilidad con SDD
     */
    public function all(): array
    {
        return $this->findAll();
    }
}