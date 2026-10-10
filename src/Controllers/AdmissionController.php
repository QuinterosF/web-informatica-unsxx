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
            'img_adm' => 'default.png'
        ];

        try {
            if (isset($_FILES['img_adm']) && $_FILES['img_adm']['error'] === UPLOAD_ERR_OK) {
                $data['img_adm'] = $this->uploadPoster($_FILES['img_adm']);
            }

            $id = $this->model->create($data);
            $_SESSION['success'] = "Admisión creada correctamente (ID: {$id})";
            header('Location: /admissions');
            exit;

        } catch (\Exception $e) {
            if (isset($data['img_adm']) && $data['img_adm'] !== 'default.png') {
                $this->deletePoster($data['img_adm']);
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

        $oldFilename = $existing['img_adm'] ?? 'default.png';

        try {
            // 1. ¿El usuario marcó el checkbox de eliminar la imagen?
            if (isset($_POST['remove_image']) && $_POST['remove_image'] === '1') {
                if ($oldFilename !== 'default.png' && !empty($oldFilename)) {
                    $this->deletePoster($oldFilename);
                }
                $data['img_adm'] = 'default.png';
            }
            // 2. ¿El usuario subió una nueva imagen?
            elseif (isset($_FILES['img_adm']) && $_FILES['img_adm']['error'] === UPLOAD_ERR_OK) {
                $data['img_adm'] = $this->uploadPoster($_FILES['img_adm']);
                
                if ($oldFilename !== 'default.png' && !empty($oldFilename)) {
                    $this->deletePoster($oldFilename);
                }
            } 
            // 3. No tocó nada, mantener la actual
            else {
                $data['img_adm'] = $oldFilename;
            }

            $this->model->update($id, $data);
            $_SESSION['success'] = 'Admisión actualizada correctamente';
            header('Location: /admissions');
            exit;

        } catch (\Exception $e) {
            if (isset($data['img_adm']) && $data['img_adm'] !== $oldFilename && $data['img_adm'] !== 'default.png') {
                $this->deletePoster($data['img_adm']);
            }
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
                // Borrar archivo físico asociado si existe
                if ($existing && $existing['img_adm']) {
                    $this->deletePoster($existing['img_adm']);
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
     * Valida MIME type, genera UUID, guarda en public/uploads/img/admission/
     *
     * @throws \Exception si validación falla
     */
    private function uploadPoster(array $file): string
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($mime, $allowed, true)) {
            throw new \Exception('Formato de afiche no permitido. Solo se admiten JPG, PNG o WebP.');
        }

        $ext = match ($mime) {
            'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', default => 'jpg'
        };

        $filename = bin2hex(random_bytes(16)) . '.' . $ext;
        $uploadPath = dirname(__DIR__, 2) . '/public/uploads/img/admission/';

        if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);

        if (!move_uploaded_file($file['tmp_name'], $uploadPath . $filename)) {
            throw new \Exception('Error al guardar el afiche.');
        }

        return $filename;
    }

    /**
     * Elimina el afiche físico del disco
     */
    private function deletePoster(string $filename): void
    {
        $filepath = dirname(__DIR__, 2) . '/public/uploads/img/admission/' . $filename;
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
}