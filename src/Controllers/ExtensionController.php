<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Extension;

class ExtensionController extends Controller
{
    private Extension $model;

    public function __construct()
    {
        $this->model = new Extension();
    }

    /**
     * Listado de extensiones
     * GET /extensions
     */
    public function index(): void
    {
        $extensions = $this->model->all();
        $this->view('extension/index', [
            'extensions' => $extensions,
            'title' => 'Extensión - Ingeniería Informática UNSXX',
            'pageTitle' => 'Extensión Universitaria'
        ]);
    }

    /**
     * Formulario de creación
     * GET /extensions/create
     */
    public function create(): void
    {
        $this->view('extension/create', [
            'title' => 'Nueva Extensión',
            'pageTitle' => 'Nueva Actividad de Extensión'
        ]);
    }

    /**
     * Guarda una nueva extensión
     * POST /extensions
     */
    public function store(): void
    {
        $data = [
            'nom_ext' => trim($_POST['nom_ext'] ?? ''),
            'fech_ext' => trim($_POST['fech_ext'] ?? ''),
            'desc_ext' => trim($_POST['desc_ext'] ?? ''),
            'creado_por' => (int)($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1),
        ];

        try {
            $id = $this->model->create($data);
            $_SESSION['success'] = "Extensión creada correctamente (ID: {$id})";
            header('Location: /extensions');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->view('extension/create', [
                'title' => 'Nueva Extensión',
                'pageTitle' => 'Nueva Actividad de Extensión',
                'old' => $data
            ]);
        }
    }

    /**
     * Formulario de edición
     * GET /extensions/{id}/edit
     */
    public function edit(int $id): void
    {
        $extension = $this->model->findById($id);

        if (!$extension) {
            $_SESSION['error'] = 'Extensión no encontrada';
            header('Location: /extensions');
            exit;
        }

        $this->view('extension/edit', [
            'extension' => $extension,
            'title' => 'Editar Extensión',
            'pageTitle' => 'Editar Actividad de Extensión'
        ]);
    }

    /**
     * Actualiza una extensión
     * POST /extensions/{id}/edit
     */
    public function update(int $id): void
    {
        $data = [
            'nom_ext' => trim($_POST['nom_ext'] ?? ''),
            'fech_ext' => trim($_POST['fech_ext'] ?? ''),
            'desc_ext' => trim($_POST['desc_ext'] ?? ''),
        ];

        try {
            $this->model->update($id, $data);
            $_SESSION['success'] = 'Extensión actualizada correctamente';
            header('Location: /extensions');
            exit;
        } catch (\Exception $e) {
            $extension = $this->model->findById($id);
            $_SESSION['error'] = $e->getMessage();
            $this->view('extension/edit', [
                'extension' => $extension,
                'title' => 'Editar Extensión',
                'pageTitle' => 'Editar Actividad de Extensión',
                'old' => $data
            ]);
        }
    }

    /**
     * Elimina una extensión
     * POST /extensions/{id}/delete
     */
    public function destroy(int $id): void
    {
        try {
            $deleted = $this->model->delete($id);

            if ($deleted) {
                $_SESSION['success'] = 'Extensión eliminada correctamente';
            } else {
                $_SESSION['error'] = 'La extensión no existe';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al eliminar: ' . $e->getMessage();
        }

        header('Location: /extensions');
        exit;
    }
}