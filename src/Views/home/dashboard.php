<?php
$title = 'Dashboard - Ingeniería Informática UNSXX';
$pageTitle = $isAdmin ? 'Panel de Control' : 'Mi Estación de Trabajo';

// Paleta enriquecida para tarjetas y estados
$cardStyles = [
    'blue'   => ['bg' => 'bg-blue-50/70',   'text' => 'text-unsxx-blue-700', 'border' => 'border-blue-200',   'iconBg' => 'bg-blue-100/80',   'bar' => 'bg-unsxx-blue-600'],
    'teal'   => ['bg' => 'bg-teal-50/70',   'text' => 'text-teal-700',       'border' => 'border-teal-200',   'iconBg' => 'bg-teal-100/80',   'bar' => 'bg-teal-600'],
    'amber'  => ['bg' => 'bg-amber-50/70',  'text' => 'text-amber-700',      'border' => 'border-amber-200',  'iconBg' => 'bg-amber-100/80',  'bar' => 'bg-amber-500'],
    'green'  => ['bg' => 'bg-green-50/70',  'text' => 'text-green-700',      'border' => 'border-green-200',  'iconBg' => 'bg-green-100/80',  'bar' => 'bg-green-600'],
    'purple' => ['bg' => 'bg-purple-50/70', 'text' => 'text-purple-700',     'border' => 'border-purple-200', 'iconBg' => 'bg-purple-100/80', 'bar' => 'bg-purple-600'],
];

// Selector inteligente de iconos SVG según la ruta o nombre del módulo
function getDashboardIconSvg(string $identifier): string {
    $key = strtolower($identifier);
    if (str_contains($key, 'academic') || str_contains($key, 'docente')) {
        return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />';
    }
    if (str_contains($key, 'lab')) {
        return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />';
    }
    if (str_contains($key, 'extens') || str_contains($key, 'evento')) {
        return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />';
    }
    if (str_contains($key, 'announc') || str_contains($key, 'comunicado')) {
        return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />';
    }
    if (str_contains($key, 'logro')) {
        return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />';
    }
    if (str_contains($key, 'doc')) {
        return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />';
    }
    if (str_contains($key, 'portada')) {
        return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />';
    }
    if (str_contains($key, 'user') || str_contains($key, 'usuario')) {
        return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />';
    }
    return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />';
}

// Badge para el tipo de acción en la tabla de auditoría
function getAuditActionBadge(string $accion): string {
    $lower = strtolower($accion);
    if (str_contains($lower, 'crea') || str_contains($lower, 'subi') || str_contains($lower, 'public') || str_contains($lower, 'registr')) {
        return 'bg-green-50 text-green-700 border-green-200';
    }
    if (str_contains($lower, 'actualiz') || str_contains($lower, 'edit') || str_contains($lower, 'modific')) {
        return 'bg-amber-50 text-amber-800 border-amber-200';
    }
    return 'bg-gray-100 text-gray-700 border-gray-200';
}

$meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$fechaHoy = date('d') . ' de ' . $meses[(int)date('n') - 1] . ', ' . date('Y');

ob_start();
?>

<!-- 1. BANNER HERO INSTITUCIONAL -->
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-unsxx-blue-900 via-unsxx-blue-800 to-blue-900 text-white shadow-md mb-8 border border-unsxx-blue-700">
    <!-- Decoración geométrica sutil -->
    <div class="absolute -right-10 -top-10 w-44 h-44 rounded-full bg-unsxx-yellow-400/10 blur-2xl pointer-events-none"></div>
    <div class="absolute right-24 -bottom-12 w-36 h-36 rounded-full bg-blue-400/10 blur-xl pointer-events-none"></div>

    <div class="relative p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-xs font-semibold text-unsxx-yellow-400 mb-3">
                <span><?= $isAdmin ? '🛡️ Modo Administrador General' : '⚙️ Panel de Operador Institucional' ?></span>
                <span class="text-white/40">•</span>
                <span class="text-blue-100 font-normal"><?= $fechaHoy ?></span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                ¡Hola, <?= htmlspecialchars($_SESSION['user_name'] ?? ($user['name'] ?? 'Usuario')) ?>!
            </h2>
            <p class="text-sm text-blue-100/90 mt-1 max-w-2xl">
                <?= $isAdmin
                    ? 'Supervisa en tiempo real las métricas del portal web de Ingeniería Informática, el estado de las publicaciones y el registro histórico de auditoría.'
                    : 'Bienvenido a tu estación de trabajo. Desde aquí puedes administrar directamente los módulos y laboratorios asignados a tu cuenta.' ?>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <a href="/" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs font-semibold transition-all">
                <svg class="w-4 h-4 text-unsxx-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                Ver Portal Público
            </a>
            <a href="/password"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 text-xs font-bold shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                Seguridad / Contraseña
            </a>
        </div>
    </div>
</div>

<?php if ($isAdmin): ?>

    <!-- ALERTA CRÍTICA DE SINCRONIZACIÓN DE LABORATORIOS (Si existen responsables sin usuario) -->
    <?php if (!empty($pendingLabAccounts)): ?>
        <div class="mb-8 p-5 bg-red-50 border-l-4 border-red-600 rounded-xl shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="p-2.5 rounded-xl bg-red-100 text-red-700 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.054 0 1.658-1.14 1.094-2.006L13.094 4.11a1.25 1.25 0 00-2.188 0L4.988 16.994C4.424 17.86 5.028 19 6.082 19z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-red-900">Responsables de Laboratorio pendientes de habilitación</h3>
                    <p class="text-xs text-red-700 mt-0.5">
                        Detectamos <strong><?= (int)$pendingLabAccounts ?> docente(s)</strong> asignados como responsables de laboratorio que aún no tienen cuenta de usuario activa para subir sus horarios PDF.
                    </p>
                </div>
            </div>
            <form action="/users/sync-labs" method="POST" class="m-0 shrink-0">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors cursor-pointer">
                    ⚡ Sincronizar y Crear Cuentas
                </button>
            </form>
        </div>
    <?php endif; ?>

    <!-- 2. TARJETAS KPI GLOBALES -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
        <?php foreach ($stats as $stat): ?>
            <?php $st = $cardStyles[$stat['color'] ?? 'blue'] ?? $cardStyles['blue']; ?>
            <a href="<?= htmlspecialchars($stat['url']) ?>"
                class="group relative overflow-hidden bg-white border border-gray-200/80 hover:border-unsxx-blue-300 rounded-2xl p-4 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                <div class="flex items-start justify-between gap-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500 group-hover:text-unsxx-blue-800 transition-colors leading-tight">
                        <?= htmlspecialchars($stat['label']) ?>
                    </span>
                    <div class="w-9 h-9 rounded-xl <?= $st['iconBg'] ?> <?= $st['text'] ?> flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <?= getDashboardIconSvg($stat['url'] . ' ' . $stat['label']) ?>
                        </svg>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-3xl font-extrabold text-unsxx-blue-900 tracking-tight"><?= (int)$stat['value'] ?></p>
                    <p class="text-[11px] text-gray-500 mt-1 flex items-center justify-between">
                        <span><?= htmlspecialchars($stat['sub']) ?></span>
                        <span class="<?= $st['text'] ?> font-bold opacity-0 group-hover:opacity-100 transition-opacity">→</span>
                    </p>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 <?= $st['bar'] ?> opacity-80"></div>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- 3. LAYOUT EJECUTIVO DE 2 COLUMNAS: AUDITORÍA RECIENTE (2/3) + SALUD Y ACCIONES (1/3) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        <!-- COLUMNA IZQUIERDA (2/3): TABLA DE AUDITORÍA E HISTORIAL RECIENTE -->
        <div class="lg:col-span-2 bg-white border border-unsxx-blue-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-unsxx-blue-900 text-white flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-unsxx-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider">Registro de Auditoría Reciente</h3>
                        <p class="text-[11px] text-blue-200">Trazabilidad histórica de creaciones y modificaciones por usuario</p>
                    </div>
                </div>
                <a href="/users" class="text-xs font-semibold text-unsxx-yellow-400 hover:underline">Ver usuarios →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[11px] text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3">Módulo / Acción</th>
                            <th class="px-4 py-3">Registro Afectado</th>
                            <th class="px-4 py-3">Autor / Editor</th>
                            <th class="px-4 py-3 text-right">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($audit)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-400 text-sm">
                                    Todavía no se registran movimientos recientes en la base de datos.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($audit as $item): ?>
                                <tr class="hover:bg-blue-50/40 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-unsxx-blue-50 text-unsxx-blue-900 border border-unsxx-blue-200">
                                            <?= htmlspecialchars($item['modulo']) ?>
                                        </span>
                                        <span class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border <?= getAuditActionBadge($item['accion'] ?? '') ?>">
                                            <?= htmlspecialchars($item['accion']) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="<?= htmlspecialchars($item['url']) ?>" class="font-semibold text-gray-800 hover:text-unsxx-blue-600 hover:underline line-clamp-1">
                                            <?= htmlspecialchars($item['titulo'] ?: 'Registro #' . ($item['id'] ?? '')) ?>
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-700">
                                            <span class="w-5 h-5 rounded-full bg-unsxx-blue-100 text-unsxx-blue-800 font-bold text-[10px] flex items-center justify-center">
                                                <?= strtoupper(substr(trim($item['usuario'] ?? 'S'), 0, 1)) ?>
                                            </span>
                                            <?= htmlspecialchars($item['usuario']) ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-500 text-right font-mono whitespace-nowrap">
                                        <?= htmlspecialchars($item['fecha']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- COLUMNA DERECHA (1/3): SALUD DEL CONTENIDO + OPERACIONES RÁPIDAS -->
        <div class="space-y-6">

            <!-- Tarjeta de Salud / Alertas de Contenido -->
            <div class="bg-white border border-unsxx-blue-100 rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between gap-2 mb-4 pb-3 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-unsxx-blue-900">Pendientes del Contenido</h3>
                        <p class="text-xs text-gray-500">Elementos que requieren atención</p>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-bold rounded-full <?= empty($alerts) ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-900' ?>">
                        <?= count($alerts) ?> pendiente(s)
                    </span>
                </div>

                <?php if (empty($alerts)): ?>
                    <div class="p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3 text-green-800">
                        <span class="text-xl">✅</span>
                        <div class="text-xs">
                            <p class="font-bold">¡Portal al 100%!</p>
                            <p class="text-green-700 mt-0.5">Todos los eventos y laboratorios cuentan con sus archivos y galerías completas.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="space-y-2.5">
                        <?php foreach ($alerts as $alert): ?>
                            <?php $isDanger = ($alert['severity'] ?? 'warning') === 'danger'; ?>
                            <a href="<?= htmlspecialchars($alert['url']) ?>"
                                class="flex items-center justify-between gap-3 p-3 rounded-xl border <?= $isDanger ? 'border-red-200 bg-red-50/70 hover:bg-red-100/80' : 'border-amber-200 bg-amber-50/70 hover:bg-amber-100/80' ?> transition-colors">
                                <div class="flex items-start gap-2.5 min-w-0">
                                    <span class="text-sm mt-0.5"><?= $isDanger ? '🚨' : '⚠️' ?></span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold <?= $isDanger ? 'text-red-900' : 'text-amber-900' ?> truncate">
                                            <?= htmlspecialchars($alert['label']) ?>
                                        </p>
                                        <p class="text-[11px] <?= $isDanger ? 'text-red-700' : 'text-amber-700' ?>">
                                            <?= (int)$alert['count'] ?> registro(s) incompleto(s)
                                        </p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold <?= $isDanger ? 'text-red-800' : 'text-amber-800' ?> shrink-0">Corregir →</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tarjeta de Acciones Rápidas Frecuentes -->
            <div class="bg-white border border-unsxx-blue-100 rounded-2xl shadow-sm p-5">
                <h3 class="text-base font-bold text-unsxx-blue-900 mb-1">Publicación Rápida</h3>
                <p class="text-xs text-gray-500 mb-4">Atajos directos a las operaciones más frecuentes</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-2.5">
                    <a href="/extensions/create" class="flex items-center justify-between p-3 rounded-xl border border-gray-200 hover:border-unsxx-blue-400 hover:bg-blue-50/50 transition-all group">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm">📸</span>
                            <div>
                                <p class="text-xs font-bold text-gray-800 group-hover:text-unsxx-blue-900">Publicar Evento / Noticia</p>
                                <p class="text-[11px] text-gray-400">Extensión, ICPC, FLISoL, Congreso</p>
                            </div>
                        </div>
                        <span class="text-gray-400 group-hover:text-unsxx-blue-700 font-bold">+</span>
                    </a>

                    <a href="/announcements/create" class="flex items-center justify-between p-3 rounded-xl border border-gray-200 hover:border-unsxx-blue-400 hover:bg-blue-50/50 transition-all group">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm">📢</span>
                            <div>
                                <p class="text-xs font-bold text-gray-800 group-hover:text-unsxx-blue-900">Emitir Comunicado Oficial</p>
                                <p class="text-[11px] text-gray-400">Avisos con afiche o documento PDF</p>
                            </div>
                        </div>
                        <span class="text-gray-400 group-hover:text-unsxx-blue-700 font-bold">+</span>
                    </a>

                    <a href="/logros/create" class="flex items-center justify-between p-3 rounded-xl border border-gray-200 hover:border-unsxx-blue-400 hover:bg-blue-50/50 transition-all group">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-yellow-100 text-yellow-800 flex items-center justify-center font-bold text-sm">🏆</span>
                            <div>
                                <p class="text-xs font-bold text-gray-800 group-hover:text-unsxx-blue-900">Registrar Nuevo Logro</p>
                                <p class="text-[11px] text-gray-400">Premios para la línea de tiempo</p>
                            </div>
                        </div>
                        <span class="text-gray-400 group-hover:text-unsxx-blue-700 font-bold">+</span>
                    </a>

                    <a href="/academics/create" class="flex items-center justify-between p-3 rounded-xl border border-gray-200 hover:border-unsxx-blue-400 hover:bg-blue-50/50 transition-all group">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-100 text-unsxx-blue-800 flex items-center justify-center font-bold text-sm">🎓</span>
                            <div>
                                <p class="text-xs font-bold text-gray-800 group-hover:text-unsxx-blue-900">Agregar Docente / Personal</p>
                                <p class="text-[11px] text-gray-400">Plantel académico y administrativo</p>
                            </div>
                        </div>
                        <span class="text-gray-400 group-hover:text-unsxx-blue-700 font-bold">+</span>
                    </a>

                    <a href="/users" class="flex items-center justify-between p-3 rounded-xl border border-gray-200 hover:border-unsxx-blue-400 hover:bg-blue-50/50 transition-all group">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-sm">🔐</span>
                            <div>
                                <p class="text-xs font-bold text-gray-800 group-hover:text-unsxx-blue-900">Usuarios, Roles y Permisos</p>
                                <p class="text-[11px] text-gray-400">Habilitar cuentas o restaurar claves</p>
                            </div>
                        </div>
                        <span class="text-gray-400 group-hover:text-unsxx-blue-700 font-bold">→</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

<?php else: ?>

    <!-- =========================================================================
         VISTA DE ESTACIÓN DE TRABAJO PARA OPERADORES Y RESPONSABLES DE LABORATORIO
         ========================================================================= -->

    <!-- 1. LABORATORIOS ASIGNADOS AL DOCENTE (Prioridad visual si es Responsable de Lab) -->
    <?php if (!empty($labs)): ?>
        <div class="mb-8">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div>
                    <h3 class="text-lg font-extrabold text-unsxx-blue-900 flex items-center gap-2">
                        Mi(s) Laboratorio(s) a Cargo
                    </h3>
                    <p class="text-xs text-gray-500">
                        Tienes permisos de propietario sobre estos laboratorios para mantener actualizado su horario en PDF y su álbum de fotografías.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
                <?php foreach ($labs as $lab): ?>
                    <?php
                    $tieneHorario = !empty($lab['horario_lab']);
                    $totalFotos = (int)($lab['total_fotos'] ?? 0);
                    ?>
                    <div class="bg-white border-2 border-unsxx-blue-100 hover:border-unsxx-blue-300 rounded-2xl shadow-sm overflow-hidden transition-all">
                        <div class="px-6 py-4 bg-unsxx-blue-900 text-white flex items-center justify-between gap-3">
                            <div>
                                <span class="text-[10px] uppercase tracking-wider text-unsxx-yellow-400 font-bold block">Laboratorio Oficial</span>
                                <h4 class="text-base font-bold"><?= htmlspecialchars($lab['nombre_lab']) ?></h4>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold <?= !empty($lab['activo']) ? 'bg-green-500/20 text-green-200 border border-green-400/30' : 'bg-gray-500/20 text-gray-200' ?>">
                                <?= !empty($lab['activo']) ? '● Activo en Portal' : '○ Oculto' ?>
                            </span>
                        </div>

                        <div class="p-6">
                            <!-- Estado Semafórico del Laboratorio -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5">
                                <div class="p-3 rounded-xl border <?= $tieneHorario ? 'bg-green-50/60 border-green-200' : 'bg-amber-50 border-amber-200' ?>">
                                    <p class="text-[11px] font-bold uppercase <?= $tieneHorario ? 'text-green-800' : 'text-amber-800' ?>">
                                        📄 Horario Semestral (PDF)
                                    </p>
                                    <?php if ($tieneHorario): ?>
                                        <a href="/uploads/pdf/laboratorios/<?= urlencode($lab['horario_lab']) ?>" target="_blank"
                                            class="inline-flex items-center gap-1 text-xs font-bold text-green-700 hover:underline mt-1">
                                            Ver PDF actual cargado ↗
                                        </a>
                                    <?php else: ?>
                                        <p class="text-xs font-semibold text-amber-700 mt-1">Pendiente de subir</p>
                                    <?php endif; ?>
                                </div>

                                <div class="p-3 rounded-xl border <?= $totalFotos > 0 ? 'bg-teal-50/60 border-teal-200' : 'bg-red-50 border-red-200' ?>">
                                    <p class="text-[11px] font-bold uppercase <?= $totalFotos > 0 ? 'text-teal-800' : 'text-red-800' ?>">
                                        🖼️ Galería Fotográfica
                                    </p>
                                    <p class="text-xs font-bold <?= $totalFotos > 0 ? 'text-teal-700' : 'text-red-700' ?> mt-1">
                                        <?= $totalFotos > 0 ? $totalFotos . ' fotografía(s) publicada(s)' : 'Sin fotografías (Vacío)' ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Botones de Acción Directa del Responsable -->
                            <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-gray-100">
                                <a href="/labs/<?= (int)$lab['id_lab'] ?>/edit"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 text-xs font-bold shadow-sm transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Subir / Actualizar Horario PDF
                                </a>
                                <a href="/labs/<?= (int)$lab['id_lab'] ?>/gallery"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-unsxx-blue-900 hover:bg-unsxx-blue-800 text-white text-xs font-bold shadow-sm transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Gestionar Fotos (<?= $totalFotos ?>)
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- 2. MÓDULOS HABILITADOS PARA EL OPERADOR -->
    <?php if (!empty($moduleCards)): ?>
        <div class="mb-8">
            <div class="mb-4">
                <h3 class="text-lg font-extrabold text-unsxx-blue-900">Módulos Habilitados en tu Cuenta</h3>
                <p class="text-xs text-gray-500">Haz clic en cualquiera de tus módulos asignados para administrar sus publicaciones.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                <?php foreach ($moduleCards as $card): ?>
                    <?php $st = $cardStyles[$card['color'] ?? 'blue'] ?? $cardStyles['blue']; ?>
                    <a href="<?= htmlspecialchars($card['url']) ?>"
                        class="group relative overflow-hidden p-5 bg-white border border-gray-200 hover:border-unsxx-blue-400 rounded-2xl shadow-xs hover:shadow-md transition-all flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl <?= $st['iconBg'] ?> <?= $st['text'] ?> flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <?= getDashboardIconSvg($card['url'] . ' ' . $card['label']) ?>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-base text-unsxx-blue-900 group-hover:text-unsxx-blue-600 transition-colors">
                                <?= htmlspecialchars($card['label']) ?>
                            </p>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed"><?= htmlspecialchars($card['desc']) ?></p>
                            <span class="inline-flex items-center gap-1 mt-3 text-xs font-bold <?= $st['text'] ?>">
                                Administrar módulo →
                            </span>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 h-1 <?= $st['bar'] ?>"></div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- 3. ESTADO VACÍO SI AÚN NO TIENE PERMISOS -->
    <?php if (empty($moduleCards) && empty($labs)): ?>
        <div class="bg-white border border-unsxx-blue-100 rounded-2xl shadow-sm p-12 text-center max-w-xl mx-auto">
            <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center mx-auto mb-4 text-2xl">
                🔐
            </div>
            <h3 class="text-lg font-bold text-unsxx-blue-900">Cuenta activa sin módulos asignados</h3>
            <p class="text-sm text-gray-500 mt-2">
                Tu cuenta está registrada correctamente, pero aún no tiene módulos ni laboratorios vinculados. Solicita al Administrador General la habilitación de tus permisos.
            </p>
        </div>
    <?php endif; ?>

<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>