<?php
/**
 * Master Layout - Ingeniería Informática UNSXX
 * Extender en cada vista con: require __DIR__ . '/../../layouts/app.php';
 * Variables esperadas: $title, $pageTitle, $content
 */
use App\Core\Auth;

// Obtener número oficial de Soporte Técnico para el botón de ayuda del Sidebar
$supportWaUrl = 'https://wa.me/?text=' . urlencode('Hola Soporte Técnico (Informática UNSXX), deseo reportar una consulta o incidencia en el sistema.');
try {
    if (class_exists('\App\Models\Phone')) {
        $phoneModel = new \App\Models\Phone(); // 1. Instanciamos el modelo primero
        if (method_exists($phoneModel, 'getSupportPhone')) {
            $sup = $phoneModel->getSupportPhone(); // 2. Llamamos al método desde la instancia
            $rawPhone = is_array($sup) ? ($sup['num_cel'] ?? '') : (string) $sup;
            $cleanSup = preg_replace('/\D+/', '', $rawPhone);
            if (strlen($cleanSup) === 8) {
                $cleanSup = '591' . $cleanSup;
            }
            if (!empty($cleanSup)) {
                $supportWaUrl = "https://wa.me/{$cleanSup}?text=" . urlencode('Hola Soporte Técnico (Informática UNSXX), necesito asistencia con el sistema web.');
            }
        }
    }
} catch (\Throwable $e) {
    // Fallback silencioso si el modelo aún no carga o falla la conexión DB
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Ingeniería Informática UNSXX') ?></title>
    <link rel="icon" type="image/png" href="/assets/img/logo-informatica.png">
    <link rel="apple-touch-icon" href="/assets/img/logo-informatica.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'unsxx-blue': {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd',
                            400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8',
                            800: '#1e40af', 900: '#1e3a8a', 950: '#172554'
                        },
                        'unsxx-yellow': {
                            50: '#fefce8', 100: '#fef9c3', 200: '#fef08a', 300: '#fde047',
                            400: '#facc15', 500: '#eab308', 600: '#ca8a04', 700: '#a16207',
                            800: '#854d0e', 900: '#713f12', 950: '#713f12'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-unsxx-blue-50">
    <!-- Sidebar Overlay (mobile) -->
    <aside id="sidebar-overlay" class="fixed inset-0 z-40 bg-black/50 lg:hidden hidden" aria-hidden="true"></aside>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-unsxx-blue-900 transform transition-transform duration-300 ease-in-out lg:translate-x-0 -translate-x-full flex flex-col h-full border-r border-unsxx-blue-800">
        <!-- Logo con el Escudo Oficial de la Carrera -->
        <div class="flex items-center justify-start h-16 shrink-0 border-b border-unsxx-blue-800 px-5">
            <a href="/dashboard" class="flex items-center gap-3 group">
                <div
                    class="w-10 h-10 rounded-xl bg-white/95 p-1 flex items-center justify-center shrink-0 shadow-md group-hover:scale-105 transition-transform">
                    <img src="/assets/img/logo-informatica.png" alt="Escudo Informática UNSXX"
                        class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="block text-sm font-extrabold text-white leading-tight tracking-wide">Informática
                        UNSXX</span>
                    <span class="block text-[10px] text-unsxx-yellow-400 font-semibold uppercase tracking-wider">Panel
                        de Gestión</span>
                </div>
            </a>
        </div>

        <!-- Navigation Categorizada -->
        <nav class="p-4 space-y-6 overflow-y-auto flex-1 custom-scrollbar" role="navigation"
            aria-label="Menú principal">

            <!-- 1. Principal -->
            <div>
                <div class="flex items-center gap-2 px-3 mb-2 text-unsxx-yellow-400/80">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    <p class="text-[10px] font-bold uppercase tracking-wider">Principal</p>
                </div>
                <div class="space-y-1">
                    <a href="/dashboard"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-white bg-unsxx-blue-800 hover:bg-unsxx-blue-700 transition shadow-sm border border-unsxx-blue-700">
                        <svg class="w-4 h-4 text-unsxx-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="font-semibold">Inicio / Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- 2. Institución -->
            <?php if (Auth::canAccessModule('institucion') || Auth::canAccessModule('portadas') || Auth::canAccessModule('redes')): ?>
                <div>
                    <div class="flex items-center gap-2 px-3 mb-2 text-unsxx-yellow-400/80">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0v-4m0 0h4m-4 0V9m0 0h4m-4 0V5">
                            </path>
                        </svg>
                        <p class="text-[10px] font-bold uppercase tracking-wider">Institución</p>
                    </div>
                    <div class="space-y-1">
                        <?php if (Auth::canAccessModule('institucion')): ?>
                            <a href="/institutional-info"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-unsxx-blue-100 hover:bg-unsxx-blue-800 hover:text-white transition">
                                <svg class="w-4 h-4 text-unsxx-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0v-4m0 0h4m-4 0V9m0 0h4m-4 0V5" />
                                </svg>
                                <span>Identidad y Textos</span>
                            </a>
                        <?php endif; ?>

                        <?php if (Auth::canAccessModule('portadas')): ?>
                            <a href="/portadas"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-unsxx-blue-100 hover:bg-unsxx-blue-800 hover:text-white transition">
                                <svg class="w-4 h-4 text-unsxx-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Fotos Institucionales
                            </a>
                        <?php endif; ?>

                        <?php if (Auth::canAccessModule('redes')): ?>
                            <a href="/directory"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-unsxx-blue-100 hover:bg-unsxx-blue-800 hover:text-white transition">
                                <svg class="w-4 h-4 text-unsxx-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                                <span>Directorio y Redes</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 3. Gestión Académica -->
            <?php if (Auth::canAccessModule('academics') || Auth::canAccessModule('documents') || Auth::canAccessModule('curricular') || Auth::canAccessModule('laboratories') || Auth::canAccessModule('admissions')): ?>
                <div>
                    <div class="flex items-center gap-2 px-3 mb-2 text-unsxx-yellow-400/80">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                        <p class="text-[10px] font-bold uppercase tracking-wider">Gestión Académica</p>
                    </div>
                    <div class="space-y-1">
                        <?php if (Auth::canAccessModule('academics')): ?>
                            <a href="/academics"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-unsxx-blue-100 hover:bg-unsxx-blue-800 hover:text-white transition">
                                <svg class="w-4 h-4 text-unsxx-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                </svg>
                                <span>Plantel Docente y Administrativo</span>
                            </a>
                        <?php endif; ?>

                        <?php if (Auth::canAccessModule('documents')): ?>
                            <a href="/documents"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-unsxx-blue-100 hover:bg-unsxx-blue-800 hover:text-white transition">
                                <svg class="w-4 h-4 text-unsxx-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Documentos y Reglamentos</span>
                            </a>
                        <?php endif; ?>

                        <?php if (Auth::canAccessModule('curricular')): ?>
                            <a href="/mentions"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-unsxx-blue-100 hover:bg-unsxx-blue-800 hover:text-white transition">
                                <svg class="w-4 h-4 text-unsxx-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>Menciones - IIDAI</span>
                            </a>
                        <?php endif; ?>

                        <?php if (Auth::canAccessModule('laboratories')): ?>
                            <a href="/labs"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-unsxx-blue-100 hover:bg-unsxx-blue-800 hover:text-white transition">
                                <svg class="w-4 h-4 text-unsxx-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>Laboratorios</span>
                            </a>
                        <?php endif; ?>

                        <?php if (Auth::canAccessModule('admissions')): ?>
                            <a href="/admissions"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-unsxx-blue-100 hover:bg-unsxx-blue-800 hover:text-white transition">
                                <svg class="w-4 h-4 text-unsxx-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span>Modalidades de Admisión</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 4. Comunicación y Extensión -->
            <?php if (Auth::canAccessModule('communications') || Auth::canAccessModule('extensions') || Auth::canAccessModule('logros')): ?>
                <div>
                    <div class="flex items-center gap-2 px-3 mb-2 text-unsxx-yellow-400/80">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                            </path>
                        </svg>
                        <p class="text-[10px] font-bold uppercase tracking-wider">Comunicación y Extensión</p>
                    </div>
                    <div class="space-y-1">
                        <?php if (Auth::canAccessModule('communications')): ?>
                            <a href="/announcements"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-unsxx-blue-100 hover:bg-unsxx-blue-800 hover:text-white transition">
                                <svg class="w-4 h-4 text-unsxx-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                                <span>Comunicados Oficiales</span>
                            </a>
                        <?php endif; ?>
                        <?php if (Auth::canAccessModule('extensions')): ?>
                            <a href="/extensions"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-unsxx-blue-100 hover:bg-unsxx-blue-800 hover:text-white transition">
                                <svg class="w-4 h-4 text-unsxx-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Extensión y Eventos</span>
                            </a>
                        <?php endif; ?>
                        <?php if (Auth::canAccessModule('logros')): ?>
                            <a href="/logros"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-unsxx-blue-100 hover:bg-unsxx-blue-800 hover:text-white transition">
                                <svg class="w-4 h-4 text-unsxx-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                                <span>Logros de la Carrera</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 6. Sistema y Seguridad (solo administradores) -->
            <?php if (Auth::isAdmin()): ?>
                <div>
                    <div class="flex items-center gap-2 px-3 mb-2 text-unsxx-yellow-400/80">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                        <p class="text-[10px] font-bold uppercase tracking-wider">Sistema y Seguridad</p>
                    </div>
                    <div class="space-y-1">
                        <a href="/users"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-unsxx-blue-100 hover:bg-unsxx-blue-800 hover:text-white transition">
                            <svg class="w-4 h-4 text-unsxx-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Usuarios y Roles</span>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        </nav>

        <!-- Pie del Sidebar: Botón de Soporte Técnico (RF-04) -->
        <div class="p-3 border-t border-unsxx-blue-800 bg-unsxx-blue-950/60">
            <a href="<?= htmlspecialchars($supportWaUrl) ?>" target="_blank" rel="noopener noreferrer"
                class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs text-blue-200 hover:text-white hover:bg-unsxx-blue-800/80 transition">
                <div
                    class="w-7 h-7 rounded-lg bg-green-500/20 text-green-400 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-white truncate">Soporte Técnico</p>
                    <p class="text-[10px] text-blue-300 truncate">Reportar falla por WhatsApp</p>
                </div>
            </a>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="lg:pl-64 min-h-screen flex flex-col">
        <!-- Top Navbar -->
        <header class="sticky top-0 z-30 bg-white border-b border-unsxx-blue-100 shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <!-- Mobile Menu Button -->
                <button id="menu-toggle"
                    class="lg:hidden p-2 rounded-lg text-unsxx-blue-600 hover:bg-unsxx-blue-100 transition"
                    aria-label="Abrir menú" aria-expanded="false" aria-controls="sidebar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Page Title -->
                <h1 class="text-xl font-bold text-unsxx-blue-900 lg:pl-4"><?= htmlspecialchars($pageTitle ?? 'Panel') ?>
                </h1>

                <!-- User Menu -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <?php if (isset($_SESSION['user']) || isset($_SESSION['user_id'])): ?>
                        <?php
                        $currentUserId = (int) ($_SESSION['user_id'] ?? ($_SESSION['user']['id'] ?? 0));
                        $sessionRole = $_SESSION['user_role'] ?? ($_SESSION['user']['role'] ?? 'user');
                        $roleLabel = $sessionRole === 'admin' ? 'Administrador' : 'Operador';
                        ?>
                        <span class="text-sm font-medium text-unsxx-blue-900 hidden sm:block">
                            <?= htmlspecialchars($_SESSION['user_name'] ?? ($_SESSION['user']['name'] ?? 'Usuario')) ?>
                        </span>
                        <span
                            class="px-3 py-1 text-xs font-semibold rounded-full bg-unsxx-yellow-100 text-unsxx-yellow-800 border border-unsxx-yellow-300">
                            <?= htmlspecialchars($roleLabel) ?>
                        </span>

                        <!-- RF-01: Solo el Administrador General tiene botón directo para editar todos sus datos de cuenta -->
                        <?php if (Auth::isAdmin() && $currentUserId > 0): ?>
                            <a href="/users/<?= $currentUserId ?>/edit" title="Editar mis datos de cuenta"
                                class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-unsxx-blue-700 hover:text-unsxx-blue-900 hover:bg-unsxx-blue-50 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="hidden xl:inline">Mi Cuenta</span>
                            </a>
                        <?php endif; ?>

                        <a href="/password" title="Cambiar contraseña"
                            class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-unsxx-blue-700 hover:text-unsxx-blue-900 hover:bg-unsxx-blue-50 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            <span class="hidden md:inline">Contraseña</span>
                        </a>
                        <a href="/logout" title="Cerrar sesión del sistema"
                            class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="hidden sm:inline">Cerrar sesión</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 sm:p-6 lg:p-8 flex-1">
            <?= $content ?? '' ?>
        </main>
    </div>

    <style>
        @keyframes slide-down {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-down {
            animation: slide-down 0.3s ease-out forwards;
        }

        #sidebar.open {
            transform: translateX(0);
        }

        #sidebar-overlay.open {
            display: block;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>

    <!-- Vanilla JS for Sidebar Toggle -->
    <script>
            (function () {
                const menuToggle = document.getElementById('menu-toggle');
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');

                function openSidebar() {
                    sidebar.classList.add('open');
                    overlay.classList.add('open');
                    menuToggle.setAttribute('aria-expanded', 'true');
                    document.body.style.overflow = 'hidden';
                }

                function closeSidebar() {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('open');
                    menuToggle.setAttribute('aria-expanded', 'false');
                    document.body.style.overflow = '';
                }

                menuToggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    if (sidebar.classList.contains('open')) closeSidebar(); else openSidebar();
                });

                overlay.addEventListener('click', closeSidebar);
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape' && sidebar.classList.contains('open')) closeSidebar();
                });
            })();
    </script>

    <!-- SweetAlert2 Flash Notifications -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            <?php if (isset($_SESSION['success'])): ?>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: <?= json_encode($_SESSION['success']) ?>,
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#1e40af',
                    timer: 5000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: true
                });
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: <?= json_encode($_SESSION['error']) ?>,
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#dc2626',
                    timer: 5000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: true
                });
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        });
    </script>
</body>

</html>