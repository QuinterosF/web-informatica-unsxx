<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\FileUploader;
use App\Models\Lab;
use App\Models\Academic;
use App\Models\User;

class LabController extends Controller
{
    private Lab $model;
    private Academic $academicModel;
    private User $userModel;
    private bool $ownerMode = false;
    private array $userLabIds = [];

    public function __construct()
    {
        $this->model = new Lab();
        $this->academicModel = new Academic();
        $this->userModel = new User();

        $modules = $_SESSION['user_modules'] ?? [];
        $modules = is_array($modules) ? $modules : [];

        $labIds = $_SESSION['user_lab_ids'] ?? [];
        $this->userLabIds = array_values(array_filter(
            array_map('intval', is_array($labIds) ? $labIds : []),
            static fn (int $id): bool => $id > 0
        ));

        // Modo responsable estricto: no es admin, no tiene el módulo global de
        // laboratorios, pero sí tiene laboratorios asignados.
        $this->ownerMode = !Auth::isAdmin()
            && !in_array('laboratories', $modules, true)
            && !empty($this->userLabIds);
    }

    /**
     * Bloquea el acceso a un laboratorio que no pertenece al responsable.
     */
    private function guardOwnerLab(int $id): void
    {
        if ($this->ownerMode && !in_array($id, $this->userLabIds, true)) {
            $_SESSION['error'] = 'No tienes permisos para gestionar este laboratorio.';
            $this->redirect('/labs');
        }
    }

    /**
     * Bloquea las operaciones globales (crear o eliminar laboratorios).
     */
    private function blockOwnerWrite(): void
    {
        if ($this->ownerMode) {
            $_SESSION['error'] = 'Como responsable solo puedes editar los laboratorios que tienes asignados.';
            $this->redirect('/labs');
        }
    }

    /**
     * Sincroniza la cuenta del responsable y devuelve credenciales o error.
     *
     * @return array{credentials: array{email: string, password: string}|null, error: string|null}
     */
    private function syncResponsibleAccount(int $acadId): array
    {
        if ($acadId <= 0) {
            return ['credentials' => null, 'error' => null];
        }

        try {
            return [
                'credentials' => $this->userModel->ensureLabResponsibleUser($acadId),
                'error' => null,
            ];
        } catch (\Exception $e) {
            return ['credentials' => null, 'error' => $e->getMessage()];
        }
    }

    /**
     * Mensaje de éxito con las credenciales temporales cuando corresponde.
     */
    private function labSavedMessage(string $baseMessage, array $sync): string
    {
        if ($sync['error'] !== null) {
            return $baseMessage;
        }

        if ($sync['credentials'] !== null) {
            $_SESSION['temp_credentials'] = $sync['credentials'];
            return $baseMessage . ' Cuenta del responsable creada: '
                . $sync['credentials']['email'] . ' / ' . $sync['credentials']['password'] . '.';
        }

        return $baseMessage;
    }

    /**
     * Listado de laboratorios
     * GET /labs
     */
    public function index(): void
    {
        $labs = $this->ownerMode
            ? $this->model->findByIds($this->userLabIds)
            : $this->model->all();

        $this->view('lab/index', [
            'labs' => $labs,
            'ownerMode' => $this->ownerMode,
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
        $this->blockOwnerWrite();

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
        $this->blockOwnerWrite();

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
            $sync = $this->syncResponsibleAccount((int) $data['resp_acad_id']);

            if ($sync['error'] !== null) {
                $_SESSION['error'] = "Laboratorio creado (ID: {$id}), pero no se pudo crear la cuenta del responsable: " . $sync['error'];
            } else {
                $_SESSION['success'] = $this->labSavedMessage(
                    "Laboratorio creado correctamente (ID: {$id}).",
                    $sync
                );
            }

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
        $this->guardOwnerLab($id);

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
            'ownerMode' => $this->ownerMode,
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
        $this->guardOwnerLab($id);

        $existing = $this->model->findById($id);

        if (!$existing) {
            $_SESSION['error'] = 'Laboratorio no encontrado';
            header('Location: /labs');
            exit;
        }

        // El responsable nunca puede reasignar su propio laboratorio a otro docente.
        $respAcadId = $this->ownerMode
            ? (int) ($existing['resp_acad_id'] ?? 0)
            : (int) ($_POST['resp_acad_id'] ?? 0);

        $data = [
            'nombre_lab' => trim($_POST['nombre_lab'] ?? ''),
            'resp_acad_id' => $respAcadId,
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
                        'ownerMode' => $this->ownerMode,
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
                    'ownerMode' => $this->ownerMode,
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
            $sync = $this->syncResponsibleAccount((int) $data['resp_acad_id']);

            if ($sync['error'] !== null) {
                $_SESSION['error'] = 'Laboratorio actualizado, pero no se pudo sincronizar la cuenta del responsable: ' . $sync['error'];
            } else {
                $_SESSION['success'] = $this->labSavedMessage(
                    'Laboratorio actualizado correctamente.',
                    $sync
                );
            }

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
                'ownerMode' => $this->ownerMode,
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
        $this->blockOwnerWrite();

        // 1. Buscar el laboratorio
        $lab = $this->model->findById($id);

        if (!$lab) {
            $_SESSION['error'] = 'Laboratorio no encontrado';
            header('Location: /labs');
            exit;
        }

        try {
            // 2. Buscar todas las fotos de la galería
            $photos = $this->model->getGallery($id);

            // 3. Eliminar archivos físicos de la galería
            foreach ($photos as $photo) {
                if (!empty($photo['archivo_url'])) {
                    FileUploader::delete($photo['archivo_url'], 'img/laboratorios');
                }
            }

            // 4. Borrar PDF del horario si existe
            if (!empty($lab['horario_lab'])) {
                FileUploader::delete($lab['horario_lab'], 'pdf/laboratorios');
            }

            // 5. Eliminar de la base de datos (CASCADE eliminará registros de galería)
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
     * Galería de fotos de un laboratorio
     * GET /labs/{id}/gallery
     */
    public function gallery(int $id): void
    {
        $this->guardOwnerLab($id);

        $lab = $this->model->findById($id);

        if (!$lab) {
            $_SESSION['error'] = 'Laboratorio no encontrado';
            header('Location: /labs');
            exit;
        }

        $photos = $this->model->getGallery($id);
        $this->view('lab/gallery', [
            'lab' => $lab,
            'photos' => $photos,
            'title' => 'Galería - ' . $lab['nombre_lab'],
            'pageTitle' => 'Galería de ' . $lab['nombre_lab']
        ]);
    }

    /**
     * Sube múltiples fotos a la galería
     * POST /labs/{id}/gallery/upload
     */
    public function uploadPhotos(int $id): void
    {
        $this->guardOwnerLab($id);

        $lab = $this->model->findById($id);

        if (!$lab) {
            $_SESSION['error'] = 'Laboratorio no encontrado';
            header('Location: /labs');
            exit;
        }

        if (!isset($_FILES['fotos']) || empty($_FILES['fotos']['name'][0])) {
            $_SESSION['error'] = 'No se seleccionaron archivos';
            header('Location: /labs/' . $id . '/gallery');
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
                $uploaded = FileUploader::uploadImage($file, 'laboratorios');
                if ($uploaded) {
                    $this->model->addPhoto($id, $uploaded);
                    $uploadedCount++;
                }
            }
        }

        if ($uploadedCount > 0) {
            $_SESSION['success'] = "Se subieron {$uploadedCount} foto(s) correctamente";
        } else {
            $_SESSION['error'] = 'No se pudo subir ninguna foto';
        }

        header('Location: /labs/' . $id . '/gallery');
        exit;
    }

    /**
     * Elimina una foto de la galería
     * POST /labs/{id_lab}/gallery/delete/{id_foto}
     */
    public function deletePhoto(int $id_lab, int $id_foto): void
    {
        $this->guardOwnerLab($id_lab);

        $photo = $this->model->getPhoto($id_foto);

        // La foto debe pertenecer al laboratorio indicado en la URL.
        if ($photo && (int) $photo['id_lab'] === $id_lab) {
            // Borrar archivo físico
            FileUploader::delete($photo['archivo_url'], 'img/laboratorios');
            // Borrar de BD
            $this->model->deletePhoto($id_foto);
            $_SESSION['success'] = 'Foto eliminada correctamente';
        } else {
            $_SESSION['error'] = 'Foto no encontrada para este laboratorio';
        }

        header('Location: /labs/' . $id_lab . '/gallery');
        exit;
    }

    /**
     * Establece una foto como portada
     * POST /labs/{id_lab}/gallery/cover/{id_foto}
     */
    public function setCover(int $id_lab, int $id_foto): void
    {
        $this->guardOwnerLab($id_lab);

        $photo = $this->model->getPhoto($id_foto);
        if (!$photo || (int) $photo['id_lab'] !== $id_lab) {
            $_SESSION['error'] = 'La foto no pertenece a este laboratorio.';
            header('Location: /labs/' . $id_lab . '/gallery');
            exit;
        }

        try {
            $this->model->setCoverPhoto($id_foto, $id_lab);
            $_SESSION['success'] = 'Portada actualizada correctamente';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al establecer portada: ' . $e->getMessage();
        }

        header('Location: /labs/' . $id_lab . '/gallery');
        exit;
    }
}