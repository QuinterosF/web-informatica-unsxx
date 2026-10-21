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

    /* ==================== EVENTOS ==================== */

    /**
     * Lista todas las actividades de extensión con su categoría y portada
     */
    public function findAll(): array
    {
        $sql = "
            SELECT e.*, c.nom_cat, c.color_badge,
                   (SELECT COUNT(*) FROM foto_extension f WHERE f.id_ext = e.id_ext) AS total_fotos,
                   (SELECT f2.nom_foto FROM foto_extension f2 WHERE f2.id_ext = e.id_ext AND f2.es_portada = 1 LIMIT 1) AS foto_portada
            FROM extension e
            LEFT JOIN categoria_extension c ON e.id_cat_ext = c.id_cat_ext
            ORDER BY e.destacado DESC, e.fech_ext DESC, e.id_ext DESC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Alias de findAll()
     */
    public function getAll(): array
    {
        return $this->findAll();
    }

    /**
     * Busca una actividad por ID con su categoría
     */
    public function findById(int $id): ?array
    {
        $sql = "
            SELECT e.*, c.nom_cat, c.color_badge
            FROM extension e
            LEFT JOIN categoria_extension c ON e.id_cat_ext = c.id_cat_ext
            WHERE e.id_ext = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Crea una nueva actividad de extensión
     * @param array $data Campos: id_cat_ext, nom_ext, fech_ext, lugar_ext, desc_ext, enlace_externo, destacado, activo, creado_por
     * @return int ID del registro insertado
     * @throws \Exception si la validación falla
     */
    public function create(array $data): int
    {
        $this->validateEventData($data);

        $slug = $this->uniqueEventSlug($this->generateSlug($data['nom_ext']));

        $idCat = !empty($data['id_cat_ext']) ? (int) $data['id_cat_ext'] : null;
        $creadoPor = (int) ($data['creado_por'] ?? $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);

        $sql = "
            INSERT INTO extension (id_cat_ext, nom_ext, slug_ext, fech_ext, lugar_ext, desc_ext, enlace_externo, destacado, activo, creado_por)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $idCat,
            $data['nom_ext'],
            $slug,
            $data['fech_ext'],
            !empty($data['lugar_ext']) ? $data['lugar_ext'] : null,
            $data['desc_ext'],
            !empty($data['enlace_externo']) ? $data['enlace_externo'] : null,
            !empty($data['destacado']) ? 1 : 0,
            !empty($data['activo']) ? 1 : 0,
            $creadoPor
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza una actividad de extensión existente
     */
    public function update(int $id, array $data): bool
    {
        $existing = $this->findById($id);
        if (!$existing) {
            throw new \Exception("La actividad de extensión no existe");
        }

        $this->validateEventData($data);

        // Regenerar slug solo si cambió el nombre
        $slug = $existing['slug_ext'];
        if ($data['nom_ext'] !== $existing['nom_ext']) {
            $slug = $this->uniqueEventSlug($this->generateSlug($data['nom_ext']), $id);
        }

        $idCat = !empty($data['id_cat_ext']) ? (int) $data['id_cat_ext'] : null;

        $sql = "
            UPDATE extension
            SET id_cat_ext = ?, nom_ext = ?, slug_ext = ?, fech_ext = ?, lugar_ext = ?, desc_ext = ?, enlace_externo = ?, destacado = ?, activo = ?, actualizado_por = ?
            WHERE id_ext = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $idCat,
            $data['nom_ext'],
            $slug,
            $data['fech_ext'],
            !empty($data['lugar_ext']) ? $data['lugar_ext'] : null,
            $data['desc_ext'],
            !empty($data['enlace_externo']) ? $data['enlace_externo'] : null,
            !empty($data['destacado']) ? 1 : 0,
            !empty($data['activo']) ? 1 : 0,
            (int) ($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1),
            $id
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Elimina una actividad de extensión (las fotos se borran en cascada)
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM extension WHERE id_ext = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Obtiene la galería de fotos de una actividad
     */
    public function getGallery(int $id_ext): array
    {
        $sql = "SELECT * FROM foto_extension WHERE id_ext = :id_ext ORDER BY es_portada DESC, id_foto DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_ext' => $id_ext]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==================== CATEGORÍAS DINÁMICAS ==================== */

    /**
     * Lista las categorías de extensión
     * @param bool $onlyActive Si true, solo devuelve categorías activas
     */
    public function getCategories(bool $onlyActive = false): array
    {
        $sql = "SELECT * FROM categoria_extension";
        if ($onlyActive) {
            $sql .= " WHERE activo = 1";
        }
        $sql .= " ORDER BY nom_cat ASC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crea una nueva categoría
     */
    public function createCategory(string $nom_cat, string $color_badge): int
    {
        $nom_cat = trim($nom_cat);
        $color_badge = trim($color_badge);

        if ($nom_cat === '') {
            throw new \Exception("El nombre de la categoría es requerido");
        }

        if ($color_badge === '') {
            $color_badge = '#6b7280';
        }

        $slug = $this->uniqueCategorySlug($this->generateSlug($nom_cat));

        $sql = "INSERT INTO categoria_extension (nom_cat, slug_cat, color_badge, activo) VALUES (?, ?, ?, 1)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nom_cat, $slug, $color_badge]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza el nombre, slug y color de una categoría existente
     */
    public function updateCategory(int $id_cat_ext, string $nom_cat, string $color_badge): bool
    {
        $nom_cat = trim($nom_cat);
        $color_badge = trim($color_badge);

        if ($nom_cat === '') {
            throw new \Exception("El nombre de la categoría es requerido");
        }

        if ($color_badge === '') {
            $color_badge = '#6b7280';
        }

        $slug = $this->uniqueCategorySlug($this->generateSlug($nom_cat), $id_cat_ext);

        $sql = "
            UPDATE categoria_extension
            SET nom_cat = ?, slug_cat = ?, color_badge = ?
            WHERE id_cat_ext = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nom_cat, $slug, $color_badge, $id_cat_ext]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Alterna el estado activo/inactivo de una categoría
     */
    public function toggleCategory(int $id_cat_ext): void
    {
        $sql = "UPDATE categoria_extension SET activo = 1 - activo WHERE id_cat_ext = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id_cat_ext]);
    }

    /**
     * Elimina una categoría. Las actividades asociadas quedan sin categoría.
     */
    public function deleteCategory(int $id_cat_ext): bool
    {
        $this->pdo->beginTransaction();
        try {
            $this->pdo->prepare("UPDATE extension SET id_cat_ext = NULL WHERE id_cat_ext = ?")
                ->execute([$id_cat_ext]);

            $stmt = $this->pdo->prepare("DELETE FROM categoria_extension WHERE id_cat_ext = ?");
            $stmt->execute([$id_cat_ext]);
            $deleted = $stmt->rowCount() > 0;

            $this->pdo->commit();
            return $deleted;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /* ==================== GALERÍA DE FOTOS ==================== */

    /**
     * Inserta una foto en la galería del evento.
     * La primera foto del evento se marca automáticamente como portada.
     */
    public function addPhoto(int $id_ext, string $nom_foto, int $subido_por): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM foto_extension WHERE id_ext = ?");
        $stmt->execute([$id_ext]);
        $esPortada = ((int) $stmt->fetchColumn() === 0) ? 1 : 0;

        $sql = "
            INSERT INTO foto_extension (nom_foto, es_portada, id_ext, subido_por)
            VALUES (?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nom_foto, $esPortada, $id_ext, $subido_por]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Busca una foto por ID
     */
    public function findPhotoById(int $id_foto): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM foto_extension WHERE id_foto = ?");
        $stmt->execute([$id_foto]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Elimina una foto. Si era la portada, promueve la foto más reciente restante.
     */
    public function deletePhoto(int $id_foto): bool
    {
        $photo = $this->findPhotoById($id_foto);
        if (!$photo) {
            return false;
        }

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("DELETE FROM foto_extension WHERE id_foto = ?");
            $stmt->execute([$id_foto]);

            if ((int) $photo['es_portada'] === 1) {
                $stmt = $this->pdo->prepare("
                    SELECT id_foto FROM foto_extension
                    WHERE id_ext = ?
                    ORDER BY id_foto DESC
                    LIMIT 1
                ");
                $stmt->execute([(int) $photo['id_ext']]);
                $nextId = $stmt->fetchColumn();

                if ($nextId !== false) {
                    $this->pdo->prepare("UPDATE foto_extension SET es_portada = 1 WHERE id_foto = ?")
                        ->execute([(int) $nextId]);
                }
            }

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Marca una foto como portada exclusiva de su evento
     */
    public function setCoverPhoto(int $id_ext, int $id_foto): void
    {
        $this->pdo->beginTransaction();
        try {
            $this->pdo->prepare("UPDATE foto_extension SET es_portada = 0 WHERE id_ext = ?")
                ->execute([$id_ext]);

            $this->pdo->prepare("UPDATE foto_extension SET es_portada = 1 WHERE id_foto = ? AND id_ext = ?")
                ->execute([$id_foto, $id_ext]);

            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /* ==================== AUXILIARES ==================== */

    /**
     * Valida los campos obligatorios de una actividad
     */
    private function validateEventData(array $data): void
    {
        foreach (['nom_ext', 'fech_ext', 'desc_ext'] as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fech_ext'])) {
            throw new \Exception("La fecha debe tener formato YYYY-MM-DD");
        }
    }

    /**
     * Genera un slug ASCII a partir de un texto (acentos/ñ → ASCII, minúsculas, guiones)
     */
    private function generateSlug(string $text): string
    {
        $map = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ñ' => 'N',
        ];
        $text = strtr($text, $map);
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');

        return $text !== '' ? $text : 'evento';
    }

    /**
     * Garantiza un slug_ext único
     */
    private function uniqueEventSlug(string $base, ?int $excludeId = null): string
    {
        $slug = $base;

        while (true) {
            if ($excludeId !== null) {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM extension WHERE slug_ext = ? AND id_ext != ?");
                $stmt->execute([$slug, $excludeId]);
            } else {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM extension WHERE slug_ext = ?");
                $stmt->execute([$slug]);
            }

            if ((int) $stmt->fetchColumn() === 0) {
                return $slug;
            }

            $slug = $base . '-' . uniqid();
        }
    }

    /**
     * Garantiza un slug_cat único
     */
    private function uniqueCategorySlug(string $base, ?int $excludeId = null): string
    {
        $slug = $base;

        while (true) {
            if ($excludeId !== null) {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM categoria_extension WHERE slug_cat = ? AND id_cat_ext != ?");
                $stmt->execute([$slug, $excludeId]);
            } else {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM categoria_extension WHERE slug_cat = ?");
                $stmt->execute([$slug]);
            }

            if ((int) $stmt->fetchColumn() === 0) {
                return $slug;
            }

            $slug = $base . '-' . uniqid();
        }
    }
}