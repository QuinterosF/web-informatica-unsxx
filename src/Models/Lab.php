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
     * Genera un UUID para el PDF del horario
     */
    private function generatePdfUuid(string $extension = 'pdf'): string
    {
        return bin2hex(random_bytes(16)) . '.' . strtolower($extension);
    }

    /**
     * Valida que el archivo sea PDF
     */
    private function validatePdf(array $file): ?string
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowedMimes = ['application/pdf'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedMimes)) {
            return 'El archivo debe ser un PDF válido';
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'pdf') {
            return 'La extensión debe ser .pdf';
        }

        return null;
    }

    /**
     * Obtiene todos los laboratorios con su responsable (JOIN academico)
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
                a.email_acad AS responsable_email
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
                a.email_acad AS responsable_email
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
     * Busca un laboratorio por ID con su responsable (JOIN academico)
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
     * Verifica si un nombre de laboratorio ya existe (opcionalmente excluyendo un ID)
     */
    public function nombreExists(string $nombre, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM laboratorio WHERE nombre_lab = ? AND id_lab != ? AND activo = 1");
            $stmt->execute([$nombre, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM laboratorio WHERE nombre_lab = ? AND activo = 1");
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

        // Validar PDF si se subió
        if (isset($data['horario_file']) && $data['horario_file'] !== null) {
            $pdfError = $this->validatePdf($data['horario_file']);
            if ($pdfError) {
                throw new \Exception($pdfError);
            }
        }

        // UUID para PDF si se subió
        $horarioLab = null;
        if (isset($data['horario_file']) && $data['horario_file'] !== null) {
            $ext = pathinfo($data['horario_file']['name'], PATHINFO_EXTENSION);
            $horarioLab = $this->generatePdfUuid($ext);
        }

        $sql = "
            INSERT INTO laboratorio (nombre_lab, resp_acad_id, horario_lab, activo)
            VALUES (?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nombre_lab'],
            (int)$data['resp_acad_id'],
            $horarioLab,
            (int)($data['activo'] ?? 1)
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Actualiza un laboratorio existente
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

        // Validar PDF si se subió
        if (isset($data['horario_file']) && $data['horario_file'] !== null) {
            $pdfError = $this->validatePdf($data['horario_file']);
            if ($pdfError) {
                throw new \Exception($pdfError);
            }
        }

        // UUID para PDF si se subió
        $horarioLab = $existing['horario_lab'];
        if (isset($data['horario_file']) && $data['horario_file'] !== null) {
            $ext = pathinfo($data['horario_file']['name'], PATHINFO_EXTENSION);
            $horarioLab = $this->generatePdfUuid($ext);
        }

        $sql = "
            UPDATE laboratorio 
            SET nombre_lab = ?, resp_acad_id = ?, horario_lab = ?, activo = ?
            WHERE id_lab = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nombre_lab'],
            (int)$data['resp_acad_id'],
            $horarioLab,
            (int)($data['activo'] ?? 1),
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
}