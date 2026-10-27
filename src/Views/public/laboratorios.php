<?php
$layout = $layout ?? [];
$labs = $labs ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Laboratorios') ?></title>
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
        <h1>Portal Público - Laboratorios</h1>
        <p>Laboratorios activos: <?= count($labs) ?></p>
        <ul>
            <?php foreach ($labs as $lab): ?>
                <li>
                    <?= htmlspecialchars($lab['nombre_lab'] ?? '') ?>
                    (<?= count($lab['fotos'] ?? []) ?> fotos)
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>
