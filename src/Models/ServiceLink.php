<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class ServiceLink
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Obtiene todos los enlaces de servicio con sus ubicaciones (GROUP_CONCAT)
     */
    public function findAll(): array
    {
        // Hacemos un LEFT JOIN para asegurar que traiga el enlace incluso si no tiene ubicaciones
        // Agrupamos las ubicaciones separadas por coma en 'ubicaciones_str'
        $sql = "
            SELECT e.*, GROUP_CONCAT(ue.ubicacion SEPARATOR ',') as ubicaciones_str
            FROM enlace_servicio e
            LEFT JOIN ubicacion_enlace ue ON e.id_enl = ue.id_enl
            GROUP BY e.id_enl
            ORDER BY e.id_enl DESC
        ";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca un enlace de servicio por ID con sus ubicaciones como array
     */
    public function findById(int $id): ?array
    {
        $sql = "
            SELECT 
                e.id_enl, 
                e.nombre, 
                e.url, 
                e.icono, 
                e.estado, 
                e.creado_por
            FROM enlace_servicio e
            WHERE e.id_enl = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $link = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$link) {
            return null;
        }

        // Obtener ubicaciones como array
        $sqlUbicaciones = "SELECT ubicacion FROM ubicacion_enlace WHERE id_enl = ?";
        $stmtUbicaciones = $this->pdo->prepare($sqlUbicaciones);
        $stmtUbicaciones->execute([$id]);
        $ubicaciones = $stmtUbicaciones->fetchAll(PDO::FETCH_COLUMN);

        $link['ubicaciones'] = $ubicaciones ?: [];
        return $link;
    }

    /**
     * Crea un nuevo enlace de servicio con transacción
     * @param array $data Array con: nombre, url, icono (opcional), ubicaciones (array), estado, creado_por
     * @return int ID del registro insertado
     * @throws \Exception si validación falla
     */
    public function create(array $data): int
    {
        $required = ['nombre', 'url', 'creado_por'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        if (!filter_var($data['url'], FILTER_VALIDATE_URL)) {
            throw new \Exception("La URL no tiene un formato válido");
        }

        $icono = $data['icono'] ?? 'fas fa-link';
        $estado = $data['estado'] ?? 1;
        $ubicaciones = $data['ubicaciones'] ?? [];

        try {
            $this->pdo->beginTransaction();

            // Insertar en enlace_servicio (sin orden ni ubicacion)
            $sql = "
                INSERT INTO enlace_servicio (nombre, url, icono, estado, creado_por)
                VALUES (?, ?, ?, ?, ?)
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['nombre'],
                $data['url'],
                $icono,
                (int)$estado,
                (int)$data['creado_por']
            ]);

            $idEnl = (int)$this->pdo->lastInsertId();

            // Insertar ubicaciones en tabla relacional
            if (!empty($ubicaciones)) {
                $sqlUbicacion = "INSERT INTO ubicacion_enlace (id_enl, ubicacion) VALUES (?, ?)";
                $stmtUbicacion = $this->pdo->prepare($sqlUbicacion);
                foreach ($ubicaciones as $ubicacion) {
                    $ubicacion = trim($ubicacion);
                    if ($ubicacion !== '') {
                        $stmtUbicacion->execute([$idEnl, $ubicacion]);
                    }
                }
            }

            $this->pdo->commit();
            return $idEnl;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Actualiza un enlace de servicio existente con transacción
     * @param int $id ID del enlace
     * @param array $data Array con: nombre, url, icono (opcional), ubicaciones (array), estado
     * @return bool true si se actualizó, false si no
     * @throws \Exception si validación falla
     */
    public function update(int $id, array $data): bool
    {
        $existing = $this->findById($id);
        if (!$existing) {
            throw new \Exception("El enlace no existe");
        }

        $required = ['nombre', 'url'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        if (!filter_var($data['url'], FILTER_VALIDATE_URL)) {
            throw new \Exception("La URL no tiene un formato válido");
        }

        $icono = $data['icono'] ?? $existing['icono'];
        $estado = $data['estado'] ?? 1;
        $ubicaciones = $data['ubicaciones'] ?? [];

        try {
            $this->pdo->beginTransaction();

            // Actualizar enlace_servicio
            $sql = "
                UPDATE enlace_servicio 
                SET nombre = ?, url = ?, icono = ?, estado = ?
                WHERE id_enl = ?
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['nombre'],
                $data['url'],
                $icono,
                (int)$estado,
                $id
            ]);

            // Eliminar ubicaciones anteriores
            $this->pdo->prepare("DELETE FROM ubicacion_enlace WHERE id_enl = ?")->execute([$id]);

            // Insertar nuevas ubicaciones
            if (!empty($ubicaciones)) {
                $sqlUbicacion = "INSERT INTO ubicacion_enlace (id_enl, ubicacion) VALUES (?, ?)";
                $stmtUbicacion = $this->pdo->prepare($sqlUbicacion);
                foreach ($ubicaciones as $ubicacion) {
                    $ubicacion = trim($ubicacion);
                    if ($ubicacion !== '') {
                        $stmtUbicacion->execute([$id, $ubicacion]);
                    }
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
     * Elimina un enlace de servicio (DELETE físico)
     */
    public function delete(int $id): bool
    {
        $existing = $this->findById($id);
        if (!$existing) {
            return false;
        }

        try {
            $this->pdo->beginTransaction();

            // Eliminar ubicaciones primero (FK)
            $this->pdo->prepare("DELETE FROM ubicacion_enlace WHERE id_enl = ?")->execute([$id]);

            // Eliminar enlace
            $stmt = $this->pdo->prepare("DELETE FROM enlace_servicio WHERE id_enl = ?");
            $stmt->execute([$id]);

            $this->pdo->commit();
            return $stmt->rowCount() > 0;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Alias para findAll() - compatibilidad con SDD
     */
    public function all(): array
    {
        return $this->findAll();
    }
}