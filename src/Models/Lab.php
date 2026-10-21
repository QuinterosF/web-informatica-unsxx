<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Lab
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Obtiene todos los laboratorios con su responsable (LEFT JOIN academico)
     * Incluye tanto activos como inactivos
     */
    public function findAll(): array
    {
        $sql = "
            SELECT 
                l.id_lab,
                l.nombre_lab,
                l.horario_lab,
                l.activo,
                l.resp_acad_id,
                a.id_acad,
                a.nom_acad AS responsable_nombre,
                a.email_acad AS responsable_email,
                (SELECT COUNT(*) FROM laboratorio_galeria g WHERE g.id_lab = l.id_lab) AS total_fotos
            FROM laboratorio l
            LEFT JOIN academico a ON l.resp_acad_id = a.id_acad
            ORDER BY l.nombre_lab
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene solo laboratorios activos (para uso público/selectores)
     */
    public function getActiveLabs(): array
    {
        $sql = "
            SELECT 
                l.id_lab,
                l.nombre_lab,
                l.horario_lab,
                l.activo,
                l.resp_acad_id,
                a.id_acad,
                a.nom_acad AS responsable_nombre,
                a.email_acad AS responsable_email,
                (SELECT COUNT(*) FROM laboratorio_galeria g WHERE g.id_lab = l.id_lab) AS total_fotos
            FROM laboratorio l
            LEFT JOIN academico a ON l.resp_acad_id = a.id_acad
            WHERE l.activo = 1
            ORDER BY l.nombre_lab
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
     * Busca un laboratorio por ID con su responsable (LEFT JOIN academico)
     */
    public function findById(int $id): ?array
    {
        $sql = "
            SELECT 
                l.id_lab,
                l.nombre_lab,
                l.horario_lab,
                l.activo,
                l.resp_acad_id,
                a.id_acad,
                a.nom_acad AS responsable_nombre,
                a.email_acad AS responsable_email
            FROM laboratorio l
            LEFT JOIN academico a ON l.resp_acad_id = a.id_acad
            WHERE l.id_lab = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Obtiene un subconjunto de laboratorios por ID (para responsables).
     * @param int[] $ids
     */
    public function findByIds(array $ids): array
    {
        $ids = array_values(array_unique(array_filter(
            array_map('intval', $ids),
            static fn (int $id): bool => $id > 0
        )));

        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(', ', array_fill(0, count($ids), '?'));

        $sql = "
            SELECT 
                l.id_lab,
                l.nombre_lab,
                l.horario_lab,
                l.activo,
                l.resp_acad_id,
                a.id_acad,
                a.nom_acad AS responsable_nombre,
                a.email_acad AS responsable_email,
                (SELECT COUNT(*) FROM laboratorio_galeria g WHERE g.id_lab = l.id_lab) AS total_fotos
            FROM laboratorio l
            LEFT JOIN academico a ON l.resp_acad_id = a.id_acad
            WHERE l.id_lab IN ({$placeholders})
            ORDER BY l.nombre_lab
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($ids);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Verifica si un nombre de laboratorio ya existe (opcionalmente excluyendo un ID)
     */
    public function nombreExists(string $nombre, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM laboratorio WHERE nombre_lab = ? AND id_lab != ?");
            $stmt->execute([$nombre, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM laboratorio WHERE nombre_lab = ?");
            $stmt->execute([$nombre]);
        }
        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Verifica si el responsable existe
     */
    public function responsableExists(int $respAcadId): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM academico WHERE id_acad = ?");
        $stmt->execute([$respAcadId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Crea un nuevo laboratorio
     * @param array $data Array con: nombre_lab, resp_acad_id, horario_lab (opcional), activo (opcional, default 1)
     * @return int ID del registro insertado
     * @throws \Exception si validación falla
     */
    public function create(array $data): int
    {
        // Validaciones
        $required = ['nombre_lab', 'resp_acad_id'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        if ($this->nombreExists($data['nombre_lab'])) {
            throw new \Exception("Ya existe un laboratorio con ese nombre");
        }

        if (!$this->responsableExists((int)$data['resp_acad_id'])) {
            throw new \Exception("El responsable seleccionado no existe");
        }

        $horarioLab = $data['horario_lab'] ?? null;
        $creadoPor = (int) ($data['creado_por'] ?? $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);

        $sql = "
            INSERT INTO laboratorio (nombre_lab, resp_acad_id, horario_lab, activo, creado_por)
            VALUES (?, ?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nombre_lab'],
            (int)$data['resp_acad_id'],
            $horarioLab,
            (int)($data['activo'] ?? 1),
            $creadoPor
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Actualiza un laboratorio existente
     * @param int $id ID del laboratorio
     * @param array $data Array con: nombre_lab, resp_acad_id, horario_lab (opcional), activo (opcional, default 1)
     * @return bool true si se actualizó, false si no
     * @throws \Exception si validación falla
     */
    public function update(int $id, array $data): bool
    {
        // Verificar existencia
        $existing = $this->findById($id);
        if (!$existing) {
            throw new \Exception("El laboratorio no existe");
        }

        // Validaciones
        $required = ['nombre_lab', 'resp_acad_id'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        if ($this->nombreExists($data['nombre_lab'], $id)) {
            throw new \Exception("Ya existe un laboratorio con ese nombre");
        }

        if (!$this->responsableExists((int)$data['resp_acad_id'])) {
            throw new \Exception("El responsable seleccionado no existe");
        }

        $horarioLab = $data['horario_lab'] ?? $existing['horario_lab'];
        $actualizadoPor = (int) ($data['actualizado_por'] ?? $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);

        $sql = "
            UPDATE laboratorio 
            SET nombre_lab = ?, resp_acad_id = ?, horario_lab = ?, activo = ?, actualizado_por = ?
            WHERE id_lab = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nombre_lab'],
            (int)$data['resp_acad_id'],
            $horarioLab,
            (int)($data['activo'] ?? 1),
            $actualizadoPor,
            $id
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Elimina un laboratorio (DELETE físico)
     */
    public function delete(int $id): bool
    {
        $existing = $this->findById($id);
        if (!$existing) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM laboratorio WHERE id_lab = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Obtiene todas las fotos de la galería de un laboratorio
     * Ordenadas con la portada primero, luego por fecha descendente
     */
    public function getGallery(int $id_lab): array
    {
        $sql = "
            SELECT * FROM laboratorio_galeria 
            WHERE id_lab = ? 
            ORDER BY es_portada DESC, subido_el DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_lab]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una foto específica de la galería por su ID
     */
    public function getPhoto(int $id_foto): ?array
    {
        $sql = "SELECT * FROM laboratorio_galeria WHERE id_foto = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_foto]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Agrega una nueva foto a la galería del laboratorio
     */
    public function addPhoto(int $id_lab, string $archivo_url, int $es_portada = 0, ?int $subido_por = null): bool
    {
        $subidoPor = $subido_por ?? (int) ($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);

        $sql = "
            INSERT INTO laboratorio_galeria (id_lab, archivo_url, es_portada, subido_por)
            VALUES (?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_lab, $archivo_url, (int)$es_portada, $subidoPor]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Elimina una foto de la galería
     */
    public function deletePhoto(int $id_foto): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM laboratorio_galeria WHERE id_foto = ?");
        $stmt->execute([$id_foto]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Establece una foto como portada del laboratorio
     * Usa transacción para asegurar integridad
     */
    public function setCoverPhoto(int $id_foto, int $id_lab): void
    {
        try {
            $this->pdo->beginTransaction();

            // Quitar portada actual
            $sql = "UPDATE laboratorio_galeria SET es_portada = 0 WHERE id_lab = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_lab]);

            // Asignar nueva portada
            $sql = "UPDATE laboratorio_galeria SET es_portada = 1 WHERE id_foto = ? AND id_lab = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_foto, $id_lab]);

            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}