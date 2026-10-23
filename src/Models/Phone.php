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
        $sql = "SELECT id_cel, nom_cel, num_cel, icono, es_soporte, estado FROM celular ORDER BY id_cel";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca un celular por ID
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT id_cel, nom_cel, num_cel, icono, es_soporte, estado FROM celular WHERE id_cel = ?";
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
        $esSoporte = !empty($data['es_soporte']) ? 1 : 0;

        $this->pdo->beginTransaction();

        try {
            if ($esSoporte === 1) {
                $this->pdo->exec("UPDATE celular SET es_soporte = 0");
            }

            $sql = "
                INSERT INTO celular (nom_cel, num_cel, icono, es_soporte, estado)
                VALUES (?, ?, ?, ?, ?)
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['nom_cel'],
                $data['num_cel'],
                $icono,
                $esSoporte,
                (int)$estado
            ]);

            $id = (int) $this->pdo->lastInsertId();
            $this->pdo->commit();

            return $id;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
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
        $esSoporte = !empty($data['es_soporte']) ? 1 : 0;
        $actualizadoPor = (int) ($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);

        $this->pdo->beginTransaction();

        try {
            if ($esSoporte === 1) {
                $this->pdo->prepare("UPDATE celular SET es_soporte = 0 WHERE id_cel != ?")
                    ->execute([$id]);
            }

            $sql = "
                UPDATE celular
                SET nom_cel = ?, num_cel = ?, icono = ?, es_soporte = ?, estado = ?, actualizado_por = ?
                WHERE id_cel = ?
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['nom_cel'],
                $data['num_cel'],
                $icono,
                $esSoporte,
                (int)$estado,
                $actualizadoPor,
                $id
            ]);

            $this->pdo->commit();

            return $stmt->rowCount() > 0;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
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
     * Marca un celular como el único teléfono oficial de soporte técnico.
     */
    public function setAsSupport(int $id): bool
    {
        $existing = $this->findById($id);
        if (!$existing) {
            return false;
        }

        $updatedBy = (int) ($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);

        $this->pdo->beginTransaction();

        try {
            $this->pdo->exec("UPDATE celular SET es_soporte = 0");

            $stmt = $this->pdo->prepare("
                UPDATE celular
                SET es_soporte = 1, estado = 1, actualizado_por = ?
                WHERE id_cel = ?
            ");
            $stmt->execute([$updatedBy, $id]);

            $this->pdo->commit();

            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Devuelve el teléfono oficial de soporte activo.
     * Si no existe, usa el teléfono activo de mayor prioridad como respaldo.
     */
    public function getSupportPhone(): ?array
    {
        $sql = "
            SELECT id_cel, nom_cel, num_cel, icono, es_soporte, estado
            FROM celular
            WHERE es_soporte = 1 AND estado = 1
            ORDER BY id_cel ASC
            LIMIT 1
        ";
        $phone = $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($phone) {
            return $phone;
        }

        $sql = "
            SELECT id_cel, nom_cel, num_cel, icono, es_soporte, estado
            FROM celular
            WHERE estado = 1
            ORDER BY (
                nom_cel LIKE '%Sistema%'
                OR nom_cel LIKE '%Admin%'
                OR nom_cel LIKE '%Soporte%'
                OR nom_cel LIKE '%Direcci%'
            ) DESC, id_cel ASC
            LIMIT 1
        ";
        $phone = $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        return $phone ?: null;
    }

    /**
     * Alias para findAll() - compatibilidad con SDD
     */
    public function all(): array
    {
        return $this->findAll();
    }
}