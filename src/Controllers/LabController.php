<?php

namespace App\Controllers;

use App\Core\Controller;
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
        $academics = $this->academicModel->all(); // Usar Academic model para el select
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

        // Manejar archivo PDF del horario (solo guardar nombre original por ahora)
        if (isset($_FILES['horario_lab']) && $_FILES['horario_lab']['error'] === UPLOAD_ERR_OK) {
            $data['horario_file'] = $_FILES['horario_lab'];
        }

        try {
            $id = $this->model->create($data);
            $_SESSION['success'] = "Laboratorio creado correctamente (ID: {$id})";
            header('Location: /labs');
            exit;
        } catch (\Exception $e) {
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
        $data = [
            'nombre_lab' => trim($_POST['nombre_lab'] ?? ''),
            'resp_acad_id' => (int)($_POST['resp_acad_id'] ?? 0),
            'activo' => isset($_POST['activo']) ? 1 : 0,
        ];

        // Manejar archivo PDF del horario (solo guardar nombre original por ahora)
        if (isset($_FILES['horario_lab']) && $_FILES['horario_lab']['error'] === UPLOAD_ERR_OK) {
            $data['horario_file'] = $_FILES['horario_lab'];
        }

        try {
            $this->model->update($id, $data);
            $_SESSION['success'] = 'Laboratorio actualizado correctamente';
            header('Location: /labs');
            exit;
        } catch (\Exception $e) {
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
            $deleted = $this->model->delete($id);
            
            if ($deleted) {
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

    /**
     * Genera UUID para PDF (placeholder)
     */
    private function generatePdfUuid(string $extension = 'pdf'): string
    {
        return bin2hex(random_bytes(16)) . '.' . strtolower($extension);
    }
}