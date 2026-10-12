<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\FileUploader;
use App\Models\InstitutionalInfo;

class InstitutionalInfoController extends Controller
{
    private InstitutionalInfo $model;

    public function __construct()
    {
        $this->model = new InstitutionalInfo();
    }

    /**
     * Formulario de edición (Registro único)
     * GET /institutional-info
     */
    public function edit(): void
    {
        $info = $this->model->get();
        $this->view('institutional/edit', [
            'info' => $info,
            'title' => 'Identidad y Textos Institucionales',
            'pageTitle' => 'Identidad y Textos Institucionales'
        ]);
    }

    /**
     * Actualiza la información institucional
     * POST /institutional-info
     */
    public function update(): void
    {
        $existing = $this->model->get();
        $oldFilename = $existing['foto_director'] ?? null;
        $newFilename = $oldFilename;

        // Recoger textos
        $data = [
            'presentacion' => trim($_POST['presentacion'] ?? ''),
            'perfil_general' => trim($_POST['perfil_general'] ?? ''),
            'mision' => trim($_POST['mision'] ?? ''),
            'vision' => trim($_POST['vision'] ?? ''),
            'objetivos' => trim($_POST['objetivos'] ?? ''),
            'resena_historica' => trim($_POST['resena_historica'] ?? ''),
            'mensaje_director' => trim($_POST['mensaje_director'] ?? ''),
        ];

        $uploadedFilename = null;

        // 1. ¿El usuario marcó el checkbox de eliminar la foto?
        if (isset($_POST['remove_foto_director']) && $_POST['remove_foto_director'] === '1') {
            if ($oldFilename) {
                FileUploader::delete($oldFilename, 'img/institucion');
            }
            $data['foto_director'] = null;
            $newFilename = null;
        }
        // 2. ¿El usuario subió una nueva foto?
        elseif (isset($_FILES['foto_director']) && $_FILES['foto_director']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['foto_director']['error'] === UPLOAD_ERR_OK) {
                try {
                    $uploadedFilename = $this->uploadFotoDirector($_FILES['foto_director']);
                    $data['foto_director'] = $uploadedFilename;
                    $newFilename = $uploadedFilename;

                    // Borrar la foto anterior si existe
                    if ($oldFilename) {
                        FileUploader::delete($oldFilename, 'img/institucion');
                    }
                } catch (\Exception $e) {
                    $_SESSION['error'] = $e->getMessage();
                    $info = $this->model->get();
                    $this->view('institutional/edit', [
                        'info' => $info,
                        'title' => 'Identidad y Textos Institucionales',
                        'pageTitle' => 'Identidad y Textos Institucionales',
                        'old' => $_POST
                    ]);
                    return;
                }
            } else {
                $_SESSION['error'] = 'Error al subir el archivo: ' . $_FILES['foto_director']['error'];
                $info = $this->model->get();
                $this->view('institutional/edit', [
                    'info' => $info,
                    'title' => 'Identidad y Textos Institucionales',
                    'pageTitle' => 'Identidad y Textos Institucionales',
                    'old' => $_POST
                ]);
                return;
            }
        }
        // 3. No tocó nada, mantener la actual
        else {
            $data['foto_director'] = $oldFilename;
        }

        // Auditoría
        $data['actualizado_por'] = (int)($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);

        try {
            $this->model->update($data);
            $_SESSION['success'] = 'Información institucional actualizada correctamente';
            header('Location: /institutional-info');
            exit;
        } catch (\Exception $e) {
            // Rollback: si se subió foto nueva y falla la BD, borrar la nueva
            if ($uploadedFilename) {
                FileUploader::delete($uploadedFilename, 'img/institucion');
            }
            $_SESSION['error'] = $e->getMessage();
            $info = $this->model->get();
            $this->view('institutional/edit', [
                'info' => $info,
                'title' => 'Identidad y Textos Institucionales',
                'pageTitle' => 'Identidad y Textos Institucionales',
                'old' => $_POST
            ]);
        }
    }

    /**
     * Sube la foto del director (OPCIONAL)
     * Valida MIME type (JPG, PNG, WEBP), genera UUID, guarda en public/uploads/img/institucion/
     *
     * @throws \Exception si validación falla
     */
    private function uploadFotoDirector(array $file): string
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($mime, $allowedMimes, true)) {
            throw new \Exception('Archivo inválido. Solo se permiten imágenes JPG, PNG, WebP');
        }

        $extension = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg'
        };

        $filename = uniqid('dir_') . '.' . $extension;
        $destinationDir = __DIR__ . '/../../public/uploads/img/institucion/';

        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $destinationPath = $destinationDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destinationPath)) {
            throw new \Exception('Error al guardar el archivo en el servidor');
        }

        return $filename;
    }
}