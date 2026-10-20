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

$router = new Router();

/* ==================== RUTAS PÚBLICAS ==================== */
$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/unauthorized', function() {
    echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
            <h2>403 - Acceso Denegado</h2>
            <p>No tienes los permisos necesarios para ver esta página.</p>
            <br>
            <a href='/dashboard' style='padding: 10px 20px; background: #007bff; color: #fff; text-decoration: none; border-radius: 5px;'>Volver a mi panel</a>
          </div>";
});

/* ==================== RUTAS PROTEGIDAS (requieren login) ==================== */
$router->get('/dashboard', function () {
    (new HomeController())->dashboard();
})->middleware([AuthMiddleware::class, 'handle']);

$router->get('/admin', function () {
    (new HomeController())->adminDashboard();
})->middleware(function () {
    (new AuthMiddleware())->requireRole('admin');
});

/* ==================== CRUD ACADÉMICOS (protegido) ==================== */
$router->get('/academics', [AcademicController::class, 'index'])->middleware([AuthMiddleware::class, 'handle']);
$router->get('/academics/create', [AcademicController::class, 'create'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/academics', [AcademicController::class, 'store'])->middleware([AuthMiddleware::class, 'handle']);

// Rutas con parámetro {id}
$router->get('/academics/{id}/edit', [AcademicController::class, 'edit'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/academics/{id}/edit', [AcademicController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/academics/{id}', [AcademicController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/academics/{id}/delete', [AcademicController::class, 'destroy'])->middleware([AuthMiddleware::class, 'handle']);

/* ==================== CRUD LABORATORIOS (protegido) ==================== */
$router->get('/labs', [LabController::class, 'index'])->middleware([AuthMiddleware::class, 'handle']);
$router->get('/labs/create', [LabController::class, 'create'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/labs', [LabController::class, 'store'])->middleware([AuthMiddleware::class, 'handle']);

$router->get('/labs/{id}/edit', [LabController::class, 'edit'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/labs/{id}/edit', [LabController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/labs/{id}', [LabController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/labs/{id}/delete', [LabController::class, 'destroy'])->middleware([AuthMiddleware::class, 'handle']);

// --- Galería de Laboratorios ---
$router->get('/labs/{id}/gallery', [LabController::class, 'gallery'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/labs/{id}/gallery', [LabController::class, 'uploadPhotos'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/labs/{id_lab}/gallery/{id_foto}/delete', [LabController::class, 'deletePhoto'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/labs/{id_lab}/gallery/{id_foto}/cover', [LabController::class, 'setCover'])->middleware([AuthMiddleware::class, 'handle']);

/* ==================== CRUD EXTENSIÓN (protegido) ==================== */
$router->get('/extensions', [ExtensionController::class, 'index'])->middleware([AuthMiddleware::class, 'handle']);
$router->get('/extensions/create', [ExtensionController::class, 'create'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/extensions', [ExtensionController::class, 'store'])->middleware([AuthMiddleware::class, 'handle']);

$router->get('/extensions/{id}/edit', [ExtensionController::class, 'edit'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/extensions/{id}/edit', [ExtensionController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/extensions/{id}', [ExtensionController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/extensions/{id}/delete', [ExtensionController::class, 'destroy'])->middleware([AuthMiddleware::class, 'handle']);

/* ==================== CRUD COMUNICADOS (protegido) ==================== */
$router->get('/announcements', [AnnouncementController::class, 'index'])->middleware([AuthMiddleware::class, 'handle']);
$router->get('/announcements/create', [AnnouncementController::class, 'create'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/announcements', [AnnouncementController::class, 'store'])->middleware([AuthMiddleware::class, 'handle']);

$router->get('/announcements/{id}/edit', [AnnouncementController::class, 'edit'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/announcements/{id}/edit', [AnnouncementController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/announcements/{id}', [AnnouncementController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/announcements/{id}/delete', [AnnouncementController::class, 'destroy'])->middleware([AuthMiddleware::class, 'handle']);

/* ==================== CRUD ADMISIÓN (protegido) ==================== */
$router->get('/admissions', [AdmissionController::class, 'index'])->middleware([AuthMiddleware::class, 'handle']);
$router->get('/admissions/create', [AdmissionController::class, 'create'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/admissions', [AdmissionController::class, 'store'])->middleware([AuthMiddleware::class, 'handle']);

$router->get('/admissions/{id}/edit', [AdmissionController::class, 'edit'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/admissions/{id}/edit', [AdmissionController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/admissions/{id}', [AdmissionController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/admissions/{id}/delete', [AdmissionController::class, 'destroy'])->middleware([AuthMiddleware::class, 'handle']);

/* ==================== CRUD DOCUMENTOS OFICIALES (protegido) ==================== */
$router->get('/documents', [OfficialDocumentController::class, 'index'])->middleware([AuthMiddleware::class, 'handle']);
$router->get('/documents/create', [OfficialDocumentController::class, 'create'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/documents', [OfficialDocumentController::class, 'store'])->middleware([AuthMiddleware::class, 'handle']);

$router->get('/documents/{id}/edit', [OfficialDocumentController::class, 'edit'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/documents/{id}/edit', [OfficialDocumentController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/documents/{id}', [OfficialDocumentController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/documents/{id}/delete', [OfficialDocumentController::class, 'destroy'])->middleware([AuthMiddleware::class, 'handle']);

/* ==================== CRUD MENCIONES E IIDAI (protegido) ==================== */
$router->get('/mentions', [MentionController::class, 'index'])->middleware([AuthMiddleware::class, 'handle']);
$router->get('/mentions/create', [MentionController::class, 'create'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/mentions', [MentionController::class, 'store'])->middleware([AuthMiddleware::class, 'handle']);

$router->get('/mentions/{id}/edit', [MentionController::class, 'edit'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/mentions/{id}/edit', [MentionController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/mentions/{id}', [MentionController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/mentions/{id}/delete', [MentionController::class, 'destroy'])->middleware([AuthMiddleware::class, 'handle']);

/* ==================== IDENTIDAD Y TEXTOS INSTITUCIONALES (Single Record - protegido) ==================== */
$router->get('/institutional-info', [InstitutionalInfoController::class, 'edit'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/institutional-info', [InstitutionalInfoController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);

/* ==================== DIRECTORIO Y REDES (protegido) ==================== */
$router->get('/directory', [DirectoryController::class, 'index'])->middleware([AuthMiddleware::class, 'handle']);

/* --- Celulares --- */
$router->get('/directory/phones/create', [PhoneController::class, 'create'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/directory/phones', [PhoneController::class, 'store'])->middleware([AuthMiddleware::class, 'handle']);

$router->get('/directory/phones/{id}/edit', [PhoneController::class, 'edit'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/directory/phones/{id}/edit', [PhoneController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/directory/phones/{id}', [PhoneController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/directory/phones/{id}/delete', [PhoneController::class, 'destroy'])->middleware([AuthMiddleware::class, 'handle']);

/* --- Enlaces de Servicio --- */
$router->get('/directory/links/create', [ServiceLinkController::class, 'create'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/directory/links', [ServiceLinkController::class, 'store'])->middleware([AuthMiddleware::class, 'handle']);

$router->get('/directory/links/{id}/edit', [ServiceLinkController::class, 'edit'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/directory/links/{id}/edit', [ServiceLinkController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/directory/links/{id}', [ServiceLinkController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/directory/links/{id}/delete', [ServiceLinkController::class, 'destroy'])->middleware([AuthMiddleware::class, 'handle']);

/* ==================== PORTADAS DE INICIO (CARRUSEL) ==================== */
$router->get('/portadas', [PortadaController::class, 'index'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/portadas', [PortadaController::class, 'upload'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/portadas/{id}/update', [PortadaController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/portadas/{id}/delete', [PortadaController::class, 'delete'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/portadas/{id}/toggle', [PortadaController::class, 'toggle'])->middleware([AuthMiddleware::class, 'handle']);

/* ==================== FUTUROS MÓDULOS (placeholder) ==================== */
// Extensión
// $router->get('/extension', ...);
// Comunicados
// $router->get('/comunicados', ...);
// Admisión
// $router->get('/admision', ...);
// Configuración Inicio
// $router->get('/config', ...);