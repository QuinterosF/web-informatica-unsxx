<?php

namespace App\Controllers;

use App\Core\AuditLogger;
use App\Core\Controller;
use App\Core\FileUploader;
use App\Models\Mention;
use App\Models\Academic;

class MentionController extends Controller
{
    private Mention $model;
    private Academic $academicModel;

    public function __construct()
    {
        $this->model = new Mention();
        $this->academicModel = new Academic();
    }

    /**
     * Listado de menciones/institutos
     * GET /mentions
     */
    public function index(): void
    {
        $mentions = $this->model->all();
        $this->view('mention/index', [
            'mentions' => $mentions,
            'title' => 'Menciones e IIDAI - Ingeniería Informática UNSXX',
            'pageTitle' => 'Menciones e IIDAI'
        ]);
    }

    /**
     * Formulario de creación
     * GET /mentions/create
     */
    public function create(): void
    {
        $coordinators = $this->academicModel->getCoordinators();
        $this->view('mention/create', [
            'coordinators' => $coordinators,
            'title' => 'Nueva Mención / Instituto',
            'pageTitle' => 'Nueva Mención / Instituto'
        ]);
    }

    /**
     * Guarda una nueva mención/instituto
     * POST /mentions
     */
    public function store(): void
    {
        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'perfil_profesional' => trim($_POST['perfil_profesional'] ?? ''),
            'coordinador' => !empty($_POST['coordinador']) ? (int)$_POST['coordinador'] : null,
            'estado' => isset($_POST['estado']) ? 1 : 0,
            'creado_por' => (int)($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1),
            'escudo_url' => 'default.png'
        ];

        $uploadedFilename = null;

        // Manejar archivo de escudo (OPCIONAL)
        if (isset($_FILES['escudo_url']) && $_FILES['escudo_url']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['escudo_url']['error'] === UPLOAD_ERR_OK) {
                try {
                    $uploadedFilename = $this->uploadShield($_FILES['escudo_url']);
                    $data['escudo_url'] = $uploadedFilename;
                } catch (\Exception $e) {
                    $coordinators = $this->academicModel->getCoordinators();
                    $_SESSION['error'] = $e->getMessage();
                    $this->view('mention/create', [
                        'coordinators' => $coordinators,
                        'title' => 'Nueva Mención / Instituto',
                        'pageTitle' => 'Nueva Mención / Instituto',
                        'old' => $data
                    ]);
                    return;
                }
            } else {
                $coordinators = $this->academicModel->getCoordinators();
                $_SESSION['error'] = 'Error al subir el archivo: ' . $_FILES['escudo_url']['error'];
                $this->view('mention/create', [
                    'coordinators' => $coordinators,
                    'title' => 'Nueva Mención / Instituto',
                    'pageTitle' => 'Nueva Mención / Instituto',
                    'old' => $data
                ]);
                return;
            }
        }

        try {
            $id = $this->model->create($data);
            $_SESSION['success'] = "Mención/Instituto creado correctamente (ID: {$id})";
            header('Location: /mentions');
            exit;
        } catch (\Exception $e) {
            // Rollback: eliminar archivo subido si falla la BD
            if ($uploadedFilename) {
                $this->deleteShield($uploadedFilename);
            }
            $coordinators = $this->academicModel->getCoordinators();
            $_SESSION['error'] = $e->getMessage();
            $this->view('mention/create', [
                'coordinators' => $coordinators,
                'title' => 'Nueva Mención / Instituto',
                'pageTitle' => 'Nueva Mención / Instituto',
                'old' => $data
            ]);
        }
    }

    /**
     * Formulario de edición
     * GET /mentions/{id}/edit
     */
    public function edit(int $id): void
    {
        $mention = $this->model->findById($id);

        if (!$mention) {
            $_SESSION['error'] = 'Mención/Instituto no encontrado';
            header('Location: /mentions');
            exit;
        }

        $coordinators = $this->academicModel->getCoordinators();
        $this->view('mention/edit', [
            'mention' => $mention,
            'coordinators' => $coordinators,
            'title' => 'Editar Mención / Instituto',
            'pageTitle' => 'Editar Mención / Instituto'
        ]);
    }

    /**
     * Actualiza una mención/instituto
     * POST /mentions/{id}/edit
     */
    public function update(int $id): void
    {
        $existing = $this->model->findById($id);

        if (!$existing) {
            $_SESSION['error'] = 'Mención/Instituto no encontrado';
            header('Location: /mentions');
            exit;
        }

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'perfil_profesional' => trim($_POST['perfil_profesional'] ?? ''),
            'coordinador' => !empty($_POST['coordinador']) ? (int)$_POST['coordinador'] : null,
            'estado' => isset($_POST['estado']) ? 1 : 0,
        ];

        $escudoUrl = $existing['escudo_url'] ?? '';
        $removeEscudo = isset($_POST['remove_escudo_url']) && $_POST['remove_escudo_url'] === '1';

        if (isset($_FILES['escudo_url']) && $_FILES['escudo_url']['error'] === UPLOAD_ERR_OK) {
            $uploaded = \App\Core\FileUploader::uploadImage($_FILES['escudo_url'], 'menciones');
            if ($uploaded) {
                if ($escudoUrl) \App\Core\FileUploader::delete($escudoUrl, 'img/menciones');
                $escudoUrl = $uploaded;
            } else {
                $coordinators = $this->academicModel->getCoordinators();
                $_SESSION['error'] = 'Archivo de imagen inválido. Solo se permiten JPG, PNG, WebP.';
                $this->view('mention/edit', [
                    'mention' => $existing,
                    'coordinators' => $coordinators,
                    'title' => 'Editar Mención / Instituto',
                    'pageTitle' => 'Editar Mención / Instituto',
                    'old' => $_POST
                ]);
                return;
            }
        } elseif ($removeEscudo) {
            if ($escudoUrl) \App\Core\FileUploader::delete($escudoUrl, 'img/menciones');
            $escudoUrl = null;
        }
        $data['escudo_url'] = $escudoUrl;

        try {
            $this->model->update($id, $data);
            $_SESSION['success'] = 'Mención/Instituto actualizado correctamente';
            header('Location: /mentions');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $coordinators = $this->academicModel->getCoordinators();
            $this->view('mention/edit', [
                'mention' => $existing,
                'coordinators' => $coordinators,
                'title' => 'Editar Mención / Instituto',
                'pageTitle' => 'Editar Mención / Instituto',
                'old' => $_POST
            ]);
        }
    }

    /**
     * Elimina una mención/instituto
     * POST /mentions/{id}/delete
     */
    public function destroy(int $id): void
    {
        try {
            $existing = $this->model->findById($id);

            $deleted = $this->model->delete($id);

            if ($deleted) {
                // Borrar archivo de escudo físico si existe y no es default
                if ($existing && $existing['escudo_url'] && $existing['escudo_url'] !== 'default.png') {
                    $this->deleteShield($existing['escudo_url']);
                }
                AuditLogger::logDelete('Menciones', $id, (string) ($existing['nombre'] ?? 'Mención #' . $id));
                $_SESSION['success'] = 'Mención/Instituto eliminado correctamente';
            } else {
                $_SESSION['error'] = 'La mención/instituto no existe';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al eliminar: ' . $e->getMessage();
        }

        header('Location: /mentions');
        exit;
    }

    /**
     * Sube el escudo de la mención (OPCIONAL)
     * Valida MIME type (JPG, PNG, WEBP), genera UUID, guarda en public/uploads/img/menciones/
     *
     * @throws \Exception si validación falla
     */
    private function uploadShield(array $file): string
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

        $filename = uniqid('men_') . '.' . $extension;
        $destinationDir = __DIR__ . '/../../public/uploads/img/menciones/';

        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $destinationPath = $destinationDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destinationPath)) {
            throw new \Exception('Error al guardar el archivo en el servidor');
        }

        return $filename;
    }

    /**
     * Elimina el escudo físico del disco
     */
    private function deleteShield(string $filename): void
    {
        $filepath = __DIR__ . '/../../public/uploads/img/menciones/' . $filename;

        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
}