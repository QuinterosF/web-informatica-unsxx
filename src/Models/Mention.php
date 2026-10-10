<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Mention
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Obtiene todas las menciones con el nombre del coordinador (LEFT JOIN academico)
     */
    public function findAll(): array
    {
        $sql = "
            SELECT 
                m.id_men,
                m.nombre,
                m.perfil_profesional,
                m.coordinador,
                m.escudo_url,
                m.estado,
                m.creado_por,
                a.nom_acad AS coordinador_nombre
            FROM mencion_instituto m
            LEFT JOIN academico a ON m.coordinador = a.id_acad
            ORDER BY m.nombre
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Alias para findAll() - compatibilidad con SDD
     */
    public function all(): array
    {
        return $this->findAll();
    }

    /**
     * Busca una mención por ID con el nombre del coordinador (LEFT JOIN academico)
     */
    public function findById(int $id): ?array
    {
        $sql = "
            SELECT 
                m.id_men,
                m.nombre,
                m.perfil_profesional,
                m.coordinador,
                m.escudo_url,
                m.estado,
                m.creado_por,
                a.nom_acad AS coordinador_nombre
            FROM mencion_instituto m
            LEFT JOIN academico a ON m.coordinador = a.id_acad
            WHERE m.id_men = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Verifica si un nombre de mención ya existe (opcionalmente excluyendo un ID)
     */
    public function nombreExists(string $nombre, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM mencion_instituto WHERE nombre = ? AND id_men != ?");
            $stmt->execute([$nombre, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM mencion_instituto WHERE nombre = ?");
            $stmt->execute([$nombre]);
        }
        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Verifica si el coordinador existe (si se proporciona)
     */
    public function coordinadorExists(?int $coordinadorId): bool
    {
        if ($coordinadorId === null || $coordinadorId === 0) {
            return true; // Es opcional
        }
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM academico WHERE id_acad = ?");
        $stmt->execute([$coordinadorId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Crea una nueva mención/instituto
     * @param array $data Array con: nombre, perfil_profesional, coordinador (opcional), escudo_url (opcional), estado, creado_por
     * @return int ID del registro insertado
     * @throws \Exception si validación falla
     */
    public function create(array $data): int
    {
        // Validaciones
        $required = ['nombre', 'perfil_profesional', 'creado_por'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        if ($this->nombreExists($data['nombre'])) {
            throw new \Exception("Ya existe una mención/instituto con ese nombre");
        }

        // Coordinador es opcional, validar solo si se proporciona
        $coordinador = !empty($data['coordinador']) ? (int)$data['coordinador'] : null;
        if ($coordinador !== null && !$this->coordinadorExists($coordinador)) {
            throw new \Exception("El coordinador seleccionado no existe");
        }

        $escudo = $data['escudo_url'] ?? 'default.png';

        $sql = "
            INSERT INTO mencion_instituto (nombre, perfil_profesional, coordinador, escudo_url, estado, creado_por)
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nombre'],
            $data['perfil_profesional'],
            $coordinador,
            $escudo,
            (int)($data['estado'] ?? 1),
            (int)$data['creado_por']
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Actualiza una mención/instituto existente
     * @param int $id ID de la mención
     * @param array $data Array con: nombre, perfil_profesional, coordinador (opcional), escudo_url (opcional), estado
     * @return bool true si se actualizó, false si no
     * @throws \Exception si validación falla
     */
    public function update(int $id, array $data): bool
    {
        // Verificar existencia
        $existing = $this->findById($id);
        if (!$existing) {
            throw new \Exception("La mención/instituto no existe");
        }

        // Validaciones
        $required = ['nombre', 'perfil_profesional'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        if ($this->nombreExists($data['nombre'], $id)) {
            throw new \Exception("Ya existe una mención/instituto con ese nombre");
        }

        // Coordinador opcional
        $coordinador = !empty($data['coordinador']) ? (int)$data['coordinador'] : null;
        if ($coordinador !== null && !$this->coordinadorExists($coordinador)) {
            throw new \Exception("El coordinador seleccionado no existe");
        }

        $escudo = $data['escudo_url'] ?? $existing['escudo_url'];

        $sql = "
            UPDATE mencion_instituto 
            SET nombre = ?, perfil_profesional = ?, coordinador = ?, escudo_url = ?, estado = ?
            WHERE id_men = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nombre'],
            $data['perfil_profesional'],
            $coordinador,
            $escudo,
            (int)($data['estado'] ?? 1),
            $id
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Elimina una mención/instituto (DELETE físico)
     */
    public function delete(int $id): bool
    {
        $existing = $this->findById($id);
        if (!$existing) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM mencion_instituto WHERE id_men = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}