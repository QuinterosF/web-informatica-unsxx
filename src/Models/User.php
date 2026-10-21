<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class User
{
    /**
     * Catálogo oficial de módulos del panel y sus etiquetas visibles.
     */
    public const MODULES = [
        'institucion'    => 'Identidad Institucional',
        'portadas'       => 'Portadas de Inicio',
        'redes'          => 'Directorio y Redes',
        'academics'      => 'Plantel Docente',
        'curricular'     => 'Menciones y Malla Curricular',
        'laboratories'   => 'Laboratorios',
        'admissions'     => 'Admisión',
        'communications' => 'Comunicados',
        'documents'      => 'Documentos Oficiales',
        'extensions'     => 'Extensión y Eventos',
        'logros'         => 'Logros de la Carrera',
    ];

    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Busca un usuario por correo (incluye estado y módulos para el login).
     */
    public function findByEmail(string $email): ?array
    {
        $sql = "
            SELECT u.usu_id, u.usu_nom, u.usu_correo, u.usu_pass, u.modulos, u.estado,
                   ur.rol_id, r.rol_nombre
            FROM usuario u
            LEFT JOIN usuario_rol ur ON u.usu_id = ur.usu_id
            LEFT JOIN rol r ON ur.rol_id = r.rol_id
            WHERE u.usu_correo = ?
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Lista todos los usuarios con su rol, académico vinculado y laboratorios.
     */
    public function findAll(): array
    {
        $sql = "
            SELECT u.usu_id, u.usu_nom, u.usu_correo, u.modulos, u.estado,
                   ur.rol_id, r.rol_nombre,
                   a.id_acad, a.nom_acad,
                   GROUP_CONCAT(DISTINCT l.nombre_lab ORDER BY l.nombre_lab SEPARATOR ', ') AS laboratorios
            FROM usuario u
            LEFT JOIN usuario_rol ur ON u.usu_id = ur.usu_id
            LEFT JOIN rol r ON ur.rol_id = r.rol_id
            LEFT JOIN academico a ON a.usu_id = u.usu_id
            LEFT JOIN laboratorio l ON l.resp_acad_id = a.id_acad
            GROUP BY u.usu_id, u.usu_nom, u.usu_correo, u.modulos, u.estado,
                     ur.rol_id, r.rol_nombre, a.id_acad, a.nom_acad
            ORDER BY u.usu_nom ASC
        ";
        $rows = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $row['modulos'] = $this->decodeModules($row['modulos'] ?? null);
            // Alias usado por las vistas de usuarios para mostrar los laboratorios a cargo.
            $row['labs_asignados'] = $row['laboratorios'] ?? null;
        }
        unset($row);

        return $rows;
    }

    /**
     * Busca un usuario por ID con su rol y académico vinculado.
     */
    public function findById(int $id): ?array
    {
        $sql = "
            SELECT u.usu_id, u.usu_nom, u.usu_correo, u.modulos, u.estado,
                   ur.rol_id, r.rol_nombre,
                   a.id_acad, a.nom_acad
            FROM usuario u
            LEFT JOIN usuario_rol ur ON u.usu_id = ur.usu_id
            LEFT JOIN rol r ON ur.rol_id = r.rol_id
            LEFT JOIN academico a ON a.usu_id = u.usu_id
            WHERE u.usu_id = ?
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $result['modulos'] = $this->decodeModules($result['modulos'] ?? null);
        }

        return $result ?: null;
    }

    /**
     * Devuelve el hash de la contraseña del usuario (uso interno).
     */
    public function getPasswordHash(int $id): ?string
    {
        $stmt = $this->pdo->prepare("SELECT usu_pass FROM usuario WHERE usu_id = ?");
        $stmt->execute([$id]);
        $hash = $stmt->fetchColumn();

        return $hash !== false ? (string) $hash : null;
    }

    /**
     * Obtiene los roles disponibles para el formulario.
     */
    public function getRoles(): array
    {
        return $this->pdo->query("SELECT rol_id, rol_nombre FROM rol ORDER BY rol_id")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Académicos libres o ya vinculados al usuario indicado.
     */
    public function getAvailableAcademics(?int $includeUserId = null): array
    {
        if ($includeUserId !== null) {
            $sql = "
                SELECT id_acad, nom_acad, email_acad
                FROM academico
                WHERE usu_id IS NULL OR usu_id = ?
                ORDER BY nom_acad ASC
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$includeUserId]);
        } else {
            $sql = "
                SELECT id_acad, nom_acad, email_acad
                FROM academico
                WHERE usu_id IS NULL
                ORDER BY nom_acad ASC
            ";
            $stmt = $this->pdo->query($sql);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Devuelve el ID del académico vinculado al usuario (o null).
     */
    public function getAcademicIdByUserId(int $userId): ?int
    {
        $stmt = $this->pdo->prepare("SELECT id_acad FROM academico WHERE usu_id = ? LIMIT 1");
        $stmt->execute([$userId]);
        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }

    /**
     * Devuelve los IDs de laboratorio a cargo del académico indicado.
     */
    public function getLabIdsByAcademicId(int $academicId): array
    {
        $stmt = $this->pdo->prepare("SELECT id_lab FROM laboratorio WHERE resp_acad_id = ? ORDER BY nombre_lab");
        $stmt->execute([$academicId]);

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    /**
     * Académicos que son responsables de al menos un laboratorio activo
     * y todavía no tienen una cuenta de usuario vinculada.
     */
    public function getPendingLabResponsibles(): array
    {
        $sql = "
            SELECT DISTINCT a.id_acad
            FROM laboratorio l
            INNER JOIN academico a ON a.id_acad = l.resp_acad_id
            WHERE l.activo = 1
              AND l.resp_acad_id IS NOT NULL
              AND a.usu_id IS NULL
              AND a.email_acad IS NOT NULL
              AND a.email_acad LIKE '%_@_%._%'
            ORDER BY a.id_acad
        ";
        $stmt = $this->pdo->query($sql);

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    /**
     * Garantiza que el académico responsable de un laboratorio tenga una cuenta.
     * Si ya tiene usuario, solo lo reactiva y devuelve null. Si no tiene,
     * crea la cuenta (o vincula una existente por correo) y devuelve las
     * credenciales temporales cuando la cuenta fue creada en este momento.
     *
     * @return array{name: string, email: string, password: string}|null
     * @throws \Exception si falla la transacción
     */
    public function ensureLabResponsibleUser(int $idAcad): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT id_acad, nom_acad, email_acad, usu_id
            FROM academico
            WHERE id_acad = ?
            LIMIT 1
        ");
        $stmt->execute([$idAcad]);
        $academic = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$academic) {
            return null;
        }

        $email = trim((string) ($academic['email_acad'] ?? ''));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $linkedUserId = !empty($academic['usu_id']) ? (int) $academic['usu_id'] : null;

        $this->pdo->beginTransaction();

        try {
            // Si ya está vinculado y el usuario existe, solo aseguramos su estado activo.
            if ($linkedUserId !== null) {
                $stmt = $this->pdo->prepare("SELECT usu_id FROM usuario WHERE usu_id = ? LIMIT 1");
                $stmt->execute([$linkedUserId]);
                $exists = $stmt->fetchColumn();

                if ($exists !== false) {
                    $this->pdo->prepare("UPDATE usuario SET estado = 1 WHERE usu_id = ?")
                        ->execute([$linkedUserId]);
                    $this->pdo->commit();
                    return null;
                }
            }

            // Buscar una cuenta existente por correo para evitar duplicados.
            $stmt = $this->pdo->prepare("SELECT usu_id FROM usuario WHERE usu_correo = ? LIMIT 1");
            $stmt->execute([$email]);
            $existingUserId = $stmt->fetchColumn();

            if ($existingUserId !== false) {
                $userId = (int) $existingUserId;
                $this->pdo->prepare("UPDATE usuario SET estado = 1 WHERE usu_id = ?")
                    ->execute([$userId]);

                if ($this->countRoles($userId) === 0) {
                    $this->assignRole($userId, 2);
                }

                $this->pdo->prepare("UPDATE academico SET usu_id = ? WHERE id_acad = ?")
                    ->execute([$userId, $idAcad]);

                $this->pdo->commit();
                return null;
            }

            $plainPassword = 'Infor' . date('Y') . '*';

            $stmt = $this->pdo->prepare("
                INSERT INTO usuario (usu_nom, usu_correo, usu_pass, modulos, estado)
                VALUES (?, ?, ?, NULL, 1)
            ");
            $stmt->execute([
                $academic['nom_acad'],
                $email,
                password_hash($plainPassword, PASSWORD_DEFAULT),
            ]);

            $userId = (int) $this->pdo->lastInsertId();
            $this->assignRole($userId, 2);

            $this->pdo->prepare("UPDATE academico SET usu_id = ? WHERE id_acad = ?")
                ->execute([$userId, $idAcad]);

            $this->pdo->commit();

            return [
                'name' => $academic['nom_acad'],
                'email' => $email,
                'password' => $plainPassword,
            ];
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Crea un usuario, asigna su rol y, opcionalmente, un académico.
     * @return int ID del usuario creado
     * @throws \Exception si la validación falla
     */
    public function create(array $data): int
    {
        $this->validateUserData($data, true);

        $password = (string) ($data['password'] ?? '');
        $modules = $this->normalizeModules($data['modulos'] ?? []);
        $academicId = !empty($data['id_acad']) ? (int) $data['id_acad'] : null;

        if ($academicId !== null && !$this->isAcademicAvailable($academicId, null)) {
            throw new \Exception("El académico seleccionado no está disponible");
        }

        $this->pdo->beginTransaction();

        try {
            $sql = "
                INSERT INTO usuario (usu_nom, usu_correo, usu_pass, modulos, estado)
                VALUES (?, ?, ?, ?, 1)
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['usu_nom'],
                $data['usu_correo'],
                password_hash($password, PASSWORD_DEFAULT),
                json_encode($modules, JSON_UNESCAPED_UNICODE),
            ]);
            $userId = (int) $this->pdo->lastInsertId();

            $this->assignRole($userId, (int) $data['rol_id']);

            if ($academicId !== null) {
                $stmt = $this->pdo->prepare("UPDATE academico SET usu_id = ? WHERE id_acad = ?");
                $stmt->execute([$userId, $academicId]);
            }

            $this->pdo->commit();
            return $userId;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Actualiza los datos del usuario, su rol, módulos y académico vinculado.
     * La contraseña solo se actualiza si se envía una nueva.
     * @throws \Exception si la validación falla
     */
    public function update(int $id, array $data): bool
    {
        $existing = $this->findById($id);
        if (!$existing) {
            throw new \Exception("El usuario no existe");
        }

        $this->validateUserData($data, false, $id);

        $modules = $this->normalizeModules($data['modulos'] ?? []);
        $academicId = !empty($data['id_acad']) ? (int) $data['id_acad'] : null;

        if ($academicId !== null && !$this->isAcademicAvailable($academicId, $id)) {
            throw new \Exception("El académico seleccionado no está disponible");
        }

        $this->pdo->beginTransaction();

        try {
            $sql = "
                UPDATE usuario
                SET usu_nom = ?, usu_correo = ?, modulos = ?
                WHERE usu_id = ?
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['usu_nom'],
                $data['usu_correo'],
                json_encode($modules, JSON_UNESCAPED_UNICODE),
                $id,
            ]);

            $password = (string) ($data['password'] ?? '');
            if ($password !== '') {
                $stmt = $this->pdo->prepare("UPDATE usuario SET usu_pass = ? WHERE usu_id = ?");
                $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
            }

            $this->assignRole($id, (int) $data['rol_id']);

            $this->pdo->prepare("UPDATE academico SET usu_id = NULL WHERE usu_id = ?")
                ->execute([$id]);

            if ($academicId !== null) {
                $stmt = $this->pdo->prepare("UPDATE academico SET usu_id = ? WHERE id_acad = ?");
                $stmt->execute([$id, $academicId]);
            }

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Activa o desactiva una cuenta. Evita que el administrador se desactive a sí mismo.
     * @throws \Exception si se intenta desactivar la cuenta propia
     */
    public function toggleStatus(int $id): bool
    {
        $currentUserId = (int) ($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 0);

        if ($id === $currentUserId) {
            throw new \Exception("No puedes desactivar tu propia cuenta");
        }

        $stmt = $this->pdo->prepare("UPDATE usuario SET estado = 1 - estado WHERE usu_id = ?");
        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Actualiza únicamente el hash de la contraseña.
     */
    public function updatePassword(int $id, string $newHash): bool
    {
        $stmt = $this->pdo->prepare("UPDATE usuario SET usu_pass = ? WHERE usu_id = ?");
        $stmt->execute([$newHash, $id]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Valida los campos comunes del formulario de usuario.
     * @throws \Exception si algún dato no es válido
     */
    private function validateUserData(array $data, bool $isCreate, ?int $excludeId = null): void
    {
        $required = ['usu_nom', 'usu_correo', 'rol_id'];
        if ($isCreate) {
            $required[] = 'password';
        }

        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("El campo {$field} es requerido");
            }
        }

        if (!filter_var($data['usu_correo'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("El correo electrónico no tiene un formato válido");
        }

        if ($this->emailExists($data['usu_correo'], $excludeId)) {
            throw new \Exception("El correo electrónico ya está registrado");
        }

        if (!$this->roleExists((int) $data['rol_id'])) {
            throw new \Exception("El rol seleccionado no existe");
        }

        $password = (string) ($data['password'] ?? '');
        if ($password !== '' && strlen($password) < 6) {
            throw new \Exception("La contraseña debe tener al menos 6 caracteres");
        }
    }

    /**
     * Verifica si el correo ya pertenece a otro usuario.
     */
    private function emailExists(string $email, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuario WHERE usu_correo = ? AND usu_id != ?");
            $stmt->execute([$email, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuario WHERE usu_correo = ?");
            $stmt->execute([$email]);
        }

        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Verifica si el rol existe.
     */
    private function roleExists(int $roleId): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM rol WHERE rol_id = ?");
        $stmt->execute([$roleId]);

        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Verifica que un académico esté libre o ya asignado al usuario indicado.
     */
    private function isAcademicAvailable(int $academicId, ?int $userId): bool
    {
        if ($userId !== null) {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM academico
                WHERE id_acad = ? AND (usu_id IS NULL OR usu_id = ?)
            ");
            $stmt->execute([$academicId, $userId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM academico WHERE id_acad = ? AND usu_id IS NULL");
            $stmt->execute([$academicId]);
        }

        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Reemplaza el rol asignado al usuario.
     */
    private function assignRole(int $userId, int $roleId): void
    {
        $this->pdo->prepare("DELETE FROM usuario_rol WHERE usu_id = ?")->execute([$userId]);

        $stmt = $this->pdo->prepare("INSERT INTO usuario_rol (usu_id, rol_id) VALUES (?, ?)");
        $stmt->execute([$userId, $roleId]);
    }

    /**
     * Cuenta los roles asignados a un usuario.
     */
    private function countRoles(int $userId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuario_rol WHERE usu_id = ?");
        $stmt->execute([$userId]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Decodifica el JSON de módulos almacenado en la base de datos.
     */
    private function decodeModules(?string $json): array
    {
        if ($json === null || $json === '') {
            return [];
        }

        $decoded = json_decode($json, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Filtra los módulos enviados contra el catálogo oficial.
     */
    private function normalizeModules(array $modules): array
    {
        $allowed = array_keys(self::MODULES);
        $modules = array_map('strval', $modules);

        return array_values(array_intersect($allowed, $modules));
    }
}
