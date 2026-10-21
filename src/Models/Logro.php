<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Logro
{
    private PDO $pdo;

    /** Posiciones válidas según el ENUM de la tabla */
    public const POSICIONES = [
        '1er Lugar',
        '2do Lugar',
        '3er Lugar',
        'Reconocimiento',
        'Mención Honorífica',
        'Otro',
    ];

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Lista todos los logros, del más reciente al más antiguo
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM logro ORDER BY fech_log DESC, id_log DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca un logro por ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM logro WHERE id_log = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Crea un logro
     * @param array $data Campos: posicion, nom_log, categoria_logro, lugar_log, foto_logro, fech_log, activo, registrado_por
     * @return int ID del registro insertado
     * @throws \Exception si la validación falla
     */
    public function create(array $data): int
    {
        $this->validate($data);

        $registradoPor = (int) ($data['registrado_por'] ?? $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);

        $sql = "
            INSERT INTO logro (posicion, nom_log, categoria_logro, lugar_log, foto_logro, fech_log, activo, registrado_por)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['posicion'],
            $data['nom_log'],
            !empty($data['categoria_logro']) ? $data['categoria_logro'] : null,
            !empty($data['lugar_log']) ? $data['lugar_log'] : null,
            !empty($data['foto_logro']) ? $data['foto_logro'] : null,
            $data['fech_log'],
            !empty($data['activo']) ? 1 : 0,
            $registradoPor
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza un logro (no modifica registrado_por)
     */
    public function update(int $id, array $data): bool
    {
        $existing = $this->findById($id);
        if (!$existing) {
            throw new \Exception("El logro no existe");
        }

        $this->validate($data);

        // Solo se modifica foto_logro si la llave existe en $data.
        // Si no existe, se conserva la foto actual (permite null explícito).
        $foto = array_key_exists('foto_logro', $data)
            ? $data['foto_logro']
            : ($existing['foto_logro'] ?? null);

        $actualizadoPor = (int) ($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);

        $sql = "
            UPDATE logro
            SET posicion = ?, nom_log = ?, categoria_logro = ?, lugar_log = ?, foto_logro = ?, fech_log = ?, activo = ?, actualizado_por = ?
            WHERE id_log = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['posicion'],
            $data['nom_log'],
            !empty($data['categoria_logro']) ? $data['categoria_logro'] : null,
            !empty($data['lugar_log']) ? $data['lugar_log'] : null,
            !empty($foto) ? $foto : null,
            $data['fech_log'],
            !empty($data['activo']) ? 1 : 0,
            $actualizadoPor,
            $id
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Elimina un logro
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM logro WHERE id_log = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Alterna el estado activo/inactivo de un logro
     */
    public function toggleActive(int $id): void
    {
        $stmt = $this->pdo->prepare("UPDATE logro SET activo = 1 - activo WHERE id_log = :id");
        $stmt->execute([':id' => $id]);
    }

    /**
     * Valida los campos obligatorios
     */
    private function validate(array $data): void
    {
        foreach (['posicion', 'nom_log', 'fech_log'] as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        if (!in_array($data['posicion'], self::POSICIONES, true)) {
            throw new \Exception("La posición seleccionada no es válida");
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fech_log'])) {
            throw new \Exception("La fecha debe tener formato YYYY-MM-DD");
        }
    }
}