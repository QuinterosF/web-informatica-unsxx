<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\FileUploader;
use App\Models\Portada;

class PortadaController extends Controller
{
    private Portada $model;

    public function __construct()
    {
        $this->model = new Portada();
    }

    /**
     * Listado de portadas del carrusel
     * GET /portadas
     */
    public function index(): void
    {
        $portadas = $this->model->getAll();
        $this->view('institutional/portadas', [
            'portadas' => $portadas,
            'title' => 'Portadas de Inicio - Ingeniería Informática UNSXX',
            'pageTitle' => 'Portadas de Inicio (Carrusel)'
        ]);
    }

    /**
     * Sube múltiples fotos al carrusel
     * POST /portadas
     */
    public function upload(): void
    {
        if (!isset($_FILES['fotos']) || empty($_FILES['fotos']['name'][0])) {
            $_SESSION['error'] = 'No se seleccionaron archivos';
            header('Location: /portadas');
            exit;
        }

        $uploadedCount = 0;
        $files = $_FILES['fotos'];
        $fileCount = count($files['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            $file = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i],
            ];

            if ($file['error'] === UPLOAD_ERR_OK) {
                $uploaded = FileUploader::uploadImage($file, 'portadas');
                if ($uploaded) {
                    $this->model->addPhoto($uploaded);
                    $uploadedCount++;
                }
            }
        }

        if ($uploadedCount > 0) {
            $_SESSION['success'] = "Se subieron {$uploadedCount} foto(s) correctamente";
        } else {
            $_SESSION['error'] = 'No se pudo subir ninguna foto';
        }

        header('Location: /portadas');
        exit;
    }

    /**
     * Elimina una foto del carrusel
     * POST /portadas/{id}/delete
     */
    public function delete(int $id): void
    {
        $photo = $this->model->findById($id);

        if ($photo) {
            // Borrar archivo físico
            FileUploader::delete($photo['archivo_url'], 'img/portadas');
            // Borrar de BD
            $this->model->delete($id);
            $_SESSION['success'] = 'Foto eliminada correctamente';
        } else {
            $_SESSION['error'] = 'Foto no encontrada';
        }

        header('Location: /portadas');
        exit;
    }

    /**
     * Actualiza el título (opcional) y el orden de una portada
     * POST /portadas/{id}/update
     */
    public function update(int $id): void
    {
        $titulo = trim($_POST['titulo'] ?? '');
        $orden = (int) ($_POST['orden'] ?? 0);

        $this->model->updateInfo($id, $titulo, $orden);

        $_SESSION['success'] = 'Portada actualizada correctamente';
        header('Location: /portadas');
        exit;
    }

    /**
     * Guarda en lote el orden y los títulos de las portadas
     * POST /portadas/batch-update
     */
    public function saveBatch(): void
    {
        $ordenes = $_POST['orden'] ?? [];
        $titulos = $_POST['titulo'] ?? [];

        if (!is_array($ordenes) || !is_array($titulos)) {
            $_SESSION['error'] = 'Datos inválidos';
            header('Location: /portadas');
            exit;
        }

        $items = [];
        foreach ($ordenes as $idPortada => $orden) {
            $items[] = [
                'id' => (int) $idPortada,
                'titulo' => trim((string) ($titulos[$idPortada] ?? '')),
                'orden' => (int) $orden,
                'activo' => isset($_POST['activo'][$idPortada]) && (int) $_POST['activo'][$idPortada] === 1 ? 1 : 0,
            ];
        }

        try {
            $this->model->updateBatch($items);
            $_SESSION['success'] = 'Orden, títulos y visibilidad actualizados correctamente.';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al actualizar las portadas: ' . $e->getMessage();
        }

        header('Location: /portadas');
        exit;
    }

    /**
     * Alterna el estado activo/inactivo de una portada
     * POST /portadas/{id}/toggle
     */
    public function toggle(int $id): void
    {
        try {
            $this->model->toggleActive($id);
            $_SESSION['success'] = 'Estado actualizado correctamente';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al actualizar estado: ' . $e->getMessage();
        }

        header('Location: /portadas');
        exit;
    }
}