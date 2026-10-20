<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Portada
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Obtiene todas las portadas ordenadas por orden ASC, id_portada DESC
     */
    public function getAll(): array
    {
        $sql = "
            SELECT * FROM portada_inicio 
            ORDER BY orden ASC, id_portada DESC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca una portada por ID
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM portada_inicio WHERE id_portada = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Inserta una nueva foto en la portada
     * @param string $archivo_url Nombre del archivo subido
     * @return bool true si se insertó, false si no
     */
    public function addPhoto(string $archivo_url): bool
    {
        $sql = "
            INSERT INTO portada_inicio (archivo_url)
            VALUES (?)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$archivo_url]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Elimina una portada
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM portada_inicio WHERE id_portada = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Actualiza el título (opcional) y el orden de una portada
     * @param int $id ID de la portada
     * @param string|null $titulo Título (vacío -> NULL)
     * @param int $orden Orden numérico
     * @return bool true si se actualizó
     */
    public function updateInfo(int $id, ?string $titulo, int $orden): bool
    {
        if ($titulo === '' || $titulo === null) {
            $titulo = null;
        }

        $sql = "UPDATE portada_inicio SET titulo = :titulo, orden = :orden WHERE id_portada = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':titulo' => $titulo,
            ':orden' => $orden,
            ':id' => $id
        ]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Alterna el estado activo de una portada
     */
    public function toggleActive(int $id): void
    {
        $sql = "UPDATE portada_inicio SET activo = 1 - activo WHERE id_portada = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
    }
}