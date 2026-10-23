<?php

namespace App\Core;

use App\Config\Database;

/**
 * Registro centralizado de eliminaciones para la auditoría del panel.
 */
class AuditLogger
{
    /**
     * Guarda una eliminación en la tabla de auditoría.
     *
     * No lanza excepciones: un fallo de auditoría no debe impedir el borrado.
     */
    public static function logDelete(string $modulo, ?int $registroId, string $titulo): void
    {
        try {
            $usuarioId = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? null;
            $titulo = trim($titulo);

            if ($titulo === '') {
                $titulo = $registroId !== null ? 'Registro #' . $registroId : 'Registro sin título';
            }

            $stmt = Database::getInstance()->prepare("
                INSERT INTO auditoria_eliminacion
                    (modulo, registro_id, titulo_registro, eliminado_por)
                VALUES
                    (:modulo, :registro_id, :titulo, :eliminado_por)
            ");

            $stmt->execute([
                'modulo' => mb_substr($modulo, 0, 60),
                'registro_id' => $registroId,
                'titulo' => mb_substr($titulo, 0, 300),
                'eliminado_por' => $usuarioId !== null ? (int) $usuarioId : null,
            ]);
        } catch (\Throwable $e) {
            error_log('No se pudo registrar la auditoría de eliminación: ' . $e->getMessage());
        }
    }
}
