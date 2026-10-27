<?php
$layout = $layout ?? [];
$academics = $academics ?? [];
$authorities = $academics['authorities'] ?? [];
$teachers = $academics['teachers'] ?? [];
$categories = $academics['categories'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Plantel Docente') ?></title>
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
        <h1>Portal Público - Plantel Docente</h1>
        <p>Categorías: <?= count($categories) ?></p>
        <p>Autoridades: <?= count($authorities) ?></p>
        <p>Docentes: <?= count($teachers) ?></p>
        <ul>
            <?php foreach ($authorities as $authority): ?>
                <li><?= htmlspecialchars($authority['nom_acad'] ?? '') ?> - <?= htmlspecialchars($authority['cat_nombre'] ?? '') ?></li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>
