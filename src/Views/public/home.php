<?php
/**
 * Vista Principal: Inicio del Portal Público (src/Views/public/home.php)
 * Variables inyectadas por HomeController::index() y renderPublicView():
 * @var array $portadas              Registros activos de `portada_inicio`
 * @var array $info                  Registro único de `info_institucional`
 * @var array $academics             Plantel agrupado ('authorities', 'teachers', 'all')
 * @var array $mentions              Menciones activas e Instituto IIDAI (`mencion_instituto`)
 * @var array $announcements         Comunicados publicados (`comunicado`)
 * @var array $extensions            Actividades de extensión con galería (`extension`)
 * @var array $logros                Logros institucionales (`logro`)
 * @var array $labs                  Laboratorios activos con galería (`laboratorio`)
 * @var array $phones                Teléfonos activos (`celular`)
 * @var array $enlacesRedesSociales  Redes sociales (`footer_redes`)
 */

use App\Config\Database;

// =========================================================================
// 1. CÁLCULO AUTOMÁTICO DE AÑOS DE VIDA INSTITUCIONAL (CADA 4 DE JULIO)
// Fundación oficial: 4 de julio de 1998 (Resolución H.C.U. N° 013/98)
// =========================================================================
$fechaFundacion = new DateTimeImmutable('1998-07-04');
$fechaHoy       = new DateTimeImmutable('today');
$aniosVida      = $fechaFundacion->diff($fechaHoy)->y;

// =========================================================================
// 2. HELPERS DE FORMATO Y SANITIZACIÓN (QUILL.JS Y MULTIMEDIA)
// =========================================================================
if (!function_exists('formatearTextoQuill')) {
    function formatearTextoQuill(?string $texto): string {
        $raw = trim((string) $texto);
        if ($raw === '') {
            return '';
        }
        if ($raw !== strip_tags($raw)) {
            $limpio = strip_tags($raw, '<p><br><strong><b><em><i><u><ul><ol><li><h3><h4><span><a>');
            $limpio = preg_replace('/background-color:\s*[^;"\']+;?/i', '', (string) $limpio);
            return trim((string) $limpio);
        }
        return nl2br(htmlspecialchars($raw, ENT_QUOTES, 'UTF-8'));
    }
}

if (!function_exists('obtenerInicialesDocente')) {
    function obtenerInicialesDocente(string $nombreCompleto): string {
        $limpio = preg_replace('/\b(M\.Sc\.|M\.Ss\.|Ph\.D\.|Ph\.D|Dra\.|Dr\.|Ing\.|Lic\.|Mgr\.)\s*/i', '', $nombreCompleto);
        $partes = array_values(array_filter(explode(' ', trim((string) $limpio))));
        $inicial1 = isset($partes[0]) ? mb_substr($partes[0], 0, 1, 'UTF-8') : 'I';
        $inicial2 = isset($partes[1]) ? mb_substr($partes[1], 0, 1, 'UTF-8') : 'I';
        return mb_strtoupper($inicial1 . $inicial2, 'UTF-8');
    }
}

if (!function_exists('obtenerUrlFotoDocente')) {
    function obtenerUrlFotoDocente(?string $archivoFoto): ?string {
        $archivo = trim((string) $archivoFoto);
        if ($archivo === '' || strtolower($archivo) === 'default.png') {
            return null;
        }
        return '/uploads/img/academic/' . rawurlencode($archivo);
    }
}

if (!function_exists('formatearFechaCortaEsp')) {
    function formatearFechaCortaEsp(?string $fechaIso): string {
        $meses = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
        ];
        $ts = !empty($fechaIso) ? strtotime($fechaIso) : false;
        if (!$ts) {
            return '';
        }
        return date('d', $ts) . ' ' . ($meses[(int) date('n', $ts)] ?? '') . ' ' . date('Y', $ts);
    }
}

// =========================================================================
// 3. PREPARACIÓN DE DATOS Y CONSULTA DEL COMUNICADO DESTACADO PARA EL MODAL
// =========================================================================
$listaPortadas    = $portadas ?? [];
$listaMenciones   = $mentions ?? [];
$listaComunicados = $announcements ?? [];
$listaExtension   = $extensions ?? [];
$listaLogros      = $logros ?? [];
$listaLabs        = $labs ?? [];
$infoInst         = $info ?? [];

// Buscar el Comunicado Fijado (fijado = 1); si no hay ninguno fijado aún, mostrar el último publicado
$comunicadoModal = $pinnedAnnouncement ?? null;
if ($comunicadoModal === null) {
    try {
        $pdoHome = Database::getInstance();
        $stmtDest = $pdoHome->query("
            SELECT *
            FROM comunicado
            WHERE estado = 1
            ORDER BY fijado DESC, fech_com DESC, id_com DESC
            LIMIT 1
        ");
        $comunicadoModal = $stmtDest ? ($stmtDest->fetch(PDO::FETCH_ASSOC) ?: null) : null;
    } catch (Throwable $e) {
        $comunicadoModal = $listaComunicados[0] ?? null;
    }
}

// WhatsApp institucional para el botón flotante del Hero
$whatsappFlotante = '59174259358';
if (!empty($phones)) {
    foreach ($phones as $ph) {
        if ((int) ($ph['es_soporte'] ?? 0) === 0 && !empty($ph['num_cel'])) {
            $limpioPh = preg_replace('/[^0-9]/', '', (string) $ph['num_cel']);
            if ($limpioPh !== '') {
                $whatsappFlotante = strlen($limpioPh) === 8 ? '591' . $limpioPh : $limpioPh;
                break;
            }
        }
    }
}

// Directora de Carrera
$director = null;
$fuenteAcademicos = !empty($academics['all']) ? $academics['all'] : ($academics['authorities'] ?? []);
foreach ($fuenteAcademicos as $ac) {
    $catId = (int) ($ac['cat_acad'] ?? 0);
    $catLower = mb_strtolower(trim((string) ($ac['cat_nombre'] ?? '')), 'UTF-8');
    if ($catId === 7 || str_contains($catLower, 'director')) {
        $director = $ac;
        break;
    }
}

// Elementos del Acordeón "Acerca de Nosotros" (Columna Derecha)
$itemsAcordeonNosotros = [
    [
        'id'        => 'presentacion',
        'titulo'    => 'Presentación',
        'subtitulo' => '¿Por qué estudiar Ingeniería Informática en la UNSXX?',
        'icono'     => 'fas fa-laptop-code',
        'contenido' => formatearTextoQuill($infoInst['presentacion'] ?? ''),
    ],
    [
        'id'        => 'perfil',
        'titulo'    => 'Perfil Profesional',
        'subtitulo' => 'Competencias del Ingeniero Informático y Grados Intermedios',
        'icono'     => 'fas fa-user-graduate',
        'contenido' => formatearTextoQuill($infoInst['perfil_general'] ?? ''),
    ],
    [
        'id'        => 'mision',
        'titulo'    => 'Misión',
        'subtitulo' => 'Misión Institucional de la Carrera',
        'icono'     => 'fas fa-bullseye',
        'contenido' => formatearTextoQuill($infoInst['mision'] ?? ''),
    ],
    [
        'id'        => 'vision',
        'titulo'    => 'Visión',
        'subtitulo' => 'Visión Institucional de la Carrera',
        'icono'     => 'fas fa-eye',
        'contenido' => formatearTextoQuill($infoInst['vision'] ?? ''),
    ],
    [
        'id'        => 'objetivos',
        'titulo'    => 'Objetivo General',
        'subtitulo' => 'Propósito de Formación Profesional',
        'icono'     => 'fas fa-compass-drafting',
        'contenido' => formatearTextoQuill($infoInst['objetivos'] ?? ''),
    ],
    [
        'id'        => 'historia',
        'titulo'    => 'Reseña Histórica',
        'subtitulo' => $aniosVida . ' Años de Vida Institucional y Acreditación al CEUB',
        'icono'     => 'fas fa-landmark',
        'contenido' => formatearTextoQuill($infoInst['resena_historica'] ?? ''),
    ],
];
?>

<!-- =========================================================================
     1. HERO INMERSIVO A PANTALLA COMPLETA (INSPIRADO EN LA REFERENCIA)
     ========================================================================= -->
<section class="relative w-full min-h-[calc(100vh-5rem)] bg-slate-950 text-white flex items-center justify-center overflow-hidden border-b-4 border-institucional-gold select-none">

    <!-- Fondo Dinámico de Portadas a Pantalla Completa (`portada_inicio`) -->
    <div id="hero-bg-carousel" class="absolute inset-0 z-0">
        <?php if (!empty($listaPortadas)): ?>
            <?php foreach ($listaPortadas as $idxP => $portada): ?>
                <?php
                    $archivoPortada = trim((string) ($portada['archivo_url'] ?? ''));
                    $urlPortada     = '/uploads/img/portadas/' . rawurlencode($archivoPortada);
                    $tituloPortada  = !empty($portada['titulo']) ? trim((string) $portada['titulo']) : 'Campus María Barzola · Ingeniería Informática';
                ?>
                <div class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-out <?= $idxP === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none' ?>"
                     data-index="<?= $idxP ?>"
                     data-caption="<?= htmlspecialchars($tituloPortada) ?>">
                    <img src="<?= htmlspecialchars($urlPortada) ?>"
                         alt="<?= htmlspecialchars($tituloPortada) ?>"
                         class="w-full h-full object-cover object-center transform scale-105 transition-transform duration-[8000ms]">
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="absolute inset-0 aurora-hero-navy"></div>
        <?php endif; ?>

        <!-- Velo de Contraste Institucional (Permite lucir las fotos manteniendo legibilidad perfecta) -->
        <div class="absolute inset-0 z-10 bg-gradient-to-b from-slate-950/65 via-[#0f1f4d]/55 to-slate-950/85"></div>
        <div class="absolute inset-0 z-10 opacity-15 pointer-events-none"
             style="background-image: radial-gradient(circle at 50% 40%, rgba(234, 179, 8, 0.25), transparent 65%);"></div>
    </div>

    <!-- Contenido Monumental Centrado -->
    <div class="relative z-20 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 text-center flex flex-col items-center">
        
        <!-- Etiqueta Dinámica de la Fotografía Actual -->
        <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-slate-950/65 border border-institucional-gold/50 backdrop-blur-md mb-6 shadow-lg">
            <span class="w-2 h-2 rounded-full bg-institucional-gold animate-pulse"></span>
            <span id="hero-slide-caption" class="font-code text-xs font-medium text-slate-100 tracking-wider uppercase">
                <?= htmlspecialchars(!empty($listaPortadas[0]['titulo']) ? (string) $listaPortadas[0]['titulo'] : 'Carrera Acreditada al CEUB') ?>
            </span>
        </div>

        <!-- Título Principal: Ingeniería Informática -->
        <h1 class="font-display text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-institucional-gold drop-shadow-[0_4px_24px_rgba(0,0,0,0.85)] leading-none">
            Ingeniería Informática
        </h1>

        <!-- Años de Vida Institucional (Dinámico cada 4 de julio) + Estado de Acreditación -->
        <p class="mt-4 text-4xl sm:text-4xl text-yellow-200 drop-shadow-[0_2px_12px_rgba(0,0,0,0.9)]">
            <?= $aniosVida ?> años de Vida Institucional
        </p>

        <div class="mt-3 inline-flex items-center gap-2 px-5 py-1.5 rounded-full bg-institucional-navy/85 border border-institucional-gold text-white text-sm sm:text-base font-bold tracking-wide shadow-[0_0_25px_rgba(234,179,8,0.35)] backdrop-blur-md">
            <img src="/assets/img/logo-ceub.png"
                 alt="Escudo CEUB"
                 class="h-16 sm:h-16 w-auto object-contain drop-shadow-[0_0_12px_rgba(255,255,255,0.5)] hover:scale-105 transition-transform">
            <span>Carrera Acreditada al CEUB</span>
        </div>

        <!-- Línea Divisoria Horizontal Elegante -->
        <div class="w-full max-w-xl my-6 flex items-center gap-4">
            <div class="h-[1px] flex-1 bg-gradient-to-r from-transparent via-white/70 to-institucional-gold"></div>
            <span class="font-code text-xs text-institucional-gold font-bold px-2 py-0.5 rounded bg-slate-950/60 border border-institucional-gold/40">&lt;/&gt;</span>
            <div class="h-[1px] flex-1 bg-gradient-to-l from-transparent via-white/70 to-institucional-gold"></div>
        </div>

        <!-- Nombre de la Universidad -->
        <p class="font-cursive text-2xl sm:text-3xl text-white drop-shadow-[0_2px_10px_rgba(0,0,0,0.9)]">
            Universidad Nacional "Siglo XX"
        </p>

        <!-- Escudos Oficiales Centrados con Resplandor -->
        <div class="mt-5 flex items-center justify-center gap-6 sm:gap-8">
            <img src="/assets/img/logo-unsxx.png"
                 alt="Escudo UNSXX"
                 class="h-20 sm:h-24 w-auto object-contain drop-shadow-[0_0_12px_rgba(255,255,255,0.85)] hover:scale-105 transition-transform">
            <img src="/assets/img/logo-informatica.png"
                 alt="Escudo Ingeniería Informática"
                 class="h-20 sm:h-24 w-auto object-contain drop-shadow-[0_0_12px_rgba(255,255,255,0.85)] hover:scale-105 transition-transform">
        </div>

        <!-- Botones de Navegación Estratégica -->
        <div class="mt-8 flex flex-wrap items-center justify-center gap-3.5">
            <a href="#acerca-de-nosotros"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-institucional-gold hover:bg-yellow-400 text-slate-950 font-extrabold text-xs sm:text-sm transition-all shadow-[0_10px_25px_-5px_rgba(234,179,8,0.6)] hover:-translate-y-0.5">
                <span>Acerca de Nosotros</span>
                <i class="fas fa-chevron-down text-xs"></i>
            </a>

            <a href="/admision"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-institucional-navy/90 hover:bg-institucional-navy text-white border border-institucional-gold/60 font-bold text-xs sm:text-sm backdrop-blur-md transition-all shadow-lg hover:-translate-y-0.5">
                <i class="fas fa-user-plus text-institucional-gold"></i>
                <span>Admisión <?= date('Y') ?></span>
            </a>

            <?php if ($comunicadoModal !== null): ?>
                <button type="button"
                        id="btn-reabrir-comunicado-hero"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-white/15 hover:bg-white/25 text-white border border-white/30 font-semibold text-xs sm:text-sm backdrop-blur-md transition-all">
                    <span class="w-2 h-2 rounded-full bg-institucional-gold animate-ping"></span>
                    <span>Último Comunicado</span>
                </button>
            <?php endif; ?>
        </div>

    </div>

    <!-- Controles Laterales e Indicadores del Carrusel -->
    <?php if (count($listaPortadas) > 1): ?>
        <button type="button" id="hero-prev" aria-label="Anterior portada" class="hidden sm:flex absolute left-4 lg:left-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-slate-950/55 hover:bg-institucional-gold text-white hover:text-slate-950 border border-white/25 items-center justify-center transition-all backdrop-blur-sm">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button type="button" id="hero-next" aria-label="Siguiente portada" class="hidden sm:flex absolute right-4 lg:right-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-slate-950/55 hover:bg-institucional-gold text-white hover:text-slate-950 border border-white/25 items-center justify-center transition-all backdrop-blur-sm">
            <i class="fas fa-chevron-right"></i>
        </button>

        <!-- Puntos inferiores -->
        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-950/60 border border-white/15 backdrop-blur-md">
            <?php foreach ($listaPortadas as $idxDot => $_): ?>
                <button type="button"
                        aria-label="Portada <?= $idxDot + 1 ?>"
                        data-slide="<?= $idxDot ?>"
                        class="hero-dot h-2 rounded-full transition-all <?= $idxDot === 0 ? 'w-7 bg-institucional-gold' : 'w-2 bg-white/50 hover:bg-white' ?>"></button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Botones Flotantes de Contacto y Redes (Esquina Inferior Derecha como en la referencia) -->
    <div class="fixed bottom-5 right-4 sm:right-6 z-40 flex flex-col gap-2.5">
        <a href="https://wa.me/<?= htmlspecialchars($whatsappFlotante) ?>"
           target="_blank"
           rel="noopener noreferrer"
           title="Consultar por WhatsApp"
           class="w-11 h-11 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white flex items-center justify-center text-xl shadow-[0_8px_20px_rgba(16,185,129,0.45)] hover:scale-110 transition-transform border-2 border-white">
            <i class="fab fa-whatsapp"></i>
        </a>

        <?php if (!empty($enlacesRedesSociales)): ?>
            <?php foreach ($enlacesRedesSociales as $redFlotante): ?>
                <?php
                    $icoRed = strtolower((string) ($redFlotante['icono'] ?? ''));
                    $bgRed  = str_contains($icoRed, 'telegram')
                        ? 'bg-sky-500 hover:bg-sky-600 shadow-[0_8px_20px_rgba(14,165,233,0.4)]'
                        : 'bg-[#1877F2] hover:bg-blue-700 shadow-[0_8px_20px_rgba(24,119,242,0.4)]';
                ?>
                <a href="<?= htmlspecialchars((string) $redFlotante['url']) ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   title="<?= htmlspecialchars((string) $redFlotante['nombre']) ?>"
                   class="w-11 h-11 rounded-full <?= $bgRed ?> text-white flex items-center justify-center text-lg hover:scale-110 transition-transform border-2 border-white">
                    <i class="<?= htmlspecialchars((string) ($redFlotante['icono'] ?: 'fas fa-share-alt')) ?>"></i>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</section>


<!-- =========================================================================
     2. SECCIÓN PRINCIPAL MULTICOLUMNA: "ACERCA DE NOSOTROS" (CERO SCROLL EXTENSO)
     (Izquierda: Menciones/IIDAI + Laboratorios | Derecha: Acordeón Interactivo)
     ========================================================================= -->
<div id="acerca-de-nosotros" class="relative py-14 sm:py-16 overflow-x-clip scroll-mt-20 space-y-16">
    <section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Encabezado de Sección -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="font-code text-xs font-medium uppercase tracking-wider text-institucional-navy bg-blue-50 px-3.5 py-1 rounded-full border border-blue-200/80">
                &lt;/&gt; Excelencia Académica y Científica
            </span>
            <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-institucional-navy mt-3">
                Acerca de Nosotros
            </h2>
            <div class="w-24 h-1 bg-gradient-to-r from-institucional-navy via-institucional-gold to-institucional-navy mx-auto mt-3 rounded-full"></div>
        </div>

        <!-- LAYOUT DE 2 COLUMNAS (Inspirado en tu segunda imagen) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- =============================================================
                 COLUMNA IZQUIERDA (7 cols): MENCIONES, IIDAI Y LABORATORIOS
                 ============================================================= -->
            <div class="lg:col-span-7 xl:col-span-8 space-y-12">

                <!-- SUB-BLOQUE A: Dos Menciones y un Instituto de Investigación -->
                <?php if (!empty($listaMenciones)): ?>
                    <div class="code-window-panel p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-5 mb-6 border-b border-slate-200/90">
                            <div>
                                <span class="font-code text-[11px] uppercase tracking-wider text-institucional-navy font-bold block">
                                    &lt;/&gt; Menciones e Instituto IIDAI
                                </span>
                                <h3 class="font-display text-xl sm:text-2xl font-bold text-slate-900 mt-0.5">
                                    Contamos con dos Menciones y un Instituto de Investigación
                                </h3>
                            </div>
                            <a href="/academico/plan-estudios" class="text-xs font-bold text-institucional-navy hover:text-yellow-600 whitespace-nowrap">
                                Plan de Estudios &rarr;
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <?php foreach ($listaMenciones as $men): ?>
                                <?php
                                    $nomMen      = trim((string) ($men['nombre'] ?? 'Especialidad'));
                                    $nomLower    = mb_strtolower($nomMen, 'UTF-8');
                                    $esIidaiCard = str_contains($nomLower, 'iidai');

                                    $escudoArch  = trim((string) ($men['escudo_url'] ?? ''));
                                    $urlEscudo   = ($escudoArch !== '' && strtolower($escudoArch) !== 'default.png')
                                        ? '/uploads/img/menciones/' . rawurlencode($escudoArch)
                                        : '/assets/img/logo-informatica.png';

                                    if ($esIidaiCard) {
                                        $urlDestino = '/investigacion/iidai';
                                        $tituloCard = 'Instituto de Investigación y Desarrollo de Aplicaciones Informáticas (IIDAI)';
                                    } elseif (str_contains($nomLower, 'seguridad')) {
                                        $urlDestino = '/mencion/seguridad-informatica';
                                        $tituloCard = 'Mención Seguridad Informática';
                                    } else {
                                        $urlDestino = '/mencion/computacion-movil';
                                        $tituloCard = 'Mención Computación Móvil';
                                    }
                                ?>

                                <a href="<?= htmlspecialchars($urlDestino) ?>"
                                   class="tech-card p-5 flex flex-col items-center text-center justify-between group relative overflow-hidden bg-white">
                                    <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r <?= $esIidaiCard ? 'from-institucional-gold via-yellow-400 to-institucional-navy' : 'from-institucional-navy via-blue-600 to-institucional-gold' ?>"></div>

                                    <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl bg-white p-3 border border-slate-200/90 shadow-sm flex items-center justify-center my-2 group-hover:scale-105 transition-transform">
                                        <img src="<?= htmlspecialchars($urlEscudo) ?>"
                                             alt="<?= htmlspecialchars($tituloCard) ?>"
                                             loading="lazy"
                                             class="w-full h-full object-contain">
                                    </div>

                                    <h4 class="font-display text-sm sm:text-base font-bold text-institucional-navy group-hover:text-yellow-600 transition-colors leading-snug mt-3">
                                        <?= htmlspecialchars($tituloCard) ?>
                                    </h4>

                                    <span class="mt-4 inline-flex items-center gap-1 text-[11px] font-code font-semibold text-slate-500 group-hover:text-institucional-navy">
                                        <span>Ver detalles</span>
                                        <i class="fas fa-arrow-right text-[9px] text-institucional-gold"></i>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- SUB-BLOQUE B: Laboratorios de la Carrera -->
                <?php if (!empty($listaLabs)): ?>
                    <div class="code-window-panel p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-5 mb-6 border-b border-slate-200/90">
                            <div>
                                <span class="font-code text-[11px] uppercase tracking-wider text-institucional-navy font-bold block">
                                    &lt;/&gt; Infraestructura Tecnológica
                                </span>
                                <h3 class="font-display text-xl sm:text-2xl font-bold text-slate-900 mt-0.5">
                                    Contamos con <?= count($listaLabs) ?> Laboratorios Especializados
                                </h3>
                            </div>
                            <a href="/laboratorios" class="text-xs font-bold text-institucional-navy hover:text-yellow-600 whitespace-nowrap">
                                Ver todos y horarios &rarr;
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                            <?php foreach ($listaLabs as $lab): ?>
                                <?php
                                    $idLab      = (int) ($lab['id_lab'] ?? 0);
                                    $nomLab     = trim((string) ($lab['nombre_lab'] ?? 'Laboratorio'));
                                    $archPort   = trim((string) ($lab['portada']['archivo_url'] ?? ($lab['fotos'][0]['archivo_url'] ?? '')));
                                    $urlFotoLab = $archPort !== '' ? '/uploads/img/laboratorios/' . rawurlencode($archPort) : null;
                                ?>
                                <a href="/laboratorios#lab-<?= $idLab ?>"
                                   class="tech-card overflow-hidden flex flex-col justify-between group bg-white">
                                    <div class="h-36 bg-gradient-to-br from-institucional-navy via-[#162d6e] to-slate-900 relative overflow-hidden flex items-center justify-center">
                                        <?php if ($urlFotoLab !== null): ?>
                                            <img src="<?= htmlspecialchars($urlFotoLab) ?>"
                                                 alt="Laboratorio de <?= htmlspecialchars($nomLab) ?>"
                                                 loading="lazy"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                 onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                                            <div class="hidden flex-col items-center justify-center text-institucional-gold">
                                                <i class="fas fa-desktop text-3xl mb-1"></i>
                                                <span class="font-code text-[10px] text-white/80 uppercase">Lab. Informática</span>
                                            </div>
                                        <?php else: ?>
                                            <div class="flex flex-col items-center justify-center text-institucional-gold">
                                                <i class="fas fa-desktop text-3xl mb-1.5 drop-shadow"></i>
                                                <span class="font-code text-[10px] text-white/80 uppercase tracking-wider">Equipamiento Técnico</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="p-4 text-center">
                                        <h4 class="font-display text-sm sm:text-base font-bold text-institucional-navy group-hover:text-yellow-600 transition-colors">
                                            Laboratorio de <?= htmlspecialchars($nomLab) ?>
                                        </h4>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- =============================================================
                 COLUMNA DERECHA (5 cols): ACORDEÓN INTERACTIVO "ACERCA DE NOSOTROS"
                 + BIENVENIDA DE DIRECCIÓN (Sticky, compacto y con modal "Ver más")
                 ============================================================= -->
            <aside class="lg:col-span-5 xl:col-span-4 space-y-6 lg:sticky lg:top-24">
                
                <!-- Panel Acordeón Institucional (Presentación, Perfil, Misión, Visión, Objetivo, Historia) -->
                <div class="code-window-panel overflow-hidden">
                    <div class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-5 py-3.5 border-b border-institucional-gold/40 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold"></span>
                            <span class="font-code text-xs text-institucional-gold font-medium uppercase tracking-wider">
                                &lt;/&gt; Identidad Institucional
                            </span>
                        </div>
                        <span class="font-code text-[11px] text-slate-300">Haz clic para desplegar</span>
                    </div>

                    <div class="divide-y divide-slate-200/80 bg-white/95" id="acordeon-nosotros">
                        <?php foreach ($itemsAcordeonNosotros as $idxAc => $itemAc): ?>
                            <?php
                                if ($itemAc['contenido'] === '') {
                                    continue;
                                }
                                $abiertoPorDefecto = ($idxAc === 0);
                            ?>
                            <div class="acordeon-item">
                                <button type="button"
                                        aria-expanded="<?= $abiertoPorDefecto ? 'true' : 'false' ?>"
                                        class="btn-acordeon-nosotros w-full px-5 py-4 flex items-center justify-between gap-3 text-left hover:bg-slate-50 transition-colors focus:outline-none">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-lg bg-institucional-navy/10 text-institucional-navy flex items-center justify-center text-xs flex-shrink-0">
                                            <i class="<?= $itemAc['icono'] ?>"></i>
                                        </span>
                                        <span class="font-display font-bold text-base text-institucional-navy">
                                            <?= htmlspecialchars($itemAc['titulo']) ?>
                                        </span>
                                    </div>
                                    <span class="lbl-acordeon-toggle font-code text-xs text-blue-600 font-semibold flex items-center gap-1">
                                        <span><?= $abiertoPorDefecto ? 'Ver menos' : 'Ver más' ?></span>
                                        <i class="fas <?= $abiertoPorDefecto ? 'fa-chevron-up' : 'fa-chevron-down' ?> text-[10px]"></i>
                                    </span>
                                </button>

                                <div class="panel-acordeon-nosotros px-5 pb-5 <?= $abiertoPorDefecto ? '' : 'hidden' ?>">
                                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/80">
                                        <div class="quill-content text-xs sm:text-sm text-slate-600 leading-relaxed line-clamp-5">
                                            <?= $itemAc['contenido'] ?>
                                        </div>

                                        <div class="mt-3 pt-2.5 border-t border-slate-200/70 flex justify-end">
                                            <button type="button"
                                                    class="btn-abrir-modal-info inline-flex items-center gap-1.5 text-xs font-bold text-institucional-navy hover:text-yellow-600 transition-colors"
                                                    data-titulo="<?= htmlspecialchars($itemAc['titulo']) ?>"
                                                    data-subtitulo="<?= htmlspecialchars($itemAc['subtitulo']) ?>"
                                                    data-contenido="<?= htmlspecialchars($itemAc['contenido'], ENT_QUOTES, 'UTF-8') ?>">
                                                <span>Leer completo en ventana</span>
                                                <i class="fas fa-expand text-[10px]"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Tarjeta Ejecutiva Compacta: Dirección de Carrera y Palabras de Bienvenida -->
                <?php if ($director !== null): ?>
                    <?php
                        $nomDir         = trim((string) ($director['nom_acad'] ?? 'Dirección de Carrera'));
                        $catDir         = trim((string) ($director['cat_nombre'] ?? 'Director/a'));
                        $urlFotoDir     = obtenerUrlFotoDocente($director['foto_acad'] ?? ($infoInst['foto_director'] ?? null));
                        $inicDir        = obtenerInicialesDocente($nomDir);
                        $msgDirHtml     = formatearTextoQuill($infoInst['mensaje_director'] ?? '');
                    ?>
                    <div class="tech-card-spotlight p-5 sm:p-6 relative overflow-hidden">
                        <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-institucional-gold via-yellow-400 to-institucional-navy"></div>

                        <div class="flex items-center gap-3.5 mb-3">
                            <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-institucional-gold shadow-md bg-institucional-navy flex items-center justify-center flex-shrink-0">
                                <?php if ($urlFotoDir !== null): ?>
                                    <img src="<?= htmlspecialchars($urlFotoDir) ?>"
                                         alt="<?= htmlspecialchars($nomDir) ?>"
                                         class="w-full h-full object-cover object-top"
                                         onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                                    <span class="hidden font-display font-bold text-lg text-institucional-gold"><?= htmlspecialchars($inicDir) ?></span>
                                <?php else: ?>
                                    <span class="font-display font-bold text-lg text-institucional-gold"><?= htmlspecialchars($inicDir) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0">
                                <span class="font-code text-[10px] uppercase tracking-wider text-amber-900 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-300 font-bold">
                                    <?= htmlspecialchars($catDir) ?>
                                </span>
                                <h3 class="font-display text-base font-bold text-slate-900 truncate mt-1">
                                    <?= htmlspecialchars($nomDir) ?>
                                </h3>
                            </div>
                        </div>

                        <?php if ($msgDirHtml !== ''): ?>
                            <div class="quill-content text-xs text-slate-600 leading-relaxed line-clamp-3 bg-white/80 p-3.5 rounded-xl border border-blue-200/60 mb-3">
                                <?= $msgDirHtml ?>
                            </div>
                        <?php endif; ?>

                        <div class="flex items-center justify-between gap-2 pt-1">
                            <?php if ($msgDirHtml !== ''): ?>
                                <button type="button"
                                        class="btn-abrir-modal-info text-xs font-bold text-institucional-navy hover:text-yellow-600 flex items-center gap-1 transition-colors"
                                        data-titulo="Palabras de Bienvenida · Dirección de Carrera"
                                        data-subtitulo="<?= htmlspecialchars($nomDir) ?>"
                                        data-contenido="<?= htmlspecialchars($msgDirHtml, ENT_QUOTES, 'UTF-8') ?>">
                                    <i class="fas fa-quote-left text-institucional-gold text-[10px]"></i>
                                    <span>Leer bienvenida</span>
                                </button>
                            <?php endif; ?>

                            <a href="/carrera/autoridades" class="text-xs font-semibold text-slate-600 hover:text-institucional-navy ml-auto">
                                Ver autoridades &rarr;
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

            </aside>

        </div>
    </section>


    <!-- =========================================================================
         3. BLOQUE COMPACTO MULTIPESTAÑA: EXTENSIÓN, COMUNICADOS Y LOGROS
         (Todo en un solo contenedor con Tabs para evitar scroll largo)
         ========================================================================= -->
    <section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="code-window-panel overflow-hidden">
            
            <!-- Cabecera con Selector de Pestañas (Tabs) -->
            <div class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-4 border-b border-institucional-gold/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                    </div>
                    <span class="font-code text-[13px] text-institucional-gold font-medium pl-2 border-l border-white/20">
                        &lt;/&gt; Actualidad y Comunidad Universitaria
                    </span>
                </div>

                <!-- Botones de Pestañas -->
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" data-tab="tab-extension" class="btn-home-tab px-4 py-1.5 rounded-full text-xs font-bold bg-institucional-gold text-slate-950 transition-all shadow-sm">
                        <i class="fas fa-camera-retro mr-1"></i> Extensión (<?= count($listaExtension) ?>)
                    </button>
                    <button type="button" data-tab="tab-comunicados" class="btn-home-tab px-4 py-1.5 rounded-full text-xs font-medium bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-all">
                        <i class="fas fa-bullhorn mr-1"></i> Comunicados (<?= count($listaComunicados) ?>)
                    </button>
                    <button type="button" data-tab="tab-logros" class="btn-home-tab px-4 py-1.5 rounded-full text-xs font-medium bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-all">
                        <i class="fas fa-trophy mr-1"></i> Logros (<?= count($listaLogros) ?>)
                    </button>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                
                <!-- TAB 1: Actividades de Extensión -->
                <div id="tab-extension" class="home-tab-pane space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($listaExtension as $ext): ?>
                            <?php
                                $idExt       = (int) ($ext['id_ext'] ?? 0);
                                $nomExt      = trim((string) ($ext['nom_ext'] ?? 'Actividad'));
                                $fechExt     = formatearFechaCortaEsp($ext['fech_ext'] ?? null);
                                $nomCatExt   = !empty($ext['nom_cat']) ? trim((string) $ext['nom_cat']) : 'Extensión';
                                $portadaExt  = $ext['portada']['nom_foto'] ?? ($ext['fotos'][0]['nom_foto'] ?? null);
                                $urlFotoExt  = !empty($portadaExt) ? '/uploads/img/extension/' . rawurlencode((string) $portadaExt) : null;
                                $descExtHtml = formatearTextoQuill($ext['desc_ext'] ?? '');
                            ?>
                            <article class="tech-card flex flex-col justify-between overflow-hidden group">
                                <div>
                                    <?php if ($urlFotoExt !== null): ?>
                                        <a href="/extension#actividad-<?= $idExt ?>" class="block h-44 bg-slate-950 overflow-hidden relative border-b border-slate-200">
                                            <img src="<?= htmlspecialchars($urlFotoExt) ?>"
                                                 alt="<?= htmlspecialchars($nomExt) ?>"
                                                 loading="lazy"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            <span class="absolute top-3 left-3 font-code px-3 py-0.5 rounded-full text-[11px] font-bold bg-slate-950/80 text-institucional-gold border border-institucional-gold/40">
                                                <?= htmlspecialchars($nomCatExt) ?>
                                            </span>
                                        </a>
                                    <?php endif; ?>
                                    <div class="p-5">
                                        <span class="font-code text-xs text-slate-500 block mb-1.5">
                                            <i class="far fa-calendar text-institucional-navy mr-1"></i><?= htmlspecialchars($fechExt) ?>
                                        </span>
                                        <h3 class="font-display text-base font-bold text-slate-900 leading-snug line-clamp-2 group-hover:text-institucional-navy">
                                            <a href="/extension#actividad-<?= $idExt ?>"><?= htmlspecialchars($nomExt) ?></a>
                                        </h3>
                                        <?php if ($descExtHtml !== ''): ?>
                                            <div class="texto-resumen-docente text-xs text-slate-600 mt-2"><?= $descExtHtml ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="px-5 py-3 bg-slate-50 border-t border-slate-200/80 flex justify-end">
                                    <a href="/extension#actividad-<?= $idExt ?>" class="text-xs font-bold text-institucional-navy hover:text-yellow-600">
                                        Ver álbum &rarr;
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-right">
                        <a href="/extension" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-institucional-navy text-white text-xs font-semibold hover:bg-institucional-navy-dark transition-all">
                            <span>Ver todas las actividades de Extensión</span>
                            <i class="fas fa-arrow-right text-institucional-gold text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- TAB 2: Comunicados Recientes -->
                <div id="tab-comunicados" class="home-tab-pane hidden space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($listaComunicados as $com): ?>
                            <?php
                                $titCom      = trim((string) ($com['nom_com'] ?? 'Comunicado'));
                                $fechCom     = formatearFechaCortaEsp($com['fech_com'] ?? null);
                                $fotoCom     = trim((string) ($com['foto_com'] ?? ''));
                                $urlFotoCom  = ($fotoCom !== '' && strtolower($fotoCom) !== 'default.png')
                                    ? '/uploads/img/comunicados/' . rawurlencode($fotoCom)
                                    : null;
                                $descComHtml = formatearTextoQuill($com['desc_com'] ?? '');
                            ?>
                            <article class="tech-card flex flex-col justify-between overflow-hidden group">
                                <div>
                                    <?php if ($urlFotoCom !== null): ?>
                                        <div class="h-44 bg-slate-950 overflow-hidden border-b border-slate-200">
                                            <img src="<?= htmlspecialchars($urlFotoCom) ?>"
                                                 alt="<?= htmlspecialchars($titCom) ?>"
                                                 loading="lazy"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                    <?php endif; ?>
                                    <div class="p-5">
                                        <span class="font-code text-xs text-institucional-navy font-semibold block mb-1.5">
                                            <i class="far fa-calendar text-institucional-gold mr-1"></i><?= htmlspecialchars($fechCom) ?>
                                        </span>
                                        <h3 class="font-display text-base font-bold text-slate-900 leading-snug line-clamp-2">
                                            <?= htmlspecialchars($titCom) ?>
                                        </h3>
                                        <?php if ($descComHtml !== ''): ?>
                                            <div class="texto-resumen-docente text-xs text-slate-600 mt-2"><?= $descComHtml ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="px-5 py-3 bg-slate-50 border-t border-slate-200/80 flex justify-end">
                                    <a href="/comunicados" class="text-xs font-bold text-institucional-navy hover:text-yellow-600">
                                        Ver en tablón &rarr;
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-right">
                        <a href="/comunicados" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-institucional-navy text-white text-xs font-semibold hover:bg-institucional-navy-dark transition-all">
                            <span>Ir al Tablón de Comunicados</span>
                            <i class="fas fa-arrow-right text-institucional-gold text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- TAB 3: Logros Institucionales -->
                <div id="tab-logros" class="home-tab-pane hidden space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        <?php foreach ($listaLogros as $logroItem): ?>
                            <?php
                                $posLog  = trim((string) ($logroItem['posicion'] ?? 'Reconocimiento'));
                                $nomLog  = trim((string) ($logroItem['nom_log'] ?? 'Logro Académico'));
                                $lugLog  = !empty($logroItem['lugar_log']) ? trim((string) $logroItem['lugar_log']) : 'Bolivia';
                                $anioLog = !empty($logroItem['fech_log']) ? date('Y', strtotime((string) $logroItem['fech_log'])) : '';
                            ?>
                            <a href="/carrera/logros" class="tech-card p-5 flex flex-col justify-between group">
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-3">
                                        <span class="font-code px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-900 border border-amber-300">
                                            <i class="fas fa-trophy text-institucional-gold mr-1"></i><?= htmlspecialchars($posLog) ?>
                                        </span>
                                        <span class="font-display font-extrabold text-lg text-institucional-navy"><?= htmlspecialchars($anioLog) ?></span>
                                    </div>
                                    <h3 class="font-display text-sm sm:text-base font-bold text-slate-900 leading-snug group-hover:text-institucional-navy line-clamp-2">
                                        <?= htmlspecialchars($nomLog) ?>
                                    </h3>
                                </div>
                                <span class="text-xs text-slate-500 mt-3 pt-2 border-t border-slate-200/70 flex items-center gap-1.5">
                                    <i class="fas fa-location-dot text-institucional-gold"></i>
                                    <?= htmlspecialchars($lugLog) ?>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-right">
                        <a href="/carrera/logros" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-institucional-navy text-white text-xs font-semibold hover:bg-institucional-navy-dark transition-all">
                            <span>Ver Línea de Tiempo de Logros</span>
                            <i class="fas fa-arrow-right text-institucional-gold text-[10px]"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- =========================================================================
         4. BANNER FINAL DE CONVERSIÓN ("¿LISTO PARA ESTUDIAR INGENIERÍA INFORMÁTICA?")
         ========================================================================= -->
    <section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="aurora-hero-navy rounded-3xl p-8 sm:p-10 lg:p-12 text-white border-2 border-institucional-gold/60 shadow-2xl relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 pointer-events-none" 
                 style="background-image: linear-gradient(to right, #ffffff 1px, transparent 1px), linear-gradient(to bottom, #ffffff 1px, transparent 1px); background-size: 32px 32px;">
            </div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                <div class="max-w-2xl">
                    <span class="font-code text-xs uppercase tracking-widest text-institucional-gold block mb-2">
                        &lt;/&gt; Tu Futuro Profesional en Tecnología
                    </span>
                    <h2 class="font-display text-2xl sm:text-4xl font-extrabold text-white leading-tight">
                        ¿Listo para estudiar <span class="text-institucional-gold">Ingeniería Informática</span>?
                    </h2>
                    <p class="text-sm sm:text-base text-slate-200 mt-3 leading-relaxed">
                        Consulta los requisitos de la Prueba de Admisión y del Curso Preuniversitario, becas estudiantiles y modalidades de ingreso directo.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
                    <a href="/admision"
                       class="inline-flex items-center gap-2 px-6 py-3.5 rounded-full bg-institucional-gold hover:bg-yellow-400 text-slate-950 font-bold text-sm transition-all shadow-lg">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Ir a Admisión</span>
                    </a>
                    <a href="/contacto"
                       class="inline-flex items-center gap-2 px-5 py-3.5 rounded-full bg-white/10 hover:bg-white/20 text-white border border-white/25 font-semibold text-sm transition-all">
                        <i class="fas fa-phone-alt text-institucional-gold"></i>
                        <span>Contactar a la Carrera</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>


<!-- =========================================================================
     5. MODAL AUTOMÁTICO DEL COMUNICADO AL INICIAR LA WEB (`GET /`)
     (Muestra el Comunicado Fijado o el Último Comunicado Publicado)
     ========================================================================= -->
<?php if ($comunicadoModal !== null): ?>
    <?php
        $modTitulo   = trim((string) ($comunicadoModal['nom_com'] ?? 'Comunicado Oficial'));
        $modFecha    = formatearFechaCortaEsp($comunicadoModal['fech_com'] ?? null);
        $modDescHtml = formatearTextoQuill($comunicadoModal['desc_com'] ?? '');
        $modFotoRaw  = trim((string) ($comunicadoModal['foto_com'] ?? ''));
        $modUrlFoto  = ($modFotoRaw !== '' && strtolower($modFotoRaw) !== 'default.png')
            ? '/uploads/img/comunicados/' . rawurlencode($modFotoRaw)
            : null;
        $modPdfRaw   = trim((string) ($comunicadoModal['doc_com'] ?? ''));
        $modUrlPdf   = $modPdfRaw !== ''
            ? '/uploads/pdf/comunicados/' . rawurlencode($modPdfRaw)
            : null;
    ?>
    <div id="modal-comunicado-inicio" class="fixed inset-0 z-50 hidden bg-slate-950/85 backdrop-blur-md flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div id="modal-comunicado-inicio-card" class="bg-white rounded-2xl border-2 border-institucional-gold shadow-2xl max-w-2xl w-full overflow-hidden transform scale-95 transition-transform duration-300 max-h-[92vh] flex flex-col">
            
            <!-- Cabecera del Modal -->
            <div class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-3.5 border-b-2 border-institucional-gold flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold animate-ping"></span>
                    <span class="font-code text-xs text-institucional-gold font-bold uppercase tracking-wider">
                        &lt;/&gt; Comunicado Oficial · Ingeniería Informática
                    </span>
                </div>
                <button type="button" id="btn-cerrar-comunicado-inicio-x" class="text-slate-300 hover:text-institucional-gold transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Cuerpo del Modal -->
            <div class="p-6 sm:p-8 overflow-y-auto space-y-4">
                <div class="text-center">
                    <?php if ($modFecha !== ''): ?>
                        <span class="font-code inline-block px-3 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-900 border border-amber-300 mb-2">
                            <i class="far fa-calendar mr-1"></i><?= htmlspecialchars($modFecha) ?>
                        </span>
                    <?php endif; ?>
                    <h3 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 leading-snug">
                        <?= htmlspecialchars($modTitulo) ?>
                    </h3>
                </div>

                <?php if ($modUrlFoto !== null): ?>
                    <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-950 text-center shadow-md">
                        <img src="<?= htmlspecialchars($modUrlFoto) ?>"
                             alt="<?= htmlspecialchars($modTitulo) ?>"
                             class="max-h-[54vh] w-auto mx-auto object-contain">
                    </div>
                <?php endif; ?>

                <?php if ($modDescHtml !== ''): ?>
                    <div class="quill-content text-sm sm:text-base text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                        <?= $modDescHtml ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pie del Modal: "Más comunicados" y "Cerrar" -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex flex-wrap items-center justify-between gap-3 flex-shrink-0">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="/comunicados"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-institucional-navy hover:bg-institucional-navy-dark text-white text-xs font-bold transition-all shadow-md">
                        <i class="fas fa-bullhorn text-institucional-gold"></i>
                        <span>Más comunicados</span>
                    </a>

                    <?php if ($modUrlPdf !== null): ?>
                        <a href="<?= htmlspecialchars($modUrlPdf) ?>"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full bg-red-50 hover:bg-red-600 text-red-800 hover:text-white border border-red-200 text-xs font-semibold transition-all">
                            <i class="fas fa-file-pdf"></i>
                            <span>Descargar PDF</span>
                        </a>
                    <?php endif; ?>
                </div>

                <button type="button"
                        id="btn-cerrar-comunicado-inicio"
                        class="px-5 py-2.5 rounded-full bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold transition-colors ml-auto">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>


<!-- =========================================================================
     6. MODAL DE LECTURA AMPLIA PARA "ACERCA DE NOSOTROS" Y BIENVENIDA
     ========================================================================= -->
<div id="modal-info-institucional" class="fixed inset-0 z-50 hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-200">
    <div id="modal-info-institucional-card" class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-2xl w-full overflow-hidden transform scale-95 transition-transform duration-200 max-h-[90vh] flex flex-col">
        <div class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-3.5 border-b-2 border-institucional-gold flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold"></span>
                <span id="modal-info-subtitulo" class="font-code text-xs text-institucional-gold font-medium uppercase tracking-wider"></span>
            </div>
            <button type="button" id="btn-cerrar-modal-info" class="text-slate-300 hover:text-institucional-gold transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="p-6 sm:p-8 overflow-y-auto">
            <h3 id="modal-info-titulo" class="font-display text-xl sm:text-2xl font-bold text-slate-900 pb-4 mb-5 border-b border-slate-200"></h3>
            <div id="modal-info-contenido" class="quill-content text-sm sm:text-base text-slate-700 leading-relaxed bg-slate-50 p-5 rounded-xl border border-slate-200/80"></div>
        </div>

        <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex justify-end flex-shrink-0">
            <button type="button" id="btn-cerrar-modal-info-inf" class="px-5 py-2 rounded-full bg-institucional-navy hover:bg-institucional-navy-dark text-white text-xs font-semibold transition-colors">
                Cerrar
            </button>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        // =====================================================================
        // 1. CARRUSEL DE PANTALLA COMPLETA DEL HERO
        // =====================================================================
        const slides = document.querySelectorAll('#hero-bg-carousel .hero-slide');
        const dots = document.querySelectorAll('.hero-dot');
        const captionEl = document.getElementById('hero-slide-caption');
        const btnPrev = document.getElementById('hero-prev');
        const btnNext = document.getElementById('hero-next');

        if (slides.length > 1) {
            let actual = 0;
            let timer = null;

            const cambiarSlide = (nuevo) => {
                slides[actual].className = 'hero-slide absolute inset-0 transition-opacity duration-1000 ease-out opacity-0 z-0 pointer-events-none';
                if (dots[actual]) {
                    dots[actual].className = 'hero-dot h-2 rounded-full transition-all w-2 bg-white/50 hover:bg-white';
                }

                actual = (nuevo + slides.length) % slides.length;

                slides[actual].className = 'hero-slide absolute inset-0 transition-opacity duration-1000 ease-out opacity-100 z-10';
                if (dots[actual]) {
                    dots[actual].className = 'hero-dot h-2 rounded-full transition-all w-7 bg-institucional-gold';
                }
                if (captionEl) {
                    captionEl.textContent = slides[actual].getAttribute('data-caption') || 'Ingeniería Informática · UNSXX';
                }
            };

            const iniciarAuto = () => {
                if (timer) clearInterval(timer);
                timer = setInterval(() => cambiarSlide(actual + 1), 5500);
            };

            btnPrev?.addEventListener('click', () => { cambiarSlide(actual - 1); iniciarAuto(); });
            btnNext?.addEventListener('click', () => { cambiarSlide(actual + 1); iniciarAuto(); });
            dots.forEach((dot) => {
                dot.addEventListener('click', () => {
                    cambiarSlide(parseInt(dot.getAttribute('data-slide') || '0', 10));
                    iniciarAuto();
                });
            });

            iniciarAuto();
        }

        // =====================================================================
        // 2. ACORDEÓN INTERACTIVO "ACERCA DE NOSOTROS" (Columna Derecha)
        // =====================================================================
        const botonesAcordeon = document.querySelectorAll('.btn-acordeon-nosotros');
        botonesAcordeon.forEach((btn) => {
            btn.addEventListener('click', () => {
                const item = btn.closest('.acordeon-item');
                const panel = item?.querySelector('.panel-acordeon-nosotros');
                const lbl = btn.querySelector('.lbl-acordeon-toggle');
                const estabaAbierto = btn.getAttribute('aria-expanded') === 'true';

                // Cerramos todos los demás para evitar que la columna crezca
                document.querySelectorAll('#acordeon-nosotros .acordeon-item').forEach((otroItem) => {
                    const otroBtn = otroItem.querySelector('.btn-acordeon-nosotros');
                    const otroPanel = otroItem.querySelector('.panel-acordeon-nosotros');
                    const otroLbl = otroItem.querySelector('.lbl-acordeon-toggle');
                    otroBtn?.setAttribute('aria-expanded', 'false');
                    otroPanel?.classList.add('hidden');
                    if (otroLbl) {
                        otroLbl.innerHTML = '<span>Ver más</span> <i class="fas fa-chevron-down text-[10px]"></i>';
                    }
                });

                if (!estabaAbierto && panel) {
                    btn.setAttribute('aria-expanded', 'true');
                    panel.classList.remove('hidden');
                    if (lbl) {
                        lbl.innerHTML = '<span>Ver menos</span> <i class="fas fa-chevron-up text-[10px]"></i>';
                    }
                }
            });
        });

        // =====================================================================
        // 3. PESTAÑAS (TABS) DE ACTUALIDAD UNIVERSITARIA
        // =====================================================================
        const tabBtns = document.querySelectorAll('.btn-home-tab');
        const tabPanes = document.querySelectorAll('.home-tab-pane');

        tabBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-tab');

                tabBtns.forEach((b) => {
                    b.className = 'btn-home-tab px-4 py-1.5 rounded-full text-xs font-medium bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-all';
                });
                btn.className = 'btn-home-tab px-4 py-1.5 rounded-full text-xs font-bold bg-institucional-gold text-slate-950 transition-all shadow-sm';

                tabPanes.forEach((pane) => {
                    pane.classList.toggle('hidden', pane.id !== targetId);
                });
            });
        });

        // =====================================================================
        // 4. MODAL DE LECTURA AMPLIA PARA "ACERCA DE NOSOTROS"
        // =====================================================================
        const modalInfo = document.getElementById('modal-info-institucional');
        const cardInfo = document.getElementById('modal-info-institucional-card');
        const titInfo = document.getElementById('modal-info-titulo');
        const subInfo = document.getElementById('modal-info-subtitulo');
        const contInfo = document.getElementById('modal-info-contenido');

        const cerrarModalInfo = () => {
            if (!modalInfo || !cardInfo) return;
            modalInfo.classList.add('opacity-0');
            cardInfo.classList.add('scale-95');
            setTimeout(() => modalInfo.classList.add('hidden'), 200);
        };

        document.querySelectorAll('.btn-abrir-modal-info').forEach((btn) => {
            btn.addEventListener('click', () => {
                if (!modalInfo || !cardInfo) return;
                titInfo.textContent = btn.getAttribute('data-titulo') || '';
                subInfo.textContent = '</> ' + (btn.getAttribute('data-subtitulo') || 'Información Institucional');
                contInfo.innerHTML = btn.getAttribute('data-contenido') || '';

                modalInfo.classList.remove('hidden');
                void modalInfo.offsetWidth;
                modalInfo.classList.remove('opacity-0');
                cardInfo.classList.remove('scale-95');
            });
        });

        document.getElementById('btn-cerrar-modal-info')?.addEventListener('click', cerrarModalInfo);
        document.getElementById('btn-cerrar-modal-info-inf')?.addEventListener('click', cerrarModalInfo);
        modalInfo?.addEventListener('click', (e) => {
            if (e.target === modalInfo) cerrarModalInfo();
        });

        // =====================================================================
        // 5. MODAL AUTOMÁTICO DEL COMUNICADO AL INGRESAR A LA WEB
        // =====================================================================
        const modalComInicio = document.getElementById('modal-comunicado-inicio');
        const cardComInicio = document.getElementById('modal-comunicado-inicio-card');
        const btnReabrirHero = document.getElementById('btn-reabrir-comunicado-hero');

        if (modalComInicio && cardComInicio) {
            const abrirModalComunicado = () => {
                modalComInicio.classList.remove('hidden');
                void modalComInicio.offsetWidth;
                modalComInicio.classList.remove('opacity-0');
                cardComInicio.classList.remove('scale-95');
            };

            const cerrarModalComunicado = () => {
                modalComInicio.classList.add('opacity-0');
                cardComInicio.classList.add('scale-95');
                setTimeout(() => modalComInicio.classList.add('hidden'), 250);
            };

            // Disparo automático al cargar la página de inicio
            setTimeout(abrirModalComunicado, 350);

            btnReabrirHero?.addEventListener('click', abrirModalComunicado);
            document.getElementById('btn-cerrar-comunicado-inicio')?.addEventListener('click', cerrarModalComunicado);
            document.getElementById('btn-cerrar-comunicado-inicio-x')?.addEventListener('click', cerrarModalComunicado);
            modalComInicio.addEventListener('click', (e) => {
                if (e.target === modalComInicio) cerrarModalComunicado();
            });
        }
    });
</script>