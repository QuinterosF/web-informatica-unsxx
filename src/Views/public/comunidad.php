<?php
$layout = $layout ?? [];
$announcements = $announcements ?? [];
$extensions = $extensions ?? [];
$extensionCategories = $extensionCategories ?? [];
$logros = $logros ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Comunidad') ?></title>
</head>
<body>
    <header>
        <strong>Ingeniería Informática - UNSXX</strong>
        <nav>
            <a href="/">Inicio</a>
            <a href="/carrera">Carrera</a>
            <a href="/plantel">Plantel Docente</a>
            <a href="/laboratorios">Laboratorios</a>
            <a href="/comunidad">Comunidad</a>
            <a href="/documentos">Documentos</a>
            <a href="/login">Acceso</a>
        </nav>
    </header>
    <main>
        <h1>Portal Público - Comunidad</h1>
        <p>Comunicados: <?= count($announcements) ?></p>
        <p>Eventos: <?= count($extensions) ?></p>
        <p>Categorías de extensión: <?= count($extensionCategories) ?></p>
        <p>Logros: <?= count($logros) ?></p>
    </main>
</body>
</html>
