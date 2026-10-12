<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class InstitutionalInfo
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Obtiene la información institucional (registro único con id = 1)
     */
    public function get(): array
    {
        $sql = "SELECT * FROM info_institucional WHERE id = 1";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            // Retornar estructura vacía si no existe el registro
            return [
                'id' => 1,
                'presentacion' => '',
                'perfil_general' => '',
                'mision' => '',
                'vision' => '',
                'objetivos' => '',
                'resena_historica' => '',
                'mensaje_director' => '',
                'foto_director' => null,
                'actualizado_por' => null,
                'actualizado_en' => null
            ];
        }

        return $result;
    }

    /**
     * Actualiza o inserta la información institucional (id = 1)
     * @param array $data Array con: presentacion, perfil_general, mision, vision, objetivos, resena_historica, mensaje_director, foto_director, actualizado_por
     * @return bool
     * @throws \Exception si falla la BD
     */
    public function update(array $data): bool
    {
        $existing = $this->get();

        $fields = [
            'presentacion',
            'perfil_general',
            'mision',
            'vision',
            'objetivos',
            'resena_historica',
            'mensaje_director',
            'foto_director',
            'actualizado_por'
        ];

        // Sanitizar textos
        foreach ($fields as $field) {
            if ($field !== 'actualizado_por' && $field !== 'foto_director') {
                $data[$field] = trim($data[$field] ?? '');
            }
        }

        // Manejar foto_director
        $fotoDirector = $data['foto_director'] ?? ($existing['foto_director'] ?? null);
        $data['foto_director'] = $fotoDirector;
        $data['actualizado_por'] = (int)($data['actualizado_por'] ?? 1);

        if ($existing && $existing['id'] === 1) {
            // UPDATE
            $sql = "
                UPDATE info_institucional 
                SET presentacion = ?, perfil_general = ?, mision = ?, vision = ?, 
                    objetivos = ?, resena_historica = ?, mensaje_director = ?, 
                    foto_director = ?, actualizado_por = ?
                WHERE id = 1
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['presentacion'],
                $data['perfil_general'],
                $data['mision'],
                $data['vision'],
                $data['objetivos'],
                $data['resena_historica'],
                $data['mensaje_director'],
                $data['foto_director'],
                $data['actualizado_por']
            ]);
        } else {
            // INSERT con id = 1
            $sql = "
                INSERT INTO info_institucional (id, presentacion, perfil_general, mision, vision, 
                    objetivos, resena_historica, mensaje_director, foto_director, actualizado_por)
                VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['presentacion'],
                $data['perfil_general'],
                $data['mision'],
                $data['vision'],
                $data['objetivos'],
                $data['resena_historica'],
                $data['mensaje_director'],
                $data['foto_director'],
                $data['actualizado_por']
            ]);
        }

        return $stmt->rowCount() > 0 || $this->pdo->lastInsertId() === 1;
    }
}