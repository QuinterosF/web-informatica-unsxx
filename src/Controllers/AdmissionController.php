<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\FileUploader;
use App\Models\Admission;

class AdmissionController extends Controller
{
    private Admission $model;

    public function __construct()
    {
        $this->model = new Admission();
    }

    /**
     * Listado de admisiones
     * GET /admissions
     */
    public function index(): void
    {
        $admissions = $this->model->all();
        $this->view('admission/index', [
            'admissions' => $admissions,
            'title' => 'Admisión - Ingeniería Informática UNSXX',
            'pageTitle' => 'Admisión'
        ]);
    }

    /**
     * Formulario de creación
     * GET /admissions/create
     */
    public function create(): void
    {
        $this->view('admission/create', [
            'title' => 'Nueva Admisión',
            'pageTitle' => 'Nueva Admisión'
        ]);
    }

    /**
     * Guarda una nueva admisión
     * POST /admissions
     */
    public function store(): void
    {
        $data = [
            'proc_adm' => trim($_POST['proc_adm'] ?? ''),
            'req_adm' => trim($_POST['req_adm'] ?? ''),
            'tipo' => $_POST['tipo'] ?? '',
            'gestion' => (int)($_POST['gestion'] ?? date('Y')),
            'estado' => isset($_POST['estado']) ? 1 : 0,
            'actualizado_por' => (int)($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1),
            'img_adm' => null
        ];

        try {
            if (isset($_FILES['img_adm']) && $_FILES['img_adm']['error'] === UPLOAD_ERR_OK) {
                $uploaded = \App\Core\FileUploader::uploadImage($_FILES['img_adm'], 'admission');
                if (!$uploaded) {
                    $_SESSION['error'] = 'Archivo de imagen inválido. Solo se permiten JPG, PNG, WebP.';
                    $this->view('admission/create', [
                        'title' => 'Nueva Admisión',
                        'pageTitle' => 'Nueva Admisión',
                        'old' => $_POST
                    ]);
                    return;
                }
                $data['img_adm'] = $uploaded;
            }

            $id = $this->model->create($data);
            $_SESSION['success'] = "Admisión creada correctamente (ID: {$id})";
            header('Location: /admissions');
            exit;

        } catch (\Exception $e) {
            if (!empty($data['img_adm']) && $data['img_adm'] !== 'default.png') {
                \App\Core\FileUploader::delete($data['img_adm'], 'img/admission');
            }
            $_SESSION['error'] = $e->getMessage();
            $this->view('admission/create', [
                'title' => 'Nueva Admisión', 'pageTitle' => 'Nueva Admisión', 'old' => $_POST
            ]);
        }
    }

    /**
     * Formulario de edición
     * GET /admissions/{id}/edit
     */
    public function edit(int $id): void
    {
        $admission = $this->model->findById($id);

        if (!$admission) {
            $_SESSION['error'] = 'Admisión no encontrada';
            header('Location: /admissions');
            exit;
        }

        $this->view('admission/edit', [
            'admission' => $admission,
            'title' => 'Editar Admisión',
            'pageTitle' => 'Editar Admisión'
        ]);
    }

    /**
     * Actualiza una admisión
     * POST /admissions/{id}/edit
     */
    public function update(int $id): void
    {
        $existing = $this->model->findById($id);

        if (!$existing) {
            $_SESSION['error'] = 'Admisión no encontrada';
            header('Location: /admissions');
            exit;
        }

        $data = [
            'proc_adm' => trim($_POST['proc_adm'] ?? ''),
            'req_adm' => trim($_POST['req_adm'] ?? ''),
            'tipo' => $_POST['tipo'] ?? '',
            'gestion' => (int)($_POST['gestion'] ?? date('Y')),
            'estado' => isset($_POST['estado']) ? 1 : 0,
            'actualizado_por' => (int)($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1),
        ];

        $imgAdm = $existing['img_adm'] ?: null;
        $removeAfiche = isset($_POST['eliminar_afiche']) && $_POST['eliminar_afiche'] === '1';

        if (isset($_FILES['img_adm']) && $_FILES['img_adm']['error'] === UPLOAD_ERR_OK) {
            $uploaded = \App\Core\FileUploader::uploadImage($_FILES['img_adm'], 'admission');
            if ($uploaded) {
                // Candado de seguridad: Jamás borrar default.png
                if ($imgAdm && $imgAdm !== 'default.png') {
                    \App\Core\FileUploader::delete($imgAdm, 'img/admission');
                }
                $imgAdm = $uploaded;
            } else {
                $_SESSION['error'] = 'Archivo de imagen inválido. Solo se permiten JPG, PNG, WebP.';
                $this->view('admission/edit', [
                    'admission' => $existing,
                    'title' => 'Editar Admisión',
                    'pageTitle' => 'Editar Admisión',
                    'old' => $_POST
                ]);
                return;
            }
        } elseif ($removeAfiche) {
            // Candado de seguridad
            if ($imgAdm && $imgAdm !== 'default.png') {
                $this->deletePoster($imgAdm);
            }
            $imgAdm = null;
        }

        $data['img_adm'] = $imgAdm;

        try {
            $this->model->update($id, $data);
            $_SESSION['success'] = 'Admisión actualizada correctamente';
            header('Location: /admissions');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->view('admission/edit', [
                'admission' => $existing, 'title' => 'Editar Admisión', 'pageTitle' => 'Editar Admisión', 'old' => $_POST
            ]);
        }
    }

    /**
     * Elimina una admisión
     * POST /admissions/{id}/delete
     */
    public function destroy(int $id): void
    {
        try {
            $existing = $this->model->findById($id);

            $deleted = $this->model->delete($id);

            if ($deleted) {
                // Borrar archivo físico asociado si existe y no es default.png
                if ($existing && $existing['img_adm'] && $existing['img_adm'] !== 'default.png') {
                    \App\Core\FileUploader::delete($existing['img_adm'], 'img/admission');
                }
                $_SESSION['success'] = 'Admisión eliminada correctamente';
            } else {
                $_SESSION['error'] = 'La admisión no existe';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al eliminar: ' . $e->getMessage();
        }

        header('Location: /admissions');
        exit;
    }

    /**
     * Sube el afiche de admisión (OPCIONAL)
     * Usa FileUploader para consistencia
     *
     * @throws \Exception si validación falla
     */
    private function uploadPoster(array $file): string
    {
        $uploaded = \App\Core\FileUploader::uploadImage($file, 'admission');
        if (!$uploaded) {
            throw new \Exception('Formato de afiche no permitido. Solo se admiten JPG, PNG o WebP.');
        }
        return $uploaded;
    }

    /**
     * Elimina el afiche físico del disco
     */
    private function deletePoster(string $filename): void
    {
        \App\Core\FileUploader::delete($filename, 'img/admission');
    }
}