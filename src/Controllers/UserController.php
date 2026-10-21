<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    private User $model;

    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * Listado de usuarios y sus permisos.
     */
    public function index(): void
    {
        $this->requireAdmin();

        $this->view('user/index', [
            'users' => $this->model->findAll(),
            'modules' => User::MODULES,
            'title' => 'Usuarios y Permisos - Informática UNSXX',
            'pageTitle' => 'Usuarios y Permisos',
        ]);
    }

    /**
     * Formulario de alta de usuario.
     */
    public function create(): void
    {
        $this->requireAdmin();

        $this->view('user/create', [
            'roles' => $this->model->getRoles(),
            'academics' => $this->model->getAvailableAcademics(),
            'modules' => User::MODULES,
            'old' => $_SESSION['old'] ?? [],
            'title' => 'Nuevo Usuario - Informática UNSXX',
            'pageTitle' => 'Nuevo Usuario',
        ]);

        unset($_SESSION['old']);
    }

    /**
     * Registra un nuevo usuario.
     */
    public function store(): void
    {
        $this->requireAdmin();

        try {
            $data = $this->collectData();
            $this->model->create($data);

            $plainPassword = (string) ($data['password'] ?? '');
            if ($plainPassword !== '') {
                $_SESSION['temp_credentials'] = [
                    'name' => $data['usu_nom'],
                    'email' => $data['usu_correo'],
                    'password' => $plainPassword,
                ];
            }

            $_SESSION['success'] = 'Usuario creado correctamente.';
            $this->redirect('/users');
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old'] = $_POST;
            $this->redirect('/users/create');
        }
    }

    /**
     * Formulario de edición de usuario.
     */
    public function edit(int $id): void
    {
        $this->requireAdmin();

        $user = $this->model->findById($id);
        if (!$user) {
            $_SESSION['error'] = 'El usuario no existe.';
            $this->redirect('/users');
        }

        $this->view('user/edit', [
            'user' => $user,
            'roles' => $this->model->getRoles(),
            'academics' => $this->model->getAvailableAcademics($id),
            'modules' => User::MODULES,
            'old' => $_SESSION['old'] ?? [],
            'title' => 'Editar Usuario - Informática UNSXX',
            'pageTitle' => 'Editar Usuario',
        ]);

        unset($_SESSION['old']);
    }

    /**
     * Actualiza los datos, rol, módulos y académico del usuario.
     */
    public function update(int $id): void
    {
        $this->requireAdmin();

        try {
            $data = $this->collectData();
            $this->model->update($id, $data);

            // Solo mostrar credenciales si el administrador definió una nueva clave.
            $plainPassword = (string) ($data['password'] ?? '');
            if ($plainPassword !== '') {
                $_SESSION['temp_credentials'] = [
                    'name' => $data['usu_nom'],
                    'email' => $data['usu_correo'],
                    'password' => $plainPassword,
                ];
            }

            $_SESSION['success'] = 'Usuario actualizado correctamente.';
            $this->redirect('/users');
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old'] = $_POST;
            $this->redirect('/users/' . $id . '/edit');
        }
    }

    /**
     * Activa o desactiva una cuenta.
     */
    public function toggle(int $id): void
    {
        $this->requireAdmin();

        try {
            if ($this->model->toggleStatus($id)) {
                $_SESSION['success'] = 'Estado del usuario actualizado correctamente.';
            } else {
                $_SESSION['error'] = 'El usuario no existe.';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        $this->redirect('/users');
    }

    /**
     * Crea automáticamente las cuentas de los responsables de laboratorio
     * activos que todavía no tienen usuario vinculado.
     */
    public function syncLabs(): void
    {
        $this->requireAdmin();

        try {
            $pendingIds = $this->model->getPendingLabResponsibles();
            $created = 0;
            $linked = 0;
            $createdCredentials = [];

            foreach ($pendingIds as $idAcad) {
                $credentials = $this->model->ensureLabResponsibleUser($idAcad);

                if ($credentials !== null) {
                    $created++;
                    $createdCredentials[] = $credentials;
                } else {
                    $linked++;
                }
            }

            if (!empty($createdCredentials)) {
                $_SESSION['temp_credentials'] = $createdCredentials;
            }

            if ($created > 0) {
                $_SESSION['success'] = "Sincronización completada: {$created} cuenta(s) creada(s)"
                    . ($linked > 0 ? " y {$linked} ya existente(s)." : '.');
            } elseif ($linked > 0) {
                $_SESSION['success'] = 'Todos los responsables de laboratorio ya tienen una cuenta vinculada.';
            } else {
                $_SESSION['success'] = 'No hay responsables de laboratorio pendientes de cuenta.';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al sincronizar responsables: ' . $e->getMessage();
        }

        $this->redirect('/users');
    }

    /**
     * Restablece la contraseña de un usuario con una clave temporal.
     */
    public function resetPassword(int $id): void
    {
        $this->requireAdmin();

        $user = $this->model->findById($id);
        if (!$user) {
            $_SESSION['error'] = 'El usuario no existe.';
            $this->redirect('/users');
        }

        $plainPassword = 'Infor-' . random_int(1000, 9999) . '*';
        $this->model->updatePassword($id, password_hash($plainPassword, PASSWORD_DEFAULT));

        $_SESSION['temp_credentials'] = [
            'name' => $user['usu_nom'],
            'email' => $user['usu_correo'],
            'password' => $plainPassword,
        ];
        $_SESSION['success'] = 'Contraseña restablecida correctamente. Comparte la clave temporal con el usuario.';

        $this->redirect('/users');
    }

    /**
     * Formulario para que cualquier usuario cambie su contraseña.
     */
    public function passwordForm(): void
    {
        $userId = $this->currentUserId();
        $user = $userId > 0 ? $this->model->findById($userId) : null;

        if (!$user) {
            $this->redirect('/login');
        }

        $this->view('user/password', [
            'user' => $user,
            'title' => 'Cambiar Contraseña - Informática UNSXX',
            'pageTitle' => 'Cambiar Contraseña',
        ]);
    }

    /**
     * Verifica la contraseña actual y guarda la nueva.
     */
    public function updatePassword(): void
    {
        $userId = $this->currentUserId();
        $hash = $userId > 0 ? $this->model->getPasswordHash($userId) : null;

        if ($hash === null) {
            $this->redirect('/login');
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            $_SESSION['error'] = 'Complete todos los campos.';
            $this->redirect('/password');
        }

        if (!password_verify($currentPassword, $hash)) {
            $_SESSION['error'] = 'La contraseña actual no es correcta.';
            $this->redirect('/password');
        }

        if (strlen($newPassword) < 6) {
            $_SESSION['error'] = 'La nueva contraseña debe tener al menos 6 caracteres.';
            $this->redirect('/password');
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'Las contraseñas nuevas no coinciden.';
            $this->redirect('/password');
        }

        $this->model->updatePassword($userId, password_hash($newPassword, PASSWORD_DEFAULT));
        $_SESSION['success'] = 'Contraseña actualizada correctamente.';
        $this->redirect('/password');
    }

    /**
     * Recoge y normaliza los datos del formulario de usuario.
     */
    private function collectData(): array
    {
        $data = [
            'usu_nom' => trim($_POST['usu_nom'] ?? ''),
            'usu_correo' => trim($_POST['usu_correo'] ?? ''),
            'rol_id' => (int) ($_POST['rol_id'] ?? 0),
            'modulos' => isset($_POST['modulos']) && is_array($_POST['modulos']) ? $_POST['modulos'] : [],
            'id_acad' => !empty($_POST['id_acad']) ? (int) $_POST['id_acad'] : null,
            // Las vistas usan "usu_pass"; se acepta "password" por compatibilidad.
            'password' => (string) ($_POST['usu_pass'] ?? $_POST['password'] ?? ''),
        ];

        return $data;
    }

    /**
     * ID del usuario autenticado.
     */
    private function currentUserId(): int
    {
        return (int) ($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 0);
    }

    /**
     * Restringe el acceso al módulo de usuarios a los administradores.
     */
    private function requireAdmin(): void
    {
        if (!Auth::isAdmin()) {
            $_SESSION['error'] = 'No tienes permisos para gestionar usuarios.';
            $this->redirect('/dashboard');
        }
    }
}
