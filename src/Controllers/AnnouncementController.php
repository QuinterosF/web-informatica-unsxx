<?php

namespace App\Controllers;

use App\Core\AuditLogger;
use App\Core\Controller;
use App\Core\FileUploader;
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

        // --- Manejar foto_com (imagen de portada) ---
        if (isset($_FILES['foto_com']) && $_FILES['foto_com']['error'] === UPLOAD_ERR_OK) {
            $uploaded = FileUploader::uploadImage($_FILES['foto_com'], 'comunicados');
            if (!$uploaded) {
                $_SESSION['error'] = 'Archivo de imagen inválido. Solo se permiten JPG, PNG, WebP.';
                $this->view('announcement/create', [
                    'title' => 'Nuevo Comunicado',
                    'pageTitle' => 'Nuevo Comunicado',
                    'old' => $_POST
                ]);
                return;
            }
            $data['foto_com'] = $uploaded;
        }

        // --- Manejar doc_com (documento PDF) ---
        if (isset($_FILES['doc_com']) && $_FILES['doc_com']['error'] === UPLOAD_ERR_OK) {
            $uploaded = FileUploader::uploadPdf($_FILES['doc_com'], 'comunicados');
            if (!$uploaded) {
                // Si falla la subida del PDF, limpiar imagen si se subió
                if (isset($data['foto_com'])) {
                    FileUploader::delete($data['foto_com'], 'img/comunicados');
                }
                $_SESSION['error'] = 'Archivo PDF inválido. Solo se permiten archivos PDF.';
                $this->view('announcement/create', [
                    'title' => 'Nuevo Comunicado',
                    'pageTitle' => 'Nuevo Comunicado',
                    'old' => $_POST
                ]);
                return;
            }
            $data['doc_com'] = $uploaded;
        }

        try {
            $id = $this->model->create($data);
            $_SESSION['success'] = "Comunicado creado correctamente (ID: {$id})";
            header('Location: /announcements');
            exit;
        } catch (\Exception $e) {
            // Rollback: eliminar archivos subidos si falla la BD
            if (isset($data['foto_com'])) {
                FileUploader::delete($data['foto_com'], 'img/comunicados');
            }
            if (isset($data['doc_com'])) {
                FileUploader::delete($data['doc_com'], 'pdf/comunicados');
            }
            $_SESSION['error'] = $e->getMessage();
            $this->view('announcement/create', [
                'title' => 'Nuevo Comunicado',
                'pageTitle' => 'Nuevo Comunicado',
                'old' => $_POST
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
        // Obtener el comunicado existente primero para acceder a los archivos actuales
        $existing = $this->model->findById($id);

        if (!$existing) {
            $_SESSION['error'] = 'Comunicado no encontrado';
            header('Location: /announcements');
            exit;
        }

        $data = [
            'nom_com' => trim($_POST['nom_com'] ?? ''),
            'fech_com' => trim($_POST['fech_com'] ?? ''),
            'desc_com' => trim($_POST['desc_com'] ?? ''),
            'estado' => isset($_POST['estado']) ? 1 : 0,
        ];

        $fotoCom = $existing['foto_com'] ?? '';
        $docCom = $existing['doc_com'] ?? '';

        // --- Manejar foto_com (imagen de portada) ---
        $fotoUploaded = isset($_FILES['foto_com']) && $_FILES['foto_com']['error'] === UPLOAD_ERR_OK;
        $removeFoto = isset($_POST['remove_foto_com']) && $_POST['remove_foto_com'] === '1';

        if ($fotoUploaded) {
            // Prioridad: subida de nuevo archivo
            $uploaded = FileUploader::uploadImage($_FILES['foto_com'], 'comunicados');
            if (!$uploaded) {
                $_SESSION['error'] = 'Archivo de imagen inválido. Solo se permiten JPG, PNG, WebP.';
                $this->view('announcement/edit', [
                    'announcement' => $existing,
                    'title' => 'Editar Comunicado',
                    'pageTitle' => 'Editar Comunicado',
                    'old' => $_POST
                ]);
                return;
            }
            // Borrar archivo anterior si existe
            if ($fotoCom) {
                FileUploader::delete($fotoCom, 'img/comunicados');
            }
            $fotoCom = $uploaded;
        } elseif ($removeFoto) {
            // Checkbox de eliminar marcado y NO hay archivo nuevo
            if ($fotoCom) {
                FileUploader::delete($fotoCom, 'img/comunicados');
            }
            $fotoCom = '';
        }

        // --- Manejar doc_com (documento PDF) ---
        $docUploaded = isset($_FILES['doc_com']) && $_FILES['doc_com']['error'] === UPLOAD_ERR_OK;
        $removeDoc = isset($_POST['remove_doc_com']) && $_POST['remove_doc_com'] === '1';

        if ($docUploaded) {
            // Prioridad: subida de nuevo archivo
            $uploaded = FileUploader::uploadPdf($_FILES['doc_com'], 'comunicados');
            if (!$uploaded) {
                $_SESSION['error'] = 'Archivo PDF inválido. Solo se permiten archivos PDF.';
                $this->view('announcement/edit', [
                    'announcement' => $existing,
                    'title' => 'Editar Comunicado',
                    'pageTitle' => 'Editar Comunicado',
                    'old' => $_POST
                ]);
                return;
            }
            // Borrar archivo anterior si existe
            if ($docCom) {
                FileUploader::delete($docCom, 'pdf/comunicados');
            }
            $docCom = $uploaded;
        } elseif ($removeDoc) {
            // Checkbox de eliminar marcado y NO hay archivo nuevo
            if ($docCom) {
                FileUploader::delete($docCom, 'pdf/comunicados');
            }
            $docCom = '';
        }

        $data['foto_com'] = $fotoCom;
        $data['doc_com'] = $docCom;

        try {
            $this->model->update($id, $data);
            $_SESSION['success'] = 'Comunicado actualizado correctamente';
            header('Location: /announcements');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->view('announcement/edit', [
                'announcement' => $existing,
                'title' => 'Editar Comunicado',
                'pageTitle' => 'Editar Comunicado',
                'old' => $_POST
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
            $existing = $this->model->findById($id);
            $deleted = $this->model->delete($id);

            if ($deleted) {
                AuditLogger::logDelete('Comunicados', $id, (string) ($existing['nom_com'] ?? 'Comunicado #' . $id));
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