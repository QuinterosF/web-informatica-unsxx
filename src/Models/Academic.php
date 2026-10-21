<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Academic
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Genera un UUID para la foto (placeholder hasta implementar subida real)
     */
    private function generatePhotoUuid(string $extension = 'jpg'): string
    {
        return bin2hex(random_bytes(16)) . '.' . strtolower($extension);
    }

    /**
     * Obtiene todas las categorías académicas para el select del formulario
     */
    public function getCategories(): array
    {
        $stmt = $this->pdo->query("SELECT cat_id, cat_nombre FROM categoria_academico ORDER BY cat_nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene académicos que son coordinadores (categoría contiene 'Coordinador')
     * Para usar en selects de menciones/institutos
     */
    public function getCoordinators(): array
    {
        $sql = "
            SELECT a.id_acad, a.nom_acad 
            FROM academico a
            JOIN categoria_academico c ON a.cat_acad = c.cat_id
            WHERE c.cat_nombre IN ('Coordinador de Mención', 'Coordinador del IIDAI')
            ORDER BY a.nom_acad ASC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lista todos los académicos con su categoría (JOIN)
     * Alias: all()
     */
    public function findAll(): array
    {
        // En findAll NO debe haber ningún "WHERE a.id_acad = ?"
        $sql = "
            SELECT a.id_acad, a.nom_acad, a.sitio_web, a.foto_acad, a.cel_acad, a.email_acad, 
            a.cat_acad, c.cat_nombre, a.desc_acad, a.mostrar_email, a.mostrar_cel,
            a.activo, a.creado_por, a.actualizado_por
            FROM academico a
            JOIN categoria_academico c ON a.cat_acad = c.cat_id
            ORDER BY a.nom_acad ASC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca un académico por ID con su categoría (JOIN)
     */
    public function findById(int $id): ?array
    {
        // findById SÍ requiere el "WHERE a.id_acad = ?"
        $sql = "
            SELECT a.id_acad, a.nom_acad, a.sitio_web, a.foto_acad, a.cel_acad, a.email_acad, 
            a.cat_acad, c.cat_nombre, a.desc_acad, a.mostrar_email, a.mostrar_cel,
            a.activo, a.creado_por, a.actualizado_por
            FROM academico a
            JOIN categoria_academico c ON a.cat_acad = c.cat_id
            WHERE a.id_acad = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Verifica si un email ya existe (opcionalmente excluyendo un ID)
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $sql = "SELECT COUNT(*) FROM academico WHERE email_acad = ? AND id_acad != ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$email, $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) FROM academico WHERE email_acad = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$email]);
        }
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Valida que la categoría exista
     */
    private function categoryExists(int $catId): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM categoria_academico WHERE cat_id = ?");
        $stmt->execute([$catId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Crea un nuevo académico
     * @param array $data Array con: nom_acad, cel_acad, email_acad, cat_acad, desc_acad, foto_acad (opcional), mostrar_email, mostrar_cel, sitio_web (opcional)
     * @return int ID del registro insertado
     * @throws \Exception si validación falla
     */
    public function create(array $data): int
    {
        // Validaciones
        $required = ['nom_acad', 'cel_acad', 'email_acad', 'cat_acad', 'desc_acad'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        if (!filter_var($data['email_acad'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("El email no tiene un formato válido");
        }

        if ($this->emailExists($data['email_acad'])) {
            throw new \Exception("El email ya está registrado");
        }

        if (!$this->categoryExists((int) $data['cat_acad'])) {
            throw new \Exception("La categoría seleccionada no existe");
        }

        // UUID para foto (placeholder)
        $foto = $data['foto_acad'] ?? 'default.png';

        // sitio_web opcional, guardar NULL si vacío
        $sitioWeb = !empty($data['sitio_web']) ? $data['sitio_web'] : null;
        $creadoPor = (int) ($data['creado_por'] ?? $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);

        $sql = "
            INSERT INTO academico (nom_acad, foto_acad, cel_acad, email_acad, cat_acad, desc_acad, sitio_web, mostrar_email, mostrar_cel, usu_id, activo, creado_por)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nom_acad'],
            $foto,
            $data['cel_acad'],
            $data['email_acad'],
            (int) $data['cat_acad'],
            $data['desc_acad'],
            $sitioWeb,
            (int) ($data['mostrar_email'] ?? 0),
            (int) ($data['mostrar_cel'] ?? 0),
            (int) ($data['activo'] ?? 1),
            $creadoPor
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza un académico existente
     * @param int $id ID del académico
     * @param array $data Array con: nom_acad, cel_acad, email_acad, cat_acad, desc_acad, foto_acad (opcional), mostrar_email, mostrar_cel, sitio_web (opcional)
     * @return bool true si se actualizó, false si no
     * @throws \Exception si validación falla
     */
    public function update(int $id, array $data): bool
    {
        // Verificar existencia
        $existing = $this->findById($id);
        if (!$existing) {
            throw new \Exception("El académico no existe");
        }

        // Validaciones
        $required = ['nom_acad', 'cel_acad', 'email_acad', 'cat_acad', 'desc_acad'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        if (!filter_var($data['email_acad'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("El email no tiene un formato válido");
        }

        if ($this->emailExists($data['email_acad'], $id)) {
            throw new \Exception("El email ya está registrado por otro académico");
        }

        if (!$this->categoryExists((int) $data['cat_acad'])) {
            throw new \Exception("La categoría seleccionada no existe");
        }

        // Mantener foto actual si no se proporciona nueva
        $foto = $data['foto_acad'] ?? $existing['foto_acad'];

        // sitio_web opcional, guardar NULL si vacío
        $sitioWeb = array_key_exists('sitio_web', $data) ? ($data['sitio_web'] ?? null) : $existing['sitio_web'] ?? null;
        $actualizadoPor = (int) ($data['actualizado_por'] ?? $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);

        $sql = "
            UPDATE academico 
            SET nom_acad = ?, foto_acad = ?, cel_acad = ?, email_acad = ?, cat_acad = ?, desc_acad = ?, sitio_web = ?, mostrar_email = ?, mostrar_cel = ?, actualizado_por = ?
            WHERE id_acad = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['nom_acad'],
            $foto,
            $data['cel_acad'],
            $data['email_acad'],
            (int) $data['cat_acad'],
            $data['desc_acad'],
            $sitioWeb,
            (int) ($data['mostrar_email'] ?? 0),
            (int) ($data['mostrar_cel'] ?? 0),
            $actualizadoPor,
            $id
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Alterna la visibilidad pública del académico (activo/inactivo)
     */
    public function toggleActive(int $id): bool
    {
        $actualizadoPor = (int) ($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);

        $stmt = $this->pdo->prepare("
            UPDATE academico
            SET activo = 1 - activo, actualizado_por = ?
            WHERE id_acad = ?
        ");
        $stmt->execute([$actualizadoPor, $id]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Elimina un académico verificando existencia previa
     */
    public function delete(int $id): bool
    {
        $existing = $this->findById($id);
        if (!$existing) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM academico WHERE id_acad = ?");
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