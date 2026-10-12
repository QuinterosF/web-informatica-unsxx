<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ServiceLink;

class ServiceLinkController extends Controller
{
    private ServiceLink $model;

    public function __construct()
    {
        $this->model = new ServiceLink();
    }

    /**
     * Formulario de creación
     * GET /directory/links/create
     */
    public function create(): void
    {
        $this->view('directory/link_create', [
            'title' => 'Nuevo Enlace de Servicio',
            'pageTitle' => 'Nuevo Enlace de Servicio'
        ]);
    }

    /**
     * Guarda un nuevo enlace de servicio
     * POST /directory/links
     */
    public function store(): void
    {
        // Capturar ubicaciones directamente como array (ya no implode)
        $ubicaciones = $_POST['ubicacion'] ?? [];

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'url' => trim($_POST['url'] ?? ''),
            'icono' => trim($_POST['icono'] ?? 'fas fa-link'),
            'ubicaciones' => $ubicaciones,
            'estado' => isset($_POST['estado']) ? 1 : 0,
            'creado_por' => (int)($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1),
        ];

        try {
            $id = $this->model->create($data);
            $_SESSION['success'] = "Enlace de servicio creado correctamente (ID: {$id})";
            header('Location: /directory');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->view('directory/link_create', [
                'title' => 'Nuevo Enlace de Servicio',
                'pageTitle' => 'Nuevo Enlace de Servicio',
                'old' => $data
            ]);
        }
    }

    /**
     * Formulario de edición
     * GET /directory/links/{id}/edit
     */
    public function edit(int $id): void
    {
        $link = $this->model->findById($id);

        if (!$link) {
            $_SESSION['error'] = 'Enlace no encontrado';
            header('Location: /directory');
            exit;
        }

        $this->view('directory/link_edit', [
            'link' => $link,
            'title' => 'Editar Enlace de Servicio',
            'pageTitle' => 'Editar Enlace de Servicio'
        ]);
    }

    /**
     * Actualiza un enlace de servicio
     * POST /directory/links/{id}/edit
     * POST /directory/links/{id}
     */
    public function update(int $id): void
    {
        // Capturar ubicaciones directamente como array (ya no implode)
        $ubicaciones = $_POST['ubicacion'] ?? [];

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'url' => trim($_POST['url'] ?? ''),
            'icono' => trim($_POST['icono'] ?? 'fas fa-link'),
            'ubicaciones' => $ubicaciones,
            'estado' => isset($_POST['estado']) ? 1 : 0,
        ];

        try {
            $this->model->update($id, $data);
            $_SESSION['success'] = 'Enlace de servicio actualizado correctamente';
            header('Location: /directory');
            exit;
        } catch (\Exception $e) {
            $link = $this->model->findById($id);
            $_SESSION['error'] = $e->getMessage();
            $this->view('directory/link_edit', [
                'link' => $link,
                'title' => 'Editar Enlace de Servicio',
                'pageTitle' => 'Editar Enlace de Servicio',
                'old' => $data
            ]);
        }
    }

    /**
     * Elimina un enlace de servicio
     * POST /directory/links/{id}/delete
     */
    public function destroy(int $id): void
    {
        try {
            $deleted = $this->model->delete($id);

            if ($deleted) {
                $_SESSION['success'] = 'Enlace de servicio eliminado correctamente';
            } else {
                $_SESSION['error'] = 'El enlace no existe';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al eliminar: ' . $e->getMessage();
        }

        header('Location: /directory');
        exit;
    }
}