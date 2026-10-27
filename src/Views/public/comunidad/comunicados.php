<?php
/**
 * Vista: Comunicados y Avisos Oficiales (src/Views/public/comunidad/comunicados.php)
 * Variables inyectadas por HomeController::comunicados():
 * @var array $announcements    Lista de comunicados de la página actual (máx. 10)
 * @var int   $totalComunicados Total global de comunicados publicados
 * @var int   $paginaActual     Página actual (1..N)
 * @var int   $totalPaginas     Total de páginas calculadas
 * @var int   $porPagina        Cantidad por página (10)
 */

$comunicados = $announcements ?? [];
usort($comunicados, static function (array $a, array $b): int {
    $fijadoCmp = ((int) ($b['fijado'] ?? 0)) <=> ((int) ($a['fijado'] ?? 0));
    if ($fijadoCmp !== 0) {
        return $fijadoCmp;
    }
    return strcmp((string) ($b['fech_com'] ?? ''), (string) ($a['fech_com'] ?? ''));
});

$totalGlobal = (int) ($totalComunicados ?? count($comunicados));
$pagActual = max(1, (int) ($paginaActual ?? 1));
$totPaginas = max(1, (int) ($totalPaginas ?? 1));
$limitePorPagina = (int) ($porPagina ?? 10);

/**
 * Helper global para sanitizar y formatear textos que pueden venir de Quill.js (HTML) o texto plano
 */
if (!function_exists('formatearTextoQuill')) {
    function formatearTextoQuill(?string $texto): string
    {
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

/**
 * Helper para formatear fechas en español (ej. "05 de Octubre, 2026")
 */
if (!function_exists('formatearFechaComunicado')) {
    function formatearFechaComunicado(?string $fechaIso): array
    {
        $meses = [
            1 => 'Ene',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Abr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Ago',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dic'
        ];
        $mesesLargos = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        $ts = !empty($fechaIso) ? strtotime($fechaIso) : false;
        if (!$ts) {
            return ['dia' => '--', 'mes_corto' => '---', 'anio' => '----', 'completa' => 'Fecha no disponible'];
        }

        $dia = date('d', $ts);
        $numMes = (int) date('n', $ts);
        $anio = date('Y', $ts);

        return [
            'dia' => $dia,
            'mes_corto' => $meses[$numMes] ?? 'Mes',
            'anio' => $anio,
            'completa' => "{$dia} de " . ($mesesLargos[$numMes] ?? '') . ", {$anio}"
        ];
    }
}

/**
 * Helpers para rutas de imagen (/uploads/img/comunicados/) y PDF (/uploads/pdf/comunicados/)
 */
if (!function_exists('obtenerUrlFotoComunicado')) {
    function obtenerUrlFotoComunicado(?string $archivo): ?string
    {
        $limpio = trim((string) $archivo);
        if ($limpio === '' || strtolower($limpio) === 'default.png') {
            return null;
        }
        return '/uploads/img/comunicados/' . rawurlencode($limpio);
    }
}

if (!function_exists('obtenerUrlPdfComunicado')) {
    function obtenerUrlPdfComunicado(?string $archivo): ?string
    {
        $limpio = trim((string) $archivo);
        if ($limpio === '') {
            return null;
        }
        return '/uploads/pdf/comunicados/' . rawurlencode($limpio);
    }
}
?>

<!-- HERO SECTION: Aurora Navy Glow -->
<section
    class="aurora-hero-navy text-white py-14 sm:py-16 border-b-4 border-institucional-gold relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 pointer-events-none"
        style="background-image: linear-gradient(to right, #ffffff 1px, transparent 1px), linear-gradient(to bottom, #ffffff 1px, transparent 1px); background-size: 36px 36px;">
    </div>

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div class="max-w-2xl">
                <div
                    class="inline-flex items-center gap-2.5 px-3.5 py-1 rounded-full bg-white/10 border border-institucional-gold/40 backdrop-blur-md mb-5">
                    <span class="w-2 h-2 rounded-full bg-institucional-gold animate-pulse"></span>
                    <a href="/" class="text-xs font-medium text-slate-200 hover:text-white transition-colors">Inicio</a>
                    <span class="text-xs text-institucional-gold/70">/</span>
                    <span
                        class="font-code text-xs font-medium text-institucional-gold uppercase tracking-wider">&lt;/&gt;
                        comunicados y avisos</span>
                </div>

                <h1 class="font-display text-3xl sm:text-5xl font-bold tracking-tight leading-[1.13] text-white">
                    Comunicados <span class="text-institucional-gold">Oficiales</span>
                </h1>

                <p class="mt-4 text-base text-slate-200 font-light leading-relaxed">
                    Tablón informativo oficial con circulares, comunicados, convocatorias, instructivos y otros avisos
                    de la Carrera de Ingeniería Informática.
                </p>
            </div>

            <!-- Indicador Estadístico Compacto -->
            <div class="relative self-start lg:self-center w-full sm:w-auto">
                <div
                    class="bg-[#0f1f4d]/95 border border-institucional-gold/40 rounded-xl p-5 flex items-center gap-5 backdrop-blur-xl shadow-2xl">
                    <div
                        class="w-13 h-13 p-3.5 rounded-xl bg-institucional-gold text-slate-900 flex items-center justify-center text-xl flex-shrink-0 shadow-[0_0_20px_rgba(234,179,8,0.4)]">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div class="pr-2">
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span
                                class="font-display font-extrabold text-2xl sm:text-3xl text-white"><?= $totalGlobal ?></span>
                            <span class="text-s text-slate-200 font-medium">Comunicados</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CONTENIDO PRINCIPAL: Lienzo Claro con Luces Aurora -->
<section class="relative py-12 sm:py-16 overflow-hidden">
    <!-- Luces Aurora difuminadas -->
    <div
        class="absolute top-10 left-1/4 -translate-x-1/2 w-[36rem] h-[24rem] bg-blue-600/10 rounded-full blur-3xl pointer-events-none">
    </div>
    <div
        class="absolute top-32 right-8 w-[28rem] h-[22rem] bg-institucional-gold/15 rounded-full blur-3xl pointer-events-none">
    </div>
    <div
        class="absolute bottom-20 left-1/3 w-[34rem] h-[24rem] bg-institucional-navy/10 rounded-full blur-3xl pointer-events-none">
    </div>

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-10">

        <div class="code-window-panel overflow-hidden">
            <!-- Barra Superior Estilo Consola -->
            <div
                class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-3.5 border-b border-institucional-gold/40 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                    </div>
                    <span
                        class="font-code text-[13px] text-institucional-gold font-medium pl-2 border-l border-white/20">
                        &lt;/&gt; Tablón Oficial de Comunicados
                    </span>
                </div>
                <span class="font-code text-xs text-slate-200">
                    Página <?= $pagActual ?> de <?= $totPaginas ?> · Mostrando <?= count($comunicados) ?> de
                    <?= $totalGlobal ?> avisos
                </span>
            </div>

            <div class="p-6 sm:p-8 lg:p-10 space-y-6">
                <?php if (!empty($comunicados)): ?>
                    <?php foreach ($comunicados as $index => $com): ?>
                        <?php
                        $idCom = (int) ($com['id_com'] ?? 0);
                        $titulo = trim((string) ($com['nom_com'] ?? 'Comunicado Oficial'));
                        $descRaw = trim((string) ($com['desc_com'] ?? ''));
                        $descHtml = formatearTextoQuill($descRaw);
                        $tieneDesc = ($descHtml !== '');
                        $urlFoto = obtenerUrlFotoComunicado($com['foto_com'] ?? null);
                        $urlPdf = obtenerUrlPdfComunicado($com['doc_com'] ?? null);
                        $fechaInfo = formatearFechaComunicado($com['fech_com'] ?? null);

                        // Solo el comunicado con fijado == 1 (máximo 1) se resalta como fijado arriba
                        $esRecienteDestacado = ((int) ($com['fijado'] ?? 0) === 1);

                        $modalComAttrs = sprintf(
                            'data-titulo="%s" data-fecha="%s" data-descripcion="%s" data-foto="%s" data-pdf="%s"',
                            htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($fechaInfo['completa'], ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($descHtml, ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($urlFoto ?? '', ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($urlPdf ?? '', ENT_QUOTES, 'UTF-8')
                        );
                        ?>

                        <article
                            class="tarjeta-comunicado <?= $esRecienteDestacado ? 'tech-card-spotlight' : 'tech-card' ?> relative overflow-hidden group">
                            <div
                                class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r <?= $esRecienteDestacado ? 'from-institucional-gold via-yellow-400 to-institucional-navy' : 'from-institucional-navy via-blue-600 to-institucional-gold' ?>">
                            </div>

                            <div class="p-6 sm:p-7 flex flex-col lg:flex-row gap-6 items-start">

                                <!-- Bloque de Fecha Estilo Calendario Institucional -->
                                <div
                                    class="flex lg:flex-col items-center justify-center gap-2 lg:gap-0.5 px-4 py-2.5 lg:w-24 lg:py-4 rounded-2xl bg-institucional-navy text-white border-2 border-institucional-gold/60 shadow-md flex-shrink-0">
                                    <span
                                        class="font-display text-xl lg:text-3xl font-extrabold text-institucional-gold leading-none">
                                        <?= htmlspecialchars($fechaInfo['dia']) ?>
                                    </span>
                                    <span class="font-code text-xs uppercase tracking-wider font-semibold text-white">
                                        <?= htmlspecialchars($fechaInfo['mes_corto']) ?>
                                    </span>
                                    <span class="font-code text-[11px] text-slate-300">
                                        <?= htmlspecialchars($fechaInfo['anio']) ?>
                                    </span>
                                </div>

                                <!-- Cuerpo Central del Comunicado -->
                                <div class="flex-1 min-w-0 w-full">
                                    <!-- Badges de estado y tipo de adjunto -->
                                    <div class="flex flex-wrap items-center gap-2 mb-2.5">
                                        <?php if ($esRecienteDestacado): ?>
                                            <span
                                                class="font-code inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-900 border border-amber-300 shadow-sm">
                                                <i class="fas fa-thumbtack text-institucional-gold rotate-45 text-[10px]"></i>
                                                Comunicado Fijado
                                            </span>
                                        <?php else: ?>
                                            <span
                                                class="font-code inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-blue-50 text-institucional-navy border border-blue-200">
                                                <i class="fas fa-bullhorn text-[10px]"></i> Comunicado Oficial
                                            </span>
                                        <?php endif; ?>

                                        <?php if ($urlFoto !== null): ?>
                                            <span
                                                class="font-code inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                                <i class="far fa-image text-institucional-navy text-[10px]"></i> Afiche
                                            </span>
                                        <?php endif; ?>

                                        <?php if ($urlPdf !== null): ?>
                                            <span
                                                class="font-code inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-red-50 text-red-800 border border-red-200">
                                                <i class="far fa-file-pdf text-red-600 text-[10px]"></i> Documento PDF
                                            </span>
                                        <?php endif; ?>

                                        <span class="font-code text-xs text-slate-400 ml-auto hidden sm:inline">
                                            <i class="far fa-calendar mr-1"></i><?= htmlspecialchars($fechaInfo['completa']) ?>
                                        </span>
                                    </div>

                                    <!-- Título del Comunicado -->
                                    <h2
                                        class="font-display text-xl sm:text-2xl font-bold text-slate-900 leading-snug mb-3 group-hover:text-institucional-navy transition-colors">
                                        <button type="button"
                                            class="btn-abrir-modal-comunicado text-left hover:underline focus:outline-none"
                                            <?= $modalComAttrs ?>>
                                            <?= htmlspecialchars($titulo) ?>
                                        </button>
                                    </h2>

                                    <!-- Descripción enriquecida (Quill.js) con truncado a 3 líneas y detección automática de "Ver más" -->
                                    <?php if ($tieneDesc): ?>
                                        <div class="bg-slate-900/[0.025] rounded-xl p-4 border border-slate-200/80 mb-4">
                                            <div
                                                class="texto-resumen-comunicado quill-content text-sm text-slate-600 leading-relaxed line-clamp-3">
                                                <?= $descHtml ?>
                                            </div>
                                            <div
                                                class="contenedor-ver-mas-comunicado hidden mt-2.5 pt-2 border-t border-slate-200/60 flex justify-end">
                                                <button type="button"
                                                    class="btn-abrir-modal-comunicado inline-flex items-center gap-1.5 text-xs font-semibold text-institucional-navy hover:text-yellow-600 transition-colors"
                                                    <?= $modalComAttrs ?>>
                                                    <span>Ver más</span>
                                                    <i class="fas fa-angle-down text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Barra de Acciones del Comunicado -->
                                    <div
                                        class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-slate-200/70">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <?php if ($urlFoto !== null): ?>
                                                <button type="button"
                                                    class="btn-abrir-modal-comunicado inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white hover:bg-slate-100 text-institucional-navy border border-slate-300 text-xs font-semibold transition-all shadow-sm"
                                                    <?= $modalComAttrs ?>>
                                                    <i class="fas fa-image text-institucional-gold text-xs"></i>
                                                    <span>Ver afiche</span>
                                                </button>
                                            <?php endif; ?>

                                            <?php if ($urlPdf !== null): ?>
                                                <button type="button"
                                                    class="btn-previsualizar-pdf inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white hover:bg-slate-100 text-institucional-navy border border-slate-300 text-xs font-semibold transition-all shadow-sm"
                                                    data-pdf="<?= htmlspecialchars($urlPdf) ?>"
                                                    data-titulo="<?= htmlspecialchars($titulo) ?>">
                                                    <i class="fas fa-file-pdf text-red-600 text-xs"></i>
                                                    <span>Ver PDF</span>
                                                </button>

                                                <a href="<?= htmlspecialchars($urlPdf) ?>"
                                                    download="<?= htmlspecialchars($titulo . '.pdf') ?>" target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium transition-all">
                                                    <i class="fas fa-download text-[11px]"></i>
                                                    <span>Descargar PDF</span>
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                        <button type="button"
                                            class="btn-abrir-modal-comunicado inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-institucional-navy hover:bg-institucional-navy-dark text-white text-xs font-medium transition-all shadow-sm ml-auto"
                                            <?= $modalComAttrs ?>>
                                            <i class="fas fa-expand text-institucional-gold text-[11px]"></i>
                                            <span>Abrir comunicado</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Miniatura del Afiche a la derecha (si el comunicado tiene foto_com) -->
                                <?php if ($urlFoto !== null): ?>
                                    <button type="button"
                                        class="btn-abrir-modal-comunicado relative w-full lg:w-44 h-44 rounded-2xl overflow-hidden border-2 border-slate-200 group-hover:border-institucional-gold shadow-sm bg-slate-900 flex-shrink-0 focus:outline-none"
                                        title="Ampliar afiche de <?= htmlspecialchars($titulo) ?>" <?= $modalComAttrs ?>>
                                        <img src="<?= htmlspecialchars($urlFoto) ?>" alt="<?= htmlspecialchars($titulo) ?>"
                                            loading="lazy"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            onerror="this.parentElement.classList.add('hidden');">
                                        <span
                                            class="absolute bottom-2 right-2 w-8 h-8 rounded-full bg-slate-900/75 text-institucional-gold flex items-center justify-center text-xs backdrop-blur-sm">
                                            <i class="fas fa-magnifying-glass-plus"></i>
                                        </span>
                                    </button>
                                <?php endif; ?>

                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div
                        class="text-center py-12 bg-white/60 rounded-xl border border-dashed border-slate-300 text-sm text-slate-500">
                        No hay comunicados oficiales publicados en este momento.
                    </div>
                <?php endif; ?>

                <!-- =============================================================
                     PAGINACIÓN INSTITUCIONAL (10 COMUNICADOS POR PÁGINA)
                     ============================================================= -->
                <?php if ($totPaginas > 1): ?>
                    <nav aria-label="Paginación de comunicados"
                        class="pt-6 border-t border-slate-200/90 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <span class="font-code text-xs text-slate-500">
                            Mostrando página <strong class="text-institucional-navy"><?= $pagActual ?></strong> de <strong
                                class="text-institucional-navy"><?= $totPaginas ?></strong> (<?= $limitePorPagina ?> por
                            página)
                        </span>

                        <div class="flex flex-wrap items-center gap-1.5">
                            <!-- Botón Anterior -->
                            <?php if ($pagActual > 1): ?>
                                <a href="/comunicados?page=<?= $pagActual - 1 ?>"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white hover:bg-institucional-navy text-slate-700 hover:text-white border border-slate-200 text-xs font-semibold transition-all shadow-sm">
                                    <i class="fas fa-chevron-left text-[10px]"></i>
                                    <span>Anterior</span>
                                </a>
                            <?php else: ?>
                                <span
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-slate-100 text-slate-400 border border-slate-200 text-xs font-medium cursor-not-allowed">
                                    <i class="fas fa-chevron-left text-[10px]"></i>
                                    <span>Anterior</span>
                                </span>
                            <?php endif; ?>

                            <!-- Números de Página -->
                            <?php
                            $inicioPag = max(1, $pagActual - 2);
                            $finPag = min($totPaginas, $pagActual + 2);
                            ?>
                            <?php for ($p = $inicioPag; $p <= $finPag; $p++): ?>
                                <a href="/comunicados?page=<?= $p ?>"
                                    class="w-8 h-8 rounded-full flex items-center justify-center font-code text-xs font-bold transition-all <?= $p === $pagActual ? 'bg-institucional-navy text-institucional-gold shadow-md border border-institucional-gold/50' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200' ?>">
                                    <?= $p ?>
                                </a>
                            <?php endfor; ?>

                            <!-- Botón Siguiente -->
                            <?php if ($pagActual < $totPaginas): ?>
                                <a href="/comunicados?page=<?= $pagActual + 1 ?>"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white hover:bg-institucional-navy text-slate-700 hover:text-white border border-slate-200 text-xs font-semibold transition-all shadow-sm">
                                    <span>Siguiente</span>
                                    <i class="fas fa-chevron-right text-[10px]"></i>
                                </a>
                            <?php else: ?>
                                <span
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-slate-100 text-slate-400 border border-slate-200 text-xs font-medium cursor-not-allowed">
                                    <span>Siguiente</span>
                                    <i class="fas fa-chevron-right text-[10px]"></i>
                                </span>
                            <?php endif; ?>
                        </div>
                    </nav>
                <?php endif; ?>

            </div>
        </div>

    </div>
</section>

<!-- 1. MODAL DETALLE COMPLETO DEL COMUNICADO (Texto Quill + Afiche + Botón PDF) -->
<div id="modal-detalle-comunicado"
    class="fixed inset-0 z-50 hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-200">
    <div id="modal-detalle-comunicado-card"
        class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-2xl w-full overflow-hidden transform scale-95 transition-transform duration-200 max-h-[90vh] flex flex-col">

        <div
            class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-3.5 border-b-2 border-institucional-gold flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold"></span>
                <span class="font-code text-xs text-institucional-gold font-medium uppercase tracking-wider">&lt;/&gt;
                    comunicado oficial</span>
            </div>
            <button type="button" id="btn-cerrar-modal-comunicado"
                class="text-slate-300 hover:text-institucional-gold transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="p-6 sm:p-8 overflow-y-auto space-y-5">
            <div>
                <span id="modal-comunicado-fecha"
                    class="font-code inline-block px-3 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-institucional-navy border border-blue-200 mb-2"></span>
                <h3 id="modal-comunicado-titulo"
                    class="font-display text-xl sm:text-2xl font-bold text-slate-900 leading-snug"></h3>
            </div>

            <!-- Afiche / Imagen del Comunicado (si tiene) -->
            <div id="modal-comunicado-foto-wrap"
                class="hidden rounded-2xl overflow-hidden border border-slate-200 bg-slate-950 text-center">
                <img id="modal-comunicado-foto" src="" alt="Afiche del Comunicado"
                    class="max-h-[55vh] w-auto mx-auto object-contain">
            </div>

            <!-- Descripción Completa (Quill.js) -->
            <div id="modal-comunicado-desc-wrap" class="hidden">
                <div id="modal-comunicado-descripcion"
                    class="quill-content text-sm sm:text-base text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                </div>
            </div>

            <!-- Pie del Modal -->
            <div class="pt-4 border-t border-slate-200 flex flex-wrap items-center justify-between gap-3">
                <div id="modal-comunicado-acciones-pdf" class="flex flex-wrap items-center gap-2"></div>
                <button type="button" id="btn-cerrar-modal-comunicado-inf"
                    class="px-5 py-2 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors ml-auto">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 2. MODAL VISOR DE PDF INTEGRADO -->
<div id="modal-visor-pdf"
    class="fixed inset-0 z-50 hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 opacity-0 transition-opacity duration-200">
    <div id="modal-visor-pdf-card"
        class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-5xl w-full h-[88vh] flex flex-col overflow-hidden transform scale-95 transition-transform duration-200">
        <div
            class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-5 py-3.5 border-b-2 border-institucional-gold flex items-center justify-between gap-4 flex-shrink-0">
            <div class="flex items-center gap-2.5 min-w-0">
                <i class="fas fa-file-pdf text-institucional-gold"></i>
                <h3 id="modal-pdf-titulo" class="font-display text-sm sm:text-base font-bold text-white truncate"></h3>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <a id="modal-pdf-nueva-pestana" href="#" target="_blank" rel="noopener noreferrer"
                    class="font-code text-xs text-institucional-gold hover:underline flex items-center gap-1">
                    <span>Abrir en pestaña</span>
                    <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
                <button type="button" id="btn-cerrar-visor-pdf"
                    class="text-slate-300 hover:text-institucional-gold transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
        <div class="flex-grow bg-slate-100">
            <iframe id="iframe-visor-pdf" src="" title="Visor PDF Comunicado" class="w-full h-full border-0"></iframe>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Detectar automáticamente si el texto de la descripción del comunicado supera las 3 líneas
        const verificarTruncadoComunicados = () => {
            document.querySelectorAll('.tarjeta-comunicado').forEach((card) => {
                const parrafo = card.querySelector('.texto-resumen-comunicado');
                const wrapVerMas = card.querySelector('.contenedor-ver-mas-comunicado');
                if (!parrafo || !wrapVerMas) return;

                const truncado = parrafo.scrollHeight > (parrafo.clientHeight + 2);
                wrapVerMas.classList.toggle('hidden', !truncado);
            });
        };

        verificarTruncadoComunicados();
        window.addEventListener('load', verificarTruncadoComunicados);
        window.addEventListener('resize', verificarTruncadoComunicados);

        // 2. Modal de Detalle Completo del Comunicado
        const modalCom = document.getElementById('modal-detalle-comunicado');
        const cardCom = document.getElementById('modal-detalle-comunicado-card');
        const tituloEl = document.getElementById('modal-comunicado-titulo');
        const fechaEl = document.getElementById('modal-comunicado-fecha');
        const fotoWrap = document.getElementById('modal-comunicado-foto-wrap');
        const fotoEl = document.getElementById('modal-comunicado-foto');
        const descWrap = document.getElementById('modal-comunicado-desc-wrap');
        const descEl = document.getElementById('modal-comunicado-descripcion');
        const pdfActions = document.getElementById('modal-comunicado-acciones-pdf');

        const cerrarModalComunicado = () => {
            modalCom.classList.add('opacity-0');
            cardCom.classList.add('scale-95');
            setTimeout(() => {
                modalCom.classList.add('hidden');
                fotoEl.src = '';
            }, 200);
        };

        document.querySelectorAll('.btn-abrir-modal-comunicado').forEach((btn) => {
            btn.addEventListener('click', () => {
                const titulo = btn.getAttribute('data-titulo') || '';
                const fecha = btn.getAttribute('data-fecha') || '';
                const desc = btn.getAttribute('data-descripcion') || '';
                const foto = btn.getAttribute('data-foto') || '';
                const pdf = btn.getAttribute('data-pdf') || '';

                tituloEl.textContent = titulo;
                fechaEl.textContent = fecha;

                if (foto !== '') {
                    fotoEl.src = foto;
                    fotoWrap.classList.remove('hidden');
                } else {
                    fotoEl.src = '';
                    fotoWrap.classList.add('hidden');
                }

                if (desc !== '') {
                    descEl.innerHTML = desc;
                    descWrap.classList.remove('hidden');
                } else {
                    descEl.innerHTML = '';
                    descWrap.classList.add('hidden');
                }

                pdfActions.innerHTML = '';
                if (pdf !== '') {
                    pdfActions.innerHTML = `
                        <a href="${pdf}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-institucional-navy hover:bg-institucional-navy-dark text-white text-xs font-medium transition-colors shadow-sm">
                            <i class="fas fa-file-pdf text-institucional-gold"></i> <span>Abrir Documento PDF</span>
                        </a>
                    `;
                }

                modalCom.classList.remove('hidden');
                void modalCom.offsetWidth;
                modalCom.classList.remove('opacity-0');
                cardCom.classList.remove('scale-95');
            });
        });

        document.getElementById('btn-cerrar-modal-comunicado')?.addEventListener('click', cerrarModalComunicado);
        document.getElementById('btn-cerrar-modal-comunicado-inf')?.addEventListener('click', cerrarModalComunicado);
        modalCom?.addEventListener('click', (e) => {
            if (e.target === modalCom) cerrarModalComunicado();
        });

        // 3. Modal Visor PDF Integrado
        const modalPdf = document.getElementById('modal-visor-pdf');
        const cardPdf = document.getElementById('modal-visor-pdf-card');
        const iframePdf = document.getElementById('iframe-visor-pdf');
        const tituloPdf = document.getElementById('modal-pdf-titulo');
        const linkPestana = document.getElementById('modal-pdf-nueva-pestana');
        const btnCerrarPdf = document.getElementById('btn-cerrar-visor-pdf');

        document.querySelectorAll('.btn-previsualizar-pdf').forEach((btn) => {
            btn.addEventListener('click', () => {
                const url = btn.getAttribute('data-pdf') || '';
                const titulo = btn.getAttribute('data-titulo') || 'Comunicado PDF';

                tituloPdf.textContent = titulo;
                iframePdf.src = url;
                linkPestana.href = url;

                modalPdf.classList.remove('hidden');
                void modalPdf.offsetWidth;
                modalPdf.classList.remove('opacity-0');
                cardPdf.classList.remove('scale-95');
            });
        });

        const cerrarVisorPdf = () => {
            modalPdf.classList.add('opacity-0');
            cardPdf.classList.add('scale-95');
            setTimeout(() => {
                modalPdf.classList.add('hidden');
                iframePdf.src = '';
            }, 200);
        };

        btnCerrarPdf?.addEventListener('click', cerrarVisorPdf);
        modalPdf?.addEventListener('click', (e) => {
            if (e.target === modalPdf) cerrarVisorPdf();
        });
    });
</script>