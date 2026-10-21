<?php

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\AcademicController;
use App\Controllers\LabController;
use App\Controllers\ExtensionController;
use App\Controllers\AnnouncementController;
use App\Controllers\AdmissionController;
use App\Controllers\OfficialDocumentController;
use App\Controllers\MentionController;
use App\Controllers\InstitutionalInfoController;
use App\Controllers\DirectoryController;
use App\Controllers\PhoneController;
use App\Controllers\ServiceLinkController;
use App\Middleware\AuthMiddleware;
use App\Controllers\PortadaController;
use App\Controllers\LogroController;
use App\Controllers\UserController;

$router = new Router();

/**
 * Middleware de módulo: exige sesión válida y acceso al módulo indicado.
 * El administrador siempre pasa; los responsables de laboratorio pasan
 * automáticamente al módulo "laboratories".
 */
$moduleGuard = static function (string ...$modules): callable {
    return static function () use ($modules): void {
        (new AuthMiddleware())->requireModule(...$modules);
    };
};

/**
 * Middleware exclusivo para administradores.
 */
$adminOnly = static function (): void {
    (new AuthMiddleware())->requireRole('admin');
};

/* ==================== RUTAS PÚBLICAS ==================== */
$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/forgot-password', [AuthController::class, 'forgotPassword']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/unauthorized', function () {
    echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
            <h2>403 - Acceso Denegado</h2>
            <p>No tienes los permisos necesarios para ver esta página.</p>
            <br>
            <a href='/dashboard' style='padding: 10px 20px; background: #007bff; color: #fff; text-decoration: none; border-radius: 5px;'>Volver a mi panel</a>
          </div>";
});

/* ==================== PANEL Y DASHBOARD ==================== */
$router->get('/dashboard', [HomeController::class, 'dashboard'])
    ->middleware([AuthMiddleware::class, 'handle']);

// Ruta heredada: el panel de administración ahora es el dashboard único.
$router->get('/admin', function () {
    header('Location: /dashboard');
    exit;
})->middleware([AuthMiddleware::class, 'handle']);

/* ==================== CRUD ACADÉMICOS ==================== */
$router->get('/academics', [AcademicController::class, 'index'])
    ->middleware($moduleGuard('academics'));
$router->get('/academics/create', [AcademicController::class, 'create'])
    ->middleware($moduleGuard('academics'));
$router->post('/academics', [AcademicController::class, 'store'])
    ->middleware($moduleGuard('academics'));

$router->get('/academics/{id}/edit', [AcademicController::class, 'edit'])
    ->middleware($moduleGuard('academics'));
$router->post('/academics/{id}/edit', [AcademicController::class, 'update'])
    ->middleware($moduleGuard('academics'));
$router->post('/academics/{id}', [AcademicController::class, 'update'])
    ->middleware($moduleGuard('academics'));
$router->post('/academics/{id}/delete', [AcademicController::class, 'destroy'])
    ->middleware($moduleGuard('academics'));
$router->post('/academics/{id}/toggle', [AcademicController::class, 'toggle'])
    ->middleware($moduleGuard('academics'));

/* ==================== CRUD LABORATORIOS ==================== */
$router->get('/labs', [LabController::class, 'index'])
    ->middleware($moduleGuard('laboratories'));
$router->get('/labs/create', [LabController::class, 'create'])
    ->middleware($moduleGuard('laboratories'));
$router->post('/labs', [LabController::class, 'store'])
    ->middleware($moduleGuard('laboratories'));

$router->get('/labs/{id}/edit', [LabController::class, 'edit'])
    ->middleware($moduleGuard('laboratories'));
$router->post('/labs/{id}/edit', [LabController::class, 'update'])
    ->middleware($moduleGuard('laboratories'));
$router->post('/labs/{id}', [LabController::class, 'update'])
    ->middleware($moduleGuard('laboratories'));
$router->post('/labs/{id}/delete', [LabController::class, 'destroy'])
    ->middleware($moduleGuard('laboratories'));

// --- Galería de Laboratorios ---
$router->get('/labs/{id}/gallery', [LabController::class, 'gallery'])
    ->middleware($moduleGuard('laboratories'));
$router->post('/labs/{id}/gallery', [LabController::class, 'uploadPhotos'])
    ->middleware($moduleGuard('laboratories'));
$router->post('/labs/{id_lab}/gallery/{id_foto}/delete', [LabController::class, 'deletePhoto'])
    ->middleware($moduleGuard('laboratories'));
$router->post('/labs/{id_lab}/gallery/{id_foto}/cover', [LabController::class, 'setCover'])
    ->middleware($moduleGuard('laboratories'));

/* ==================== CRUD EXTENSIÓN ==================== */
$router->get('/extensions', [ExtensionController::class, 'index'])
    ->middleware($moduleGuard('extensions'));
$router->get('/extensions/create', [ExtensionController::class, 'create'])
    ->middleware($moduleGuard('extensions'));
$router->post('/extensions', [ExtensionController::class, 'store'])
    ->middleware($moduleGuard('extensions'));

$router->get('/extensions/{id}/edit', [ExtensionController::class, 'edit'])
    ->middleware($moduleGuard('extensions'));
$router->post('/extensions/{id}/edit', [ExtensionController::class, 'update'])
    ->middleware($moduleGuard('extensions'));
$router->post('/extensions/{id}', [ExtensionController::class, 'update'])
    ->middleware($moduleGuard('extensions'));
$router->post('/extensions/{id}/delete', [ExtensionController::class, 'destroy'])
    ->middleware($moduleGuard('extensions'));

/* --- Categorías de Extensión --- */
$router->get('/extensions/categories', [ExtensionController::class, 'categories'])
    ->middleware($moduleGuard('extensions'));
$router->post('/extensions/categories', [ExtensionController::class, 'storeCategory'])
    ->middleware($moduleGuard('extensions'));
$router->post('/extensions/categories/{id_cat}/update', [ExtensionController::class, 'updateCategory'])
    ->middleware($moduleGuard('extensions'));
$router->post('/extensions/categories/{id_cat}/toggle', [ExtensionController::class, 'toggleCategory'])
    ->middleware($moduleGuard('extensions'));
$router->post('/extensions/categories/{id_cat}/delete', [ExtensionController::class, 'deleteCategory'])
    ->middleware($moduleGuard('extensions'));

/* --- Galería de Extensión --- */
$router->get('/extensions/{id}/gallery', [ExtensionController::class, 'gallery'])
    ->middleware($moduleGuard('extensions'));
$router->post('/extensions/{id}/gallery', [ExtensionController::class, 'uploadPhotos'])
    ->middleware($moduleGuard('extensions'));
$router->post('/extensions/{id_ext}/gallery/{id_foto}/delete', [ExtensionController::class, 'deletePhoto'])
    ->middleware($moduleGuard('extensions'));
$router->post('/extensions/{id_ext}/gallery/{id_foto}/cover', [ExtensionController::class, 'setCover'])
    ->middleware($moduleGuard('extensions'));

/* ==================== CRUD COMUNICADOS ==================== */
$router->get('/announcements', [AnnouncementController::class, 'index'])
    ->middleware($moduleGuard('communications'));
$router->get('/announcements/create', [AnnouncementController::class, 'create'])
    ->middleware($moduleGuard('communications'));
$router->post('/announcements', [AnnouncementController::class, 'store'])
    ->middleware($moduleGuard('communications'));

$router->get('/announcements/{id}/edit', [AnnouncementController::class, 'edit'])
    ->middleware($moduleGuard('communications'));
$router->post('/announcements/{id}/edit', [AnnouncementController::class, 'update'])
    ->middleware($moduleGuard('communications'));
$router->post('/announcements/{id}', [AnnouncementController::class, 'update'])
    ->middleware($moduleGuard('communications'));
$router->post('/announcements/{id}/delete', [AnnouncementController::class, 'destroy'])
    ->middleware($moduleGuard('communications'));

/* ==================== CRUD ADMISIÓN ==================== */
$router->get('/admissions', [AdmissionController::class, 'index'])
    ->middleware($moduleGuard('admissions'));
$router->get('/admissions/create', [AdmissionController::class, 'create'])
    ->middleware($moduleGuard('admissions'));
$router->post('/admissions', [AdmissionController::class, 'store'])
    ->middleware($moduleGuard('admissions'));

$router->get('/admissions/{id}/edit', [AdmissionController::class, 'edit'])
    ->middleware($moduleGuard('admissions'));
$router->post('/admissions/{id}/edit', [AdmissionController::class, 'update'])
    ->middleware($moduleGuard('admissions'));
$router->post('/admissions/{id}', [AdmissionController::class, 'update'])
    ->middleware($moduleGuard('admissions'));
$router->post('/admissions/{id}/delete', [AdmissionController::class, 'destroy'])
    ->middleware($moduleGuard('admissions'));

/* ==================== CRUD DOCUMENTOS OFICIALES ==================== */
$router->get('/documents', [OfficialDocumentController::class, 'index'])
    ->middleware($moduleGuard('documents'));
$router->get('/documents/create', [OfficialDocumentController::class, 'create'])
    ->middleware($moduleGuard('documents'));
$router->post('/documents', [OfficialDocumentController::class, 'store'])
    ->middleware($moduleGuard('documents'));

$router->get('/documents/{id}/edit', [OfficialDocumentController::class, 'edit'])
    ->middleware($moduleGuard('documents'));
$router->post('/documents/{id}/edit', [OfficialDocumentController::class, 'update'])
    ->middleware($moduleGuard('documents'));
$router->post('/documents/{id}', [OfficialDocumentController::class, 'update'])
    ->middleware($moduleGuard('documents'));
$router->post('/documents/{id}/delete', [OfficialDocumentController::class, 'destroy'])
    ->middleware($moduleGuard('documents'));

/* ==================== CRUD MENCIONES E IIDAI ==================== */
$router->get('/mentions', [MentionController::class, 'index'])
    ->middleware($moduleGuard('curricular'));
$router->get('/mentions/create', [MentionController::class, 'create'])
    ->middleware($moduleGuard('curricular'));
$router->post('/mentions', [MentionController::class, 'store'])
    ->middleware($moduleGuard('curricular'));

$router->get('/mentions/{id}/edit', [MentionController::class, 'edit'])
    ->middleware($moduleGuard('curricular'));
$router->post('/mentions/{id}/edit', [MentionController::class, 'update'])
    ->middleware($moduleGuard('curricular'));
$router->post('/mentions/{id}', [MentionController::class, 'update'])
    ->middleware($moduleGuard('curricular'));
$router->post('/mentions/{id}/delete', [MentionController::class, 'destroy'])
    ->middleware($moduleGuard('curricular'));

/* ==================== IDENTIDAD Y TEXTOS INSTITUCIONALES ==================== */
$router->get('/institutional-info', [InstitutionalInfoController::class, 'edit'])
    ->middleware($moduleGuard('institucion'));
$router->post('/institutional-info', [InstitutionalInfoController::class, 'update'])
    ->middleware($moduleGuard('institucion'));

/* ==================== DIRECTORIO Y REDES ==================== */
$router->get('/directory', [DirectoryController::class, 'index'])
    ->middleware($moduleGuard('redes'));

/* --- Celulares --- */
$router->get('/directory/phones/create', [PhoneController::class, 'create'])
    ->middleware($moduleGuard('redes'));
$router->post('/directory/phones', [PhoneController::class, 'store'])
    ->middleware($moduleGuard('redes'));

$router->get('/directory/phones/{id}/edit', [PhoneController::class, 'edit'])
    ->middleware($moduleGuard('redes'));
$router->post('/directory/phones/{id}/edit', [PhoneController::class, 'update'])
    ->middleware($moduleGuard('redes'));
$router->post('/directory/phones/{id}', [PhoneController::class, 'update'])
    ->middleware($moduleGuard('redes'));
$router->post('/directory/phones/{id}/delete', [PhoneController::class, 'destroy'])
    ->middleware($moduleGuard('redes'));

/* --- Enlaces de Servicio --- */
$router->get('/directory/links/create', [ServiceLinkController::class, 'create'])
    ->middleware($moduleGuard('redes'));
$router->post('/directory/links', [ServiceLinkController::class, 'store'])
    ->middleware($moduleGuard('redes'));

$router->get('/directory/links/{id}/edit', [ServiceLinkController::class, 'edit'])
    ->middleware($moduleGuard('redes'));
$router->post('/directory/links/{id}/edit', [ServiceLinkController::class, 'update'])
    ->middleware($moduleGuard('redes'));
$router->post('/directory/links/{id}', [ServiceLinkController::class, 'update'])
    ->middleware($moduleGuard('redes'));
$router->post('/directory/links/{id}/delete', [ServiceLinkController::class, 'destroy'])
    ->middleware($moduleGuard('redes'));

/* ==================== PORTADAS DE INICIO (CARRUSEL) ==================== */
$router->get('/portadas', [PortadaController::class, 'index'])
    ->middleware($moduleGuard('portadas'));
$router->post('/portadas', [PortadaController::class, 'upload'])
    ->middleware($moduleGuard('portadas'));
$router->post('/portadas/batch-update', [PortadaController::class, 'saveBatch'])
    ->middleware($moduleGuard('portadas'));
$router->post('/portadas/{id}/update', [PortadaController::class, 'update'])
    ->middleware($moduleGuard('portadas'));
$router->post('/portadas/{id}/delete', [PortadaController::class, 'delete'])
    ->middleware($moduleGuard('portadas'));
$router->post('/portadas/{id}/toggle', [PortadaController::class, 'toggle'])
    ->middleware($moduleGuard('portadas'));

/* ==================== LOGROS INSTITUCIONALES ==================== */
$router->get('/logros', [LogroController::class, 'index'])
    ->middleware($moduleGuard('logros'));
$router->get('/logros/create', [LogroController::class, 'create'])
    ->middleware($moduleGuard('logros'));
$router->post('/logros', [LogroController::class, 'store'])
    ->middleware($moduleGuard('logros'));
$router->get('/logros/{id}/edit', [LogroController::class, 'edit'])
    ->middleware($moduleGuard('logros'));
$router->post('/logros/{id}/edit', [LogroController::class, 'update'])
    ->middleware($moduleGuard('logros'));
$router->post('/logros/{id}/delete', [LogroController::class, 'destroy'])
    ->middleware($moduleGuard('logros'));
$router->post('/logros/{id}/toggle', [LogroController::class, 'toggle'])
    ->middleware($moduleGuard('logros'));

/* ==================== USUARIOS, ROLES Y CONTRASEÑA ==================== */
$router->get('/users', [UserController::class, 'index'])
    ->middleware($adminOnly);
$router->get('/users/create', [UserController::class, 'create'])
    ->middleware($adminOnly);
$router->post('/users', [UserController::class, 'store'])
    ->middleware($adminOnly);
$router->post('/users/sync-labs', [UserController::class, 'syncLabs'])
    ->middleware($adminOnly);
$router->get('/users/{id}/edit', [UserController::class, 'edit'])
    ->middleware($adminOnly);
$router->post('/users/{id}/edit', [UserController::class, 'update'])
    ->middleware($adminOnly);
$router->post('/users/{id}/toggle', [UserController::class, 'toggle'])
    ->middleware($adminOnly);
$router->post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])
    ->middleware($adminOnly);

$router->get('/password', [UserController::class, 'passwordForm'])
    ->middleware([AuthMiddleware::class, 'handle']);
$router->post('/password', [UserController::class, 'updatePassword'])
    ->middleware([AuthMiddleware::class, 'handle']);
