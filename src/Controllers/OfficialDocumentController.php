<?php

namespace App\Controllers;

use App\Core\AuditLogger;
use App\Core\Controller;
use App\Core\FileUploader;
use App\Models\OfficialDocument;

class OfficialDocumentController extends Controller
{
    private OfficialDocument $model;

    public function __construct()
    {
        $this->model = new OfficialDocument();
    }

    /**
     * Listado de documentos oficiales
     * GET /documents
     */
    public function index(): void
    {
        $documents = $this->model->all();
        $this->view('document/index', [
            'documents' => $documents,
            'title' => 'Documentos Oficiales - Ingeniería Informática UNSXX',
            'pageTitle' => 'Documentos Oficiales'
        ]);
    }

    /**
     * Formulario de creación
     * GET /documents/create
     */
    public function create(): void
    {
        $this->view('document/create', [
            'title' => 'Nuevo Documento Oficial',
            'pageTitle' => 'Nuevo Documento Oficial'
        ]);
    }

    /**
     * Guarda un nuevo documento oficial
     * POST /documents
     */
    public function store(): void
    {
        // Validar archivo PDF obligatorio
        if (!isset($_FILES['archivo_pdf']) || $_FILES['archivo_pdf']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'El archivo PDF es obligatorio';
            $this->view('document/create', [
                'title' => 'Nuevo Documento Oficial',
                'pageTitle' => 'Nuevo Documento Oficial',
                'old' => $_POST
            ]);
            return;
        }

        // 1. SUBIDA: Usa la subcarpeta 'documents' (Se guardará en uploads/pdf/documents/)
        $filename = FileUploader::uploadPdf($_FILES['archivo_pdf'], 'documents');
        if (!$filename) {
            $_SESSION['error'] = 'Archivo inválido. Solo se permiten PDFs.';
            $this->view('document/create', [
                'title' => 'Nuevo Documento Oficial',
                'pageTitle' => 'Nuevo Documento Oficial',
                'old' => $_POST
            ]);
            return;
        }

        $allowedCategories = ['plan_estudios', 'horario_clases', 'reglamento_titulacion', 'reglamento_interno', 'otro'];
        $categoria = in_array($_POST['categoria'] ?? '', $allowedCategories, true) ? $_POST['categoria'] : 'otro';

        $data = [
            'titulo' => trim($_POST['titulo'] ?? ''),
            'categoria' => $categoria,
            'archivo_pdf' => $filename,
            'gestion' => (int)($_POST['gestion'] ?? 0),
            'estado' => isset($_POST['estado']) ? 1 : 0,
            'subido_por' => (int)($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1),
        ];

        try {
            $id = $this->model->create($data);
            $_SESSION['success'] = "Documento creado correctamente (ID: {$id})";
            header('Location: /documents');
            exit;
        } catch (\Exception $e) {
            // 2. ROLLBACK: Usa la ruta relativa completa 'pdf/documents'
            FileUploader::delete($filename, 'pdf/documents');
            $_SESSION['error'] = $e->getMessage();
            $this->view('document/create', [
                'title' => 'Nuevo Documento Oficial',
                'pageTitle' => 'Nuevo Documento Oficial',
                'old' => $_POST
            ]);
        }
    }

    /**
     * Formulario de edición
     * GET /documents/{id}/edit
     */
    public function edit(int $id): void
    {
        $document = $this->model->findById($id);

        if (!$document) {
            $_SESSION['error'] = 'Documento no encontrado';
            header('Location: /documents');
            exit;
        }

        $this->view('document/edit', [
            'document' => $document,
            'title' => 'Editar Documento Oficial',
            'pageTitle' => 'Editar Documento Oficial'
        ]);
    }

    /**
     * Actualiza un documento oficial
     * POST /documents/{id}/edit
     * POST /documents/{id}
     */
    public function update(int $id): void
    {
        $existing = $this->model->findById($id);

        if (!$existing) {
            $_SESSION['error'] = 'Documento no encontrado';
            header('Location: /documents');
            exit;
        }

        $oldFilename = $existing['archivo_pdf'];
        $newFilename = $oldFilename;

        // Manejar subida de nuevo PDF (opcional)
        if (isset($_FILES['archivo_pdf']) && $_FILES['archivo_pdf']['error'] === UPLOAD_ERR_OK) {
            // 3. SUBIDA EN UPDATE: Añadimos 'documents'
            $uploadedFilename = FileUploader::uploadPdf($_FILES['archivo_pdf'], 'documents');
            if (!$uploadedFilename) {
                $_SESSION['error'] = 'Archivo inválido. Solo se permiten PDFs.';
                $this->view('document/edit', [
                    'document' => $existing,
                    'title' => 'Editar Documento Oficial',
                    'pageTitle' => 'Editar Documento Oficial',
                    'old' => $_POST
                ]);
                return;
            }
            $newFilename = $uploadedFilename;

            // Eliminar archivo anterior si existe
            if ($oldFilename) {
                // 4. BORRADO DE ARCHIVO VIEJO: Usa 'pdf/documents'
                FileUploader::delete($oldFilename, 'pdf/documents');
            }
        }

        $allowedCategories = ['plan_estudios', 'horario_clases', 'reglamento_titulacion', 'reglamento_interno', 'otro'];
        $categoria = in_array($_POST['categoria'] ?? '', $allowedCategories, true) ? $_POST['categoria'] : 'otro';

        $data = [
            'titulo' => trim($_POST['titulo'] ?? ''),
            'categoria' => $categoria,
            'archivo_pdf' => $newFilename,
            'gestion' => (int)($_POST['gestion'] ?? 0),
            'estado' => isset($_POST['estado']) ? 1 : 0,
            'subido_por' => (int)($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1),
        ];

        try {
            $this->model->update($id, $data);
            $_SESSION['success'] = 'Documento actualizado correctamente';
            header('Location: /documents');
            exit;
        } catch (\Exception $e) {
            // Si falla la BD y se subió archivo nuevo, eliminar el nuevo
            if ($newFilename !== $oldFilename) {
                // 5. ROLLBACK EN UPDATE: Usa 'pdf/documents'
                FileUploader::delete($newFilename, 'pdf/documents');
            }
            $_SESSION['error'] = $e->getMessage();
            $this->view('document/edit', [
                'document' => $existing,
                'title' => 'Editar Documento Oficial',
                'pageTitle' => 'Editar Documento Oficial',
                'old' => $_POST
            ]);
        }
    }

    /**
     * Elimina un documento oficial
     * POST /documents/{id}/delete
     */
    public function destroy(int $id): void
    {
        try {
            $existing = $this->model->findById($id);

            if ($existing && $existing['archivo_pdf']) {
                // 6. BORRADO FÍSICO AL ELIMINAR REGISTRO: Usa 'pdf/documents'
                FileUploader::delete($existing['archivo_pdf'], 'pdf/documents');
            }

            $deleted = $this->model->delete($id);

            if ($deleted) {
                AuditLogger::logDelete('Documentos', $id, (string) ($existing['titulo'] ?? 'Documento #' . $id));
                $_SESSION['success'] = 'Documento eliminado correctamente';
            } else {
                $_SESSION['error'] = 'El documento no existe';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error al eliminar: ' . $e->getMessage();
        }

        header('Location: /documents');
        exit;
    }
}