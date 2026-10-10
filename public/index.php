<?php
session_start();

/**
 * Front Controller - Punto de entrada único
 * Ubicación: public/index.php
 */

// ¡TRUCO MAGICO! 
// Forzamos a PHP a que el "directorio de trabajo" sea la raíz del proyecto.
// Esto soluciona instantáneamente el error de "Vista no encontrada".
chdir(dirname(__DIR__));

// 1. Autoload PSR-4
require_once __DIR__ . '/../src/autoload.php';

// 2. Cargar variables de entorno
if (class_exists('\App\Config\EnvLoader')) {
    // Volvemos a llamarlo SIN parámetros, tal cual lo tenías en tu código original.
    // O si tu clase lo requiere, usa: \App\Config\EnvLoader::load(__DIR__ . '/../.env');
    \App\Config\EnvLoader::load(); 
}

// 3. Configuración
date_default_timezone_set('America/La_Paz');

// 4. Enrutamiento MVC
$router = new \App\Core\Router();
require_once __DIR__ . '/../src/routes.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);