<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\FileUploader;
use App\Models\Lab;
use App\Models\Academic;

class LabController extends Controller
{
    private Lab $model;
    private Academic $academicModel;

    public function __construct()
    {
        $this->model = new Lab();
        $this->academicModel = new Academic();
    }

    /**
     * Listado de laboratorios
     * GET /labs
     */
    public function index(): void
    {
        $labs = $this->model->all();
        $this->view('lab/index', [
            'labs' => $labs,
            'title' => 'Laboratorios - Ingeniería Informática UNSXX',
            'pageTitle' => 'Laboratorios'
        ]);
    }

    /**
     * Formulario de creación
     * GET /labs/create
     */
    public function create(): void
    {
        $academics = $this->academicModel->all();
        $this->view('lab/create', [
            'academics' => $academics,
            'title' => 'Nuevo Laboratorio',
            'pageTitle' => 'Nuevo Laboratorio'
        ]);
    }

    /**
     * Guarda un nuevo laboratorio
     * POST /labs
     */
    public function store(): void
    {
        $data = [
            'nombre_lab' => trim($_POST['nombre_lab'] ?? ''),
            'resp_acad_id' => (int)($_POST['resp_acad_id'] ?? 0),
            'activo' => isset($_POST['activo']) ? 1 : 0,
        ];

        $uploadedFilename = null;

        // Manejar archivo PDF del horario (OPCIONAL)
        if (isset($_FILES['horario_lab']) && $_FILES['horario_lab']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['horario_lab']['error'] === UPLOAD_ERR_OK) {
                $uploadedFilename = FileUploader::uploadPdf($_FILES['horario_lab'], 'laboratorios');
                if (!$uploadedFilename) {
                    $academics = $this->academicModel->all();
                    $_SESSION['error'] = 'Archivo inválido. Solo se permiten PDFs.';
                    $this->view('lab/create', [
                        'academics' => $academics,
                        'title' => 'Nuevo Laboratorio',
                        'pageTitle' => 'Nuevo Laboratorio',
                        'old' => $data
                    ]);
                    return;
                }
                $data['horario_lab'] = $uploadedFilename;
            } else {
                $academics = $this->academicModel->all();
                $_SESSION['error'] = 'Error al subir el archivo: ' . $_FILES['horario_lab']['error'];
                $this->view('lab/create', [
                    'academics' => $academics,
                    'title' => 'Nuevo Laboratorio',
                    'pageTitle' => 'Nuevo Laboratorio',
                    'old' => $data
                ]);
                return;
            }
        }

        try {
            $id = $this->model->create($data);
            $_SESSION['success'] = "Laboratorio creado correctamente (ID: {$id})";
            header('Location: /labs');
            exit;
        } catch (\Exception $e) {
            // Rollback: eliminar archivo subido si falla la BD
            if ($uploadedFilename) {
                FileUploader::delete($uploadedFilename, 'pdf/laboratorios');
            }
            $academics = $this->academicModel->all();
            $_SESSION['error'] = $e->getMessage();
            $this->view('lab/create', [
                'academics' => $academics,
                'title' => 'Nuevo Laboratorio',
                'pageTitle' => 'Nuevo Laboratorio',
                'old' => $data
            ]);
        }
    }

    /**
     * Formulario de edición
     * GET /labs/{id}/edit
     */
    public function edit(int $id): void
    {
        $lab = $this->model->findById($id);

        if (!$lab) {
            $_SESSION['error'] = 'Laboratorio no encontrado';
            header('Location: /labs');
            exit;
        }

        $academics = $this->academicModel->all();
        $this->view('lab/edit', [
            'lab' => $lab,
            'academics' => $academics,
            'title' => 'Editar Laboratorio',
            'pageTitle' => 'Editar Laboratorio'
        ]);
    }

    /**
     * Actualiza un laboratorio
     * POST /labs/{id}/edit
     */
    public function update(int $id): void
    {
        $existing = $this->model->findById($id);

        if (!$existing) {
            $_SESSION['error'] = 'Laboratorio no encontrado';
            header('Location: /labs');
            exit;
        }

        $data = [
            'nombre_lab' => trim($_POST['nombre_lab'] ?? ''),
            'resp_acad_id' => (int)($_POST['resp_acad_id'] ?? 0),
            'activo' => isset($_POST['activo']) ? 1 : 0,
        ];

        $oldFilename = $existing['horario_lab'] ?? '';
        $uploadedFilename = null;

        // 1. ¿El usuario marcó el checkbox de eliminar el PDF?
        if (isset($_POST['remove_pdf']) && $_POST['remove_pdf'] === '1') {
            if ($oldFilename) {
                FileUploader::delete($oldFilename, 'pdf/laboratorios');
            }
            $data['horario_lab'] = null;
        }
        // 2. ¿El usuario subió un nuevo PDF?
        elseif (isset($_FILES['horario_lab']) && $_FILES['horario_lab']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['horario_lab']['error'] === UPLOAD_ERR_OK) {
                $uploadedFilename = FileUploader::uploadPdf($_FILES['horario_lab'], 'laboratorios');
                if (!$uploadedFilename) {
                    $academics = $this->academicModel->all();
                    $_SESSION['error'] = 'Archivo inválido. Solo se permiten PDFs.';
                    $this->view('lab/edit', [
                        'lab' => $existing,
                        'academics' => $academics,
                        'title' => 'Editar Laboratorio',
                        'pageTitle' => 'Editar Laboratorio',
                        'old' => $data
                    ]);
                    return;
                }
                $data['horario_lab'] = $uploadedFilename;

                // Borrar el PDF anterior si existe
                if ($oldFilename) {
                    FileUploader::delete($oldFilename, 'pdf/laboratorios');
                }
            } else {
                $academics = $this->academicModel->all();
                $_SESSION['error'] = 'Error al subir el archivo: ' . $_FILES['horario_lab']['error'];
                $this->view('lab/edit', [
                    'lab' => $existing,
                    'academics' => $academics,
                    'title' => 'Editar Laboratorio',
                    'pageTitle' => 'Editar Laboratorio',
                    'old' => $data
                ]);
                return;
            }
        }
        // 3. No tocó nada, mantener el actual
        else {
            $data['horario_lab'] = $oldFilename;
        }

        try {
            $this->model->update($id, $data);
            $_SESSION['success'] = 'Laboratorio actualizado correctamente';
            header('Location: /labs');
            exit;
        } catch (\Exception $e) {
            // Rollback: si se subió PDF nuevo y falla la BD, borrar el nuevo
            if ($uploadedFilename) {
                FileUploader::delete($uploadedFilename, 'pdf/laboratorios');
            }
            $lab = $this->model->findById($id);
            $academics = $this->academicModel->all();
            $_SESSION['error'] = $e->getMessage();
            $this->view('lab/edit', [
                'lab' => $lab,
                'academics' => $academics,
                'title' => 'Editar Laboratorio',
                'pageTitle' => 'Editar Laboratorio',
                'old' => $data
            ]);
        }
    }

    /**
     * Elimina un laboratorio
     * POST /labs/{id}/delete
     */
    public function destroy(int $id): void
    {
        try {
            $existing = $this->model->findById($id);

            $deleted = $this->model->delete($id);

            if ($deleted) {
                // Borrar archivo PDF físico asociado si existe
                if ($existing && $existing['horario_lab']) {
                    FileUploader::delete($existing['horario_lab'], 'pdf/laboratorios');
                }
                $_SESSION['success'] = 'Laboratorio eliminado correctamente';
            } else {
                $_SESSION['error'] = 'El laboratorio no existe';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al eliminar: ' . $e->getMessage();
        }

        header('Location: /labs');
        exit;
    }
}