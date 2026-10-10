<?php
/**
 * Emulador de Servidor Web (server.php)
 * Raíz del proyecto
 */
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$requestedFile = __DIR__ . '/public' . $uri;

// 1. Entregar archivos físicos directamente (PDFs, CSS, JS)
if ($uri !== '/' && is_file($requestedFile)) {
    $ext = strtolower(pathinfo($requestedFile, PATHINFO_EXTENSION));
    $mimes = [
        'pdf'  => 'application/pdf',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'svg'  => 'image/svg+xml'
    ];
    
    header('Content-Type: ' . ($mimes[$ext] ?? 'application/octet-stream'));
    readfile($requestedFile);
    exit;
}

// 2. Cargar el controlador principal MVC para todo lo demás
require_once __DIR__ . '/public/index.php';