<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    private Announcement $model;

    public function __construct()
    {
        $this->model = new Announcement();
    }

    /**
     * Listado de comunicados
     * GET /announcements
     */
    public function index(): void
    {
        $announcements = $this->model->all();
        $this->view('announcement/index', [
            'announcements' => $announcements,
            'title' => 'Comunicados - Ingeniería Informática UNSXX',
            'pageTitle' => 'Comunicados'
        ]);
    }

    /**
     * Formulario de creación
     * GET /announcements/create
     */
    public function create(): void
    {
        $this->view('announcement/create', [
            'title' => 'Nuevo Comunicado',
            'pageTitle' => 'Nuevo Comunicado'
        ]);
    }

    /**
     * Guarda un nuevo comunicado
     * POST /announcements
     */
    public function store(): void
    {
        $data = [
            'nom_com' => trim($_POST['nom_com'] ?? ''),
            'fech_com' => trim($_POST['fech_com'] ?? ''),
            'desc_com' => trim($_POST['desc_com'] ?? ''),
            'publicado_por' => (int)($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1),
            'estado' => isset($_POST['estado']) ? 1 : 0,
        ];

        // Manejar doc_com y foto_com (solo guardar nombre del archivo si viene en $_FILES)
        if (isset($_FILES['doc_com']) && $_FILES['doc_com']['error'] === UPLOAD_ERR_OK) {
            $data['doc_com_file'] = $_FILES['doc_com'];
        }
        if (isset($_FILES['foto_com']) && $_FILES['foto_com']['error'] === UPLOAD_ERR_OK) {
            $data['foto_com_file'] = $_FILES['foto_com'];
        }

        try {
            $id = $this->model->create($data);
            $_SESSION['success'] = "Comunicado creado correctamente (ID: {$id})";
            header('Location: /announcements');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->view('announcement/create', [
                'title' => 'Nuevo Comunicado',
                'pageTitle' => 'Nuevo Comunicado',
                'old' => $data
            ]);
        }
    }

    /**
     * Formulario de edición
     * GET /announcements/{id}/edit
     */
    public function edit(int $id): void
    {
        $announcement = $this->model->findById($id);

        if (!$announcement) {
            $_SESSION['error'] = 'Comunicado no encontrado';
            header('Location: /announcements');
            exit;
        }

        $this->view('announcement/edit', [
            'announcement' => $announcement,
            'title' => 'Editar Comunicado',
            'pageTitle' => 'Editar Comunicado'
        ]);
    }

    /**
     * Actualiza un comunicado
     * POST /announcements/{id}/edit
     */
    public function update(int $id): void
    {
        $data = [
            'nom_com' => trim($_POST['nom_com'] ?? ''),
            'fech_com' => trim($_POST['fech_com'] ?? ''),
            'desc_com' => trim($_POST['desc_com'] ?? ''),
            'estado' => isset($_POST['estado']) ? 1 : 0,
        ];

        // Manejar doc_com y foto_com (solo guardar nombre original por ahora)
        if (isset($_FILES['doc_com']) && $_FILES['doc_com']['error'] === UPLOAD_ERR_OK) {
            $data['doc_com_file'] = $_FILES['doc_com'];
        }
        if (isset($_FILES['foto_com']) && $_FILES['foto_com']['error'] === UPLOAD_ERR_OK) {
            $data['foto_com_file'] = $_FILES['foto_com'];
        }

        try {
            $this->model->update($id, $data);
            $_SESSION['success'] = 'Comunicado actualizado correctamente';
            header('Location: /announcements');
            exit;
        } catch (\Exception $e) {
            $announcement = $this->model->findById($id);
            $_SESSION['error'] = $e->getMessage();
            $this->view('announcement/edit', [
                'announcement' => $announcement,
                'title' => 'Editar Comunicado',
                'pageTitle' => 'Editar Comunicado',
                'old' => $data
            ]);
        }
    }

    /**
     * Elimina un comunicado
     * POST /announcements/{id}/delete
     */
    public function destroy(int $id): void
    {
        try {
            $deleted = $this->model->delete($id);

            if ($deleted) {
                $_SESSION['success'] = 'Comunicado eliminado correctamente';
            } else {
                $_SESSION['error'] = 'El comunicado no existe';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al eliminar: ' . $e->getMessage();
        }

        header('Location: /announcements');
        exit;
    }
}