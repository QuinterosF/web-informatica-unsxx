<?php

namespace App\Controllers;

use App\Core\AuditLogger;
use App\Core\Controller;
use App\Core\FileUploader;
use App\Models\Extension;

class ExtensionController extends Controller
{
    private Extension $model;

    public function __construct()
    {
        $this->model = new Extension();
    }

    /* ==================== EVENTOS ==================== */

    /**
     * Listado de actividades de extensión
     * GET /extensions
     */
    public function index(): void
    {
        $extensions = $this->model->findAll();
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
            'categories' => $this->model->getCategories(true),
            'title' => 'Nueva Extensión',
            'pageTitle' => 'Nueva Actividad de Extensión'
        ]);
    }

    /**
     * Guarda una nueva actividad
     * POST /extensions
     */
    public function store(): void
    {
        $data = $this->collectData();
        $data['creado_por'] = (int) ($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);

        try {
            $this->model->create($data);
            $_SESSION['success'] = 'Actividad de extensión creada correctamente';
            $this->redirect('/extensions');
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->view('extension/create', [
                'categories' => $this->model->getCategories(true),
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
            $_SESSION['error'] = 'Actividad de extensión no encontrada';
            $this->redirect('/extensions');
        }

        $this->view('extension/edit', [
            'extension' => $extension,
            'categories' => $this->model->getCategories(true),
            'title' => 'Editar Extensión',
            'pageTitle' => 'Editar Actividad de Extensión'
        ]);
    }

    /**
     * Actualiza una actividad
     * POST /extensions/{id}/edit
     */
    public function update(int $id): void
    {
        $data = $this->collectData();

        try {
            $this->model->update($id, $data);
            $_SESSION['success'] = 'Actividad de extensión actualizada correctamente';
            $this->redirect('/extensions');
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->view('extension/edit', [
                'extension' => $this->model->findById($id) ?: [],
                'categories' => $this->model->getCategories(true),
                'title' => 'Editar Extensión',
                'pageTitle' => 'Editar Actividad de Extensión',
                'old' => $data
            ]);
        }
    }

    /**
     * Elimina una actividad y todas sus fotos del disco
     * POST /extensions/{id}/delete
     */
    public function destroy(int $id): void
    {
        try {
            $event = $this->model->findById($id);
            $photos = $this->model->getGallery($id);
            foreach ($photos as $photo) {
                FileUploader::delete($photo['nom_foto'], 'img/extension');
            }

            $deleted = $this->model->delete($id);
            if ($deleted) {
                AuditLogger::logDelete('Extensión', $id, (string) ($event['nom_ext'] ?? 'Evento #' . $id));
            }
            $_SESSION[$deleted ? 'success' : 'error'] = $deleted
                ? 'Actividad de extensión eliminada correctamente'
                : 'La actividad de extensión no existe';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al eliminar: ' . $e->getMessage();
        }

        $this->redirect('/extensions');
    }

    /* ==================== CATEGORÍAS ==================== */

    /**
     * Gestión de categorías
     * GET /extensions/categories
     */
    public function categories(): void
    {
        $this->view('extension/categories', [
            'categories' => $this->model->getCategories(false),
            'title' => 'Categorías de Extensión',
            'pageTitle' => 'Categorías de Extensión'
        ]);
    }

    /**
     * Crea una categoría
     * POST /extensions/categories
     */
    public function storeCategory(): void
    {
        $nom_cat = trim($_POST['nom_cat'] ?? '');
        $color_badge = trim($_POST['color_badge'] ?? '');

        try {
            $this->model->createCategory($nom_cat, $color_badge);
            $_SESSION['success'] = 'Categoría creada correctamente';
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        $this->redirect('/extensions/categories');
    }

    /**
     * Actualiza una categoría
     * POST /extensions/categories/{id_cat}/update
     */
    public function updateCategory(int $id_cat): void
    {
        $nom_cat = trim($_POST['nom_cat'] ?? '');
        $color_badge = trim($_POST['color_badge'] ?? '');

        try {
            $this->model->updateCategory($id_cat, $nom_cat, $color_badge);
            $_SESSION['success'] = 'Categoría actualizada correctamente';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al actualizar la categoría: ' . $e->getMessage();
        }

        $this->redirect('/extensions/categories');
    }

    /**
     * Alterna el estado activo/inactivo de una categoría
     * POST /extensions/categories/{id_cat}/toggle
     */
    public function toggleCategory(int $id_cat): void
    {
        try {
            $this->model->toggleCategory($id_cat);
            $_SESSION['success'] = 'Estado de la categoría actualizado';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al actualizar la categoría: ' . $e->getMessage();
        }

        $this->redirect('/extensions/categories');
    }

    /**
     * Elimina una categoría
     * POST /extensions/categories/{id_cat}/delete
     */
    public function deleteCategory(int $id_cat): void
    {
        try {
            $deleted = $this->model->deleteCategory($id_cat);
            $_SESSION[$deleted ? 'success' : 'error'] = $deleted
                ? 'Categoría eliminada correctamente'
                : 'La categoría no existe';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al eliminar la categoría: ' . $e->getMessage();
        }

        $this->redirect('/extensions/categories');
    }

    /* ==================== GALERÍA DE FOTOS ==================== */

    /**
     * Galería fotográfica del evento
     * GET /extensions/{id}/gallery
     */
    public function gallery(int $id): void
    {
        $extension = $this->model->findById($id);

        if (!$extension) {
            $_SESSION['error'] = 'Actividad de extensión no encontrada';
            $this->redirect('/extensions');
        }

        $this->view('extension/gallery', [
            'extension' => $extension,
            'photos' => $this->model->getGallery($id),
            'title' => 'Galería - ' . $extension['nom_ext'],
            'pageTitle' => 'Galería del Evento'
        ]);
    }

    /**
     * Sube múltiples fotos a la galería
     * POST /extensions/{id}/gallery
     */
    public function uploadPhotos(int $id): void
    {
        if (!isset($_FILES['fotos']) || empty($_FILES['fotos']['name'][0])) {
            $_SESSION['error'] = 'No se seleccionaron archivos';
            $this->redirect("/extensions/{$id}/gallery");
        }

        $uploaded = 0;
        $files = $_FILES['fotos'];
        $total = count($files['name']);
        $subidoPor = (int) ($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);

        for ($i = 0; $i < $total; $i++) {
            $file = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i],
            ];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                continue;
            }

            $filename = FileUploader::uploadImage($file, 'extension');
            if ($filename) {
                $this->model->addPhoto($id, $filename, $subidoPor);
                $uploaded++;
            }
        }

        $_SESSION[$uploaded > 0 ? 'success' : 'error'] = $uploaded > 0
            ? "Se subieron {$uploaded} foto(s) correctamente"
            : 'No se pudo subir ninguna foto (solo JPG, PNG o WebP)';

        $this->redirect("/extensions/{$id}/gallery");
    }

    /**
     * Elimina una foto de la galería
     * POST /extensions/{id_ext}/gallery/{id_foto}/delete
     */
    public function deletePhoto(int $id_ext, int $id_foto): void
    {
        $photo = $this->model->findPhotoById($id_foto);

        if ($photo && (int) $photo['id_ext'] === $id_ext) {
            $event = $this->model->findById($id_ext);
            FileUploader::delete($photo['nom_foto'], 'img/extension');
            $this->model->deletePhoto($id_foto);
            AuditLogger::logDelete('Extensión', $id_foto, 'Foto de galería: ' . ($event['nom_ext'] ?? 'Evento #' . $id_ext));
            $_SESSION['success'] = 'Foto eliminada correctamente';
        } else {
            $_SESSION['error'] = 'La foto no existe o no pertenece a este evento';
        }

        $this->redirect("/extensions/{$id_ext}/gallery");
    }

    /**
     * Marca una foto como portada del evento
     * POST /extensions/{id_ext}/gallery/{id_foto}/cover
     */
    public function setCover(int $id_ext, int $id_foto): void
    {
        try {
            $this->model->setCoverPhoto($id_ext, $id_foto);
            $_SESSION['success'] = 'Portada actualizada correctamente';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al actualizar la portada: ' . $e->getMessage();
        }

        $this->redirect("/extensions/{$id_ext}/gallery");
    }

    /* ==================== AUXILIARES ==================== */

    /**
     * Recolecta y sanitiza los campos del formulario de actividad
     */
    private function collectData(): array
    {
        $enlace = trim($_POST['enlace_externo'] ?? '');
        $lugar = trim($_POST['lugar_ext'] ?? '');
        $idCat = trim($_POST['id_cat_ext'] ?? '');

        return [
            'nom_ext' => trim($_POST['nom_ext'] ?? ''),
            'id_cat_ext' => $idCat !== '' ? (int) $idCat : null,
            'fech_ext' => trim($_POST['fech_ext'] ?? ''),
            'lugar_ext' => $lugar !== '' ? $lugar : null,
            'desc_ext' => trim($_POST['desc_ext'] ?? ''),
            'enlace_externo' => $enlace !== '' ? filter_var($enlace, FILTER_SANITIZE_URL) : null,
            'destacado' => isset($_POST['destacado']) ? 1 : 0,
            'activo' => isset($_POST['activo']) ? 1 : 0,
        ];
    }
}