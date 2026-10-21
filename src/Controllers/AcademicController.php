<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Academic;

class AcademicController extends Controller
{
    private Academic $model;
    
    // Ruta absoluta dinámica hacia la carpeta public/uploads/img/academic/
    private string $uploadPath;

    public function __construct()
    {
        $this->model = new Academic();
        $this->uploadPath = dirname(__DIR__, 2) . '/public/uploads/img/academic/';
    }

    public function index(): void
    {
        $academics = $this->model->findAll();
        $this->view('academic/index', [
            'academics' => $academics,
            'title' => 'Plantel Docente',
            'pageTitle' => 'Plantel Docente'
        ]);
    }

    public function create(): void
    {
        $categories = $this->model->getCategories();
        $this->view('academic/create', [
            'categories' => $categories,
            'title' => 'Nuevo Académico',
            'pageTitle' => 'Nuevo Académico'
        ]);
    }

    public function store(): void
    {
        $data = [
            'nom_acad' => trim($_POST['nom_acad'] ?? ''),
            'cel_acad' => trim($_POST['cel_acad'] ?? ''),
            'email_acad' => trim($_POST['email_acad'] ?? ''),
            'cat_acad' => (int) ($_POST['cat_acad'] ?? 0),
            'desc_acad' => trim($_POST['desc_acad'] ?? ''),
            'mostrar_email' => isset($_POST['mostrar_email']) ? 1 : 0,
            'mostrar_cel' => isset($_POST['mostrar_cel']) ? 1 : 0,
        ];

        // Capturar y sanitizar sitio_web (opcional)
        $sitioWeb = trim($_POST['sitio_web'] ?? '');
        $data['sitio_web'] = $sitioWeb !== '' ? filter_var($sitioWeb, FILTER_SANITIZE_URL) : null;

        // Validación de seguridad: formato de celular
        if (!empty($data['cel_acad']) && !preg_match('/^[0-9\+\-\s]*$/', $data['cel_acad'])) {
            $categories = $this->model->getCategories();
            $_SESSION['error'] = 'El número de celular contiene caracteres no válidos.';
            $this->view('academic/create', [
                'categories' => $categories,
                'title' => 'Nuevo Docente y Administrativo',
                'pageTitle' => 'Nuevo Docente y Administrativo',
                'old' => $_POST
            ]);
            return;
        }

        try {
            // 1. Validar y procesar archivo obligatorio
            if (!isset($_FILES['foto_acad']) || $_FILES['foto_acad']['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception('La foto de perfil es obligatoria.');
            }

            $data['foto_acad'] = $this->uploadPhoto($_FILES['foto_acad']);

            // 2. Intentar crear en BD
            $id = $this->model->create($data);
            $_SESSION['success'] = "Docente creado correctamente.";
            $this->redirect('/academics');

        } catch (\Exception $e) {
            // Rollback físico: si falla el insert a BD, borramos la foto que se subió
            if (isset($data['foto_acad'])) {
                $this->deletePhoto($data['foto_acad']);
            }

            $categories = $this->model->getCategories();
            $_SESSION['error'] = $e->getMessage();
            $this->view('academic/create', [
                'categories' => $categories,
                'title' => 'Nuevo Docente y Administrativo',
                'pageTitle' => 'Nuevo Docente y Administrativo',
                'old' => $_POST
            ]);
        }
    }

    public function edit(int $id): void
    {
        $academic = $this->model->findById($id);

        if (!$academic) {
            $_SESSION['error'] = 'Docente no encontrado';
            $this->redirect('/academics');
        }

        $categories = $this->model->getCategories();
        $this->view('academic/edit', [
            'academic' => $academic,
            'categories' => $categories,
            'title' => 'Editar Académico',
            'pageTitle' => 'Editar Académico'
        ]);
    }

    public function update(int $id): void
    {
        $data = [
            'nom_acad' => trim($_POST['nom_acad'] ?? ''),
            'cel_acad' => trim($_POST['cel_acad'] ?? ''),
            'email_acad' => trim($_POST['email_acad'] ?? ''),
            'cat_acad' => (int) ($_POST['cat_acad'] ?? 0),
            'desc_acad' => trim($_POST['desc_acad'] ?? ''),
            'mostrar_email' => isset($_POST['mostrar_email']) ? 1 : 0,
            'mostrar_cel' => isset($_POST['mostrar_cel']) ? 1 : 0,
        ];

        // Capturar y sanitizar sitio_web (opcional)
        $sitioWeb = trim($_POST['sitio_web'] ?? '');
        $data['sitio_web'] = $sitioWeb !== '' ? filter_var($sitioWeb, FILTER_SANITIZE_URL) : null;

        // Validación de seguridad: formato de celular
        if (!empty($data['cel_acad']) && !preg_match('/^[0-9\+\-\s]*$/', $data['cel_acad'])) {
            $academic = $this->model->findById($id);
            $categories = $this->model->getCategories();
            $_SESSION['error'] = 'El número de celular contiene caracteres no válidos.';
            $this->view('academic/edit', [
                'academic' => $academic,
                'categories' => $categories,
                'title' => 'Editar Docente y Administrativo',
                'pageTitle' => 'Editar Docente y Administrativo',
                'old' => $_POST
            ]);
            return;
        }

        try {
            $academic = $this->model->findById($id);
            if (!$academic) {
                throw new \Exception("El docente no existe");
            }

            // --- Manejar foto_acad (imagen de perfil) con FileUploader ---
            $fotoAcad = $academic['foto_acad'] ?? '';
            $removeFoto = isset($_POST['remove_foto_acad']) && $_POST['remove_foto_acad'] === '1';

            if (isset($_FILES['foto_acad']) && $_FILES['foto_acad']['error'] === UPLOAD_ERR_OK) {
                // Prioridad: subida de nuevo archivo
                $uploaded = \App\Core\FileUploader::uploadImage($_FILES['foto_acad'], 'academic');
                if ($uploaded) {
                    if ($fotoAcad) \App\Core\FileUploader::delete($fotoAcad, 'img/academic');
                    $fotoAcad = $uploaded;
                } else {
                    $categories = $this->model->getCategories();
                    $_SESSION['error'] = 'Archivo de imagen inválido. Solo se permiten JPG, PNG, WebP.';
                    $this->view('academic/edit', [
                        'academic' => $academic,
                        'categories' => $categories,
                        'title' => 'Editar Docente y Administrativo',
                        'pageTitle' => 'Editar Docente y Administrativo',
                        'old' => $_POST
                    ]);
                    return;
                }
            } elseif ($removeFoto) {
                // Checkbox de eliminar marcado y NO hay archivo nuevo
                if ($fotoAcad) \App\Core\FileUploader::delete($fotoAcad, 'img/academic');
                $fotoAcad = null;
            }
            $data['foto_acad'] = $fotoAcad;

            $this->model->update($id, $data);
            $_SESSION['success'] = 'Docente actualizado correctamente';
            $this->redirect('/academics');

        } catch (\Exception $e) {
            $academic = $this->model->findById($id) ?: [];
            $categories = $this->model->getCategories();
            $_SESSION['error'] = $e->getMessage();
            $this->view('academic/edit', [
                'academic' => $academic,
                'categories' => $categories,
                'title' => 'Editar Docente y Administrativo',
                'pageTitle' => 'Editar Docente y Administrativo',
                'old' => $_POST
            ]);
        }
    }

    public function destroy(int $id): void
    {
        try {
            $academic = $this->model->findById($id);
            
            if ($academic && $this->model->delete($id)) {
                // Borrar foto física al eliminar registro
                if (!empty($academic['foto_acad']) && $academic['foto_acad'] !== 'default.png') {
                    $this->deletePhoto($academic['foto_acad']);
                }
                $_SESSION['success'] = 'Docente eliminado correctamente';
            } else {
                $_SESSION['error'] = 'El docente no existe';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al eliminar: ' . $e->getMessage();
        }

        $this->redirect('/academics');
    }

    /**
     * Alterna la visibilidad pública del académico (activo/inactivo)
     */
    public function toggle(int $id): void
    {
        try {
            if ($this->model->toggleActive($id)) {
                $_SESSION['success'] = 'Visibilidad del docente actualizada correctamente.';
            } else {
                $_SESSION['error'] = 'El docente no existe.';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al actualizar la visibilidad: ' . $e->getMessage();
        }

        $this->redirect('/academics');
    }

    /**
     * Sube y valida la imagen localmente para este módulo
     */
    private function uploadPhoto(array $file): string
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($mime, $allowed, true)) {
            throw new \Exception('Formato de imagen no permitido. Solo se admiten JPG, PNG o WebP.');
        }

        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg'
        };

        // Genera un nombre único estilo UUID
        $filename = bin2hex(random_bytes(16)) . '.' . $ext;

        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }

        if (!move_uploaded_file($file['tmp_name'], $this->uploadPath . $filename)) {
            throw new \Exception('Error crítico al guardar la imagen en el servidor.');
        }

        return $filename;
    }

    /**
     * Elimina el archivo físico
     */
    private function deletePhoto(string $filename): void
    {
        $filepath = $this->uploadPath . $filename;
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
}