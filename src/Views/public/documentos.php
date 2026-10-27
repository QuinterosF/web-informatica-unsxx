<?php
$layout = $layout ?? [];
$documents = $documents ?? [];
$admissions = $admissions ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Documentos Oficiales') ?></title>
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
        <h1>Portal Público - Documentos Oficiales</h1>
        <p>Documentos publicados: <?= count($documents) ?></p>
        <p>Procesos de admisión: <?= count($admissions) ?></p>
        <ul>
            <?php foreach ($documents as $document): ?>
                <li><?= htmlspecialchars($document['titulo'] ?? '') ?></li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>
