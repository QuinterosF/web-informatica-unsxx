<?php

namespace App\Controllers;

use App\Core\AuditLogger;
use App\Core\Controller;
use App\Core\FileUploader;
use App\Models\Logro;

class LogroController extends Controller
{
    private Logro $model;

    public function __construct()
    {
        $this->model = new Logro();
    }

    /**
     * Listado de logros
     * GET /logros
     */
    public function index(): void
    {
        $this->view('logro/index', [
            'logros' => $this->model->findAll(),
            'title' => 'Logros Institucionales - Ingeniería Informática UNSXX',
            'pageTitle' => 'Logros Institucionales'
        ]);
    }

    /**
     * Formulario de creación
     * GET /logros/create
     */
    public function create(): void
    {
        $this->view('logro/create', [
            'posiciones' => Logro::POSICIONES,
            'title' => 'Nuevo Logro',
            'pageTitle' => 'Nuevo Logro Institucional'
        ]);
    }

    /**
     * Guarda un nuevo logro
     * POST /logros
     */
    public function store(): void
    {
        $data = $this->collectData();
        $data['registrado_por'] = (int) ($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);

        // --- Manejar foto_logro (opcional) ---
        if (isset($_FILES['foto_logro']) && $_FILES['foto_logro']['error'] === UPLOAD_ERR_OK) {
            $uploaded = FileUploader::uploadImage($_FILES['foto_logro'], 'logros');
            if (!$uploaded) {
                $this->renderFormError('Archivo de imagen inválido. Solo se permiten JPG, PNG, WebP.', $data);
                return;
            }
            $data['foto_logro'] = $uploaded;
        }

        try {
            $this->model->create($data);
            $_SESSION['success'] = 'Logro registrado correctamente';
            $this->redirect('/logros');
        } catch (\Exception $e) {
            // Rollback: borrar la imagen subida si falla la inserción
            if (!empty($data['foto_logro'])) {
                FileUploader::delete($data['foto_logro'], 'img/logros');
            }
            $this->renderFormError($e->getMessage(), $data);
        }
    }

    /**
     * Formulario de edición
     * GET /logros/{id}/edit
     */
    public function edit(int $id): void
    {
        $logro = $this->model->findById($id);

        if (!$logro) {
            $_SESSION['error'] = 'Logro no encontrado';
            $this->redirect('/logros');
        }

        $this->view('logro/edit', [
            'logro' => $logro,
            'posiciones' => Logro::POSICIONES,
            'title' => 'Editar Logro',
            'pageTitle' => 'Editar Logro Institucional'
        ]);
    }

    /**
     * Actualiza un logro
     * POST /logros/{id}/edit
     */
    public function update(int $id): void
    {
        $logroActual = $this->model->findById($id);

        if (!$logroActual) {
            $_SESSION['error'] = 'Logro no encontrado';
            $this->redirect('/logros');
        }

        $data = $this->collectData();

        // --- Manejar foto_logro ---
        $eliminarFoto = isset($_POST['eliminar_foto']) && $_POST['eliminar_foto'] === '1';
        $nuevaFoto = isset($_FILES['foto_logro']) && $_FILES['foto_logro']['error'] === UPLOAD_ERR_OK;
        $fotoVieja = $logroActual['foto_logro'] ?? null;

        if ($eliminarFoto) {
            if (!empty($fotoVieja)) {
                FileUploader::delete($fotoVieja, 'img/logros');
            }
            $data['foto_logro'] = null;
        } elseif ($nuevaFoto) {
            $uploaded = FileUploader::uploadImage($_FILES['foto_logro'], 'logros');
            if (!$uploaded) {
                $this->renderFormError('Archivo de imagen inválido. Solo se permiten JPG, PNG, WebP.', $data, $logroActual, $id);
                return;
            }
            if (!empty($fotoVieja)) {
                FileUploader::delete($fotoVieja, 'img/logros');
            }
            $data['foto_logro'] = $uploaded;
        }
        // Si no se elimina ni se sube una nueva, no se incluye foto_logro:
        // el Modelo conserva la foto existente.

        try {
            $this->model->update($id, $data);
            $_SESSION['success'] = 'Logro actualizado correctamente';
            $this->redirect('/logros');
        } catch (\Exception $e) {
            // Rollback: si se subió una foto nueva, eliminarla
            if ($nuevaFoto && !empty($data['foto_logro'])) {
                FileUploader::delete($data['foto_logro'], 'img/logros');
            }
            $this->renderFormError($e->getMessage(), $data, $logroActual, $id);
        }
    }

    /**
     * Elimina un logro y su foto física
     * POST /logros/{id}/delete
     */
    public function destroy(int $id): void
    {
        $logro = $this->model->findById($id);

        if (!$logro) {
            $_SESSION['error'] = 'Logro no encontrado';
            $this->redirect('/logros');
        }

        try {
            if (!empty($logro['foto_logro'])) {
                FileUploader::delete($logro['foto_logro'], 'img/logros');
            }

            $this->model->delete($id);
            AuditLogger::logDelete('Logros', $id, (string) ($logro['nom_log'] ?? 'Logro #' . $id));
            $_SESSION['success'] = 'Logro eliminado correctamente';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al eliminar: ' . $e->getMessage();
        }

        $this->redirect('/logros');
    }

    /**
     * Alterna el estado activo/inactivo
     * POST /logros/{id}/toggle
     */
    public function toggle(int $id): void
    {
        try {
            $this->model->toggleActive($id);
            $_SESSION['success'] = 'Estado del logro actualizado';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al actualizar el estado: ' . $e->getMessage();
        }

        $this->redirect('/logros');
    }

    /* ==================== AUXILIARES ==================== */

    /**
     * Recolecta y sanitiza los campos del formulario.
     * El formulario envía solo el año (ej. "2006"); se normaliza a DATE con -01-01.
     */
    private function collectData(): array
    {
        $fechLog = trim($_POST['fech_log'] ?? '');

        // Convertir año (YYYY) a fecha completa YYYY-01-01
        if (preg_match('/^\d{4}$/', $fechLog)) {
            $fechLog .= '-01-01';
        }

        return [
            'posicion' => trim($_POST['posicion'] ?? ''),
            'nom_log' => trim($_POST['nom_log'] ?? ''),
            'categoria_logro' => trim($_POST['categoria_logro'] ?? ''),
            'lugar_log' => trim($_POST['lugar_log'] ?? ''),
            'fech_log' => $fechLog,
            'activo' => isset($_POST['activo']) ? 1 : 0,
        ];
    }

    /**
     * Vuelve al formulario con el error y los datos previos
     */
    private function renderFormError(string $message, array $old, ?array $logro = null, ?int $id = null): void
    {
        $_SESSION['error'] = $message;

        if ($id !== null) {
            $this->view('logro/edit', [
                'logro' => $logro ?? [],
                'posiciones' => Logro::POSICIONES,
                'title' => 'Editar Logro',
                'pageTitle' => 'Editar Logro Institucional',
                'old' => $old
            ]);
            return;
        }

        $this->view('logro/create', [
            'posiciones' => Logro::POSICIONES,
            'title' => 'Nuevo Logro',
            'pageTitle' => 'Nuevo Logro Institucional',
            'old' => $old
        ]);
    }
}