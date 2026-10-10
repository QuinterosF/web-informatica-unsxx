<?php
$title = 'Dashboard - Ingeniería Informática UNSXX';
$pageTitle = 'Dashboard';
$content = <<<'HTML'
<div class="text-center">
    <h2 class="text-3xl font-bold text-unsxx-blue-900 mb-2">Bienvenido, <?= htmlspecialchars($user['name'] ?? 'Usuario') ?></h2>
    <p class="text-unsxx-blue-600 mb-8">Has iniciado sesión correctamente en el sistema.</p>
    <div class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-unsxx-blue-200 rounded-xl shadow-sm">
        <svg class="w-6 h-6 text-unsxx-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-unsxx-blue-700 font-medium">Dashboard en construcción</span>
    </div>
</div>
HTML;
require __DIR__ . '/../layouts/app.php';