<?php
/**
 * Vista: Extensión Universitaria - Feed Social de Actividades (src/Views/public/comunidad/eventos.php)
 * Variables inyectadas por HomeController::eventos():
 * @var array  $extensions          Publicaciones de la página actual (máx. 10)
 * @var array  $extensionCategories Categorías activas (`categoria_extension`)
 * @var int    $categoriaActiva     ID de categoría filtrada (0 = Todas)
 * @var string $slugActivo          Slug SEO de la categoría activa
 * @var int    $totalActividades    Total de actividades para la selección actual
 * @var int    $paginaActual        Página actual (1..N)
 * @var int    $totalPaginas        Total de páginas
 * @var int    $porPagina           10 por página
 */

$publicaciones   = $extensions ?? [];
$categorias      = $extensionCategories ?? [];
$catActiva       = (int) ($categoriaActiva ?? 0);
$slugCatActivo   = trim((string) ($slugActivo ?? ''));
$totalGlobal     = (int) ($totalActividades ?? count($publicaciones));
$pagActual       = max(1, (int) ($paginaActual ?? 1));
$totPaginas      = max(1, (int) ($totalPaginas ?? 1));
$limitePorPagina = (int) ($porPagina ?? 10);

/**
 * Helper global para sanitizar y formatear textos enriquecidos de Quill.js o texto plano
 */
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

/**
 * Helper para formatear la fecha del evento al estilo red social ("24 de octubre de 2026")
 */
if (!function_exists('formatearFechaEventoSocial')) {
    function formatearFechaEventoSocial(?string $fechaIso): string {
        $meses = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
        ];
        $ts = !empty($fechaIso) ? strtotime($fechaIso) : false;
        if (!$ts) {
            return 'Actividad Institucional';
        }
        $dia = (int) date('d', $ts);
        $mes = $meses[(int) date('n', $ts)] ?? '';
        $anio = date('Y', $ts);
        return "{$dia} de {$mes} de {$anio}";
    }
}

/**
 * Helper para traducir el `color_badge` de la tabla `categoria_extension` a clases Tailwind e icono
 */
if (!function_exists('obtenerEstiloCategoriaExtension')) {
    function obtenerEstiloCategoriaExtension(?string $color, ?string $nombreCat): array {
        $nomLower = mb_strtolower((string) $nombreCat, 'UTF-8');
        $icono = 'fas fa-bolt';
        if (str_contains($nomLower, 'icpc') || str_contains($nomLower, 'programaci')) {
            $icono = 'fas fa-code';
        } elseif (str_contains($nomLower, 'ctf') || str_contains($nomLower, 'seguridad')) {
            $icono = 'fas fa-shield-halved';
        } elseif (str_contains($nomLower, 'flisol') || str_contains($nomLower, 'libre')) {
            $icono = 'fab fa-linux';
        } elseif (str_contains($nomLower, 'congreso')) {
            $icono = 'fas fa-microphone-lines';
        } elseif (str_contains($nomLower, 'feria') || str_contains($nomLower, 'investigaci')) {
            $icono = 'fas fa-microchip';
        }

        $clasesBadge = match (strtolower((string) $color)) {
            'yellow' => 'bg-amber-50 text-amber-900 border-amber-300',
            'red'    => 'bg-rose-50 text-rose-800 border-rose-200',
            'green'  => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            'purple' => 'bg-purple-50 text-purple-800 border-purple-200',
            'teal'   => 'bg-teal-50 text-teal-800 border-teal-200',
            default  => 'bg-blue-50 text-institucional-navy border-blue-200',
        };

        return [
            'badge' => $clasesBadge,
            'icono' => $icono
        ];
    }
}

// Helper para armar URLs de paginación respetando la ruta SEO /extension/{slug_cat}
$armarUrlPagina = static function (int $numPag) use ($slugCatActivo): string {
    $rutaBase = $slugCatActivo !== ''
        ? '/extension/' . rawurlencode($slugCatActivo)
        : '/extension';

    return $numPag > 1 ? "{$rutaBase}?page={$numPag}" : $rutaBase;
};
?>

<!-- HERO SECTION: Aurora Navy Glow -->
<section class="aurora-hero-navy text-white py-14 sm:py-16 border-b-4 border-institucional-gold relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 pointer-events-none" 
         style="background-image: linear-gradient(to right, #ffffff 1px, transparent 1px), linear-gradient(to bottom, #ffffff 1px, transparent 1px); background-size: 36px 36px;">
    </div>

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2.5 px-3.5 py-1 rounded-full bg-white/10 border border-institucional-gold/40 backdrop-blur-md mb-5">
                    <span class="w-2 h-2 rounded-full bg-institucional-gold animate-pulse"></span>
                    <a href="/" class="text-xs font-medium text-slate-200 hover:text-white transition-colors">Inicio</a>
                    <span class="text-xs text-institucional-gold/70">/</span>
                    <span class="font-code text-xs font-medium text-institucional-gold uppercase tracking-wider">&lt;/&gt; vida universitaria y extensión</span>
                </div>

                <h1 class="font-display text-3xl sm:text-5xl font-bold tracking-tight leading-[1.13] text-white">
                    Extensión <span class="text-institucional-gold">Universitaria</span>
                </h1>
                
                <p class="mt-4 text-base text-slate-200 font-light leading-relaxed">
                    Explora los congresos, competencias de programación ICPC, competencias de seguridad informática CTF, ferias tecnológicas, actividades deportivas y culturales, y muchos más eventos que viven nuestros estudiantes y docentes.
                </p>
            </div>

            <!-- Indicador Estadístico Compacto -->
            <div class="relative self-start lg:self-center w-full sm:w-auto">
                <div class="bg-[#0f1f4d]/95 border border-institucional-gold/40 rounded-xl p-5 flex items-center gap-5 backdrop-blur-xl shadow-2xl">
                    <div class="w-13 h-13 p-3.5 rounded-xl bg-institucional-gold text-slate-900 flex items-center justify-center text-xl flex-shrink-0 shadow-[0_0_20px_rgba(234,179,8,0.4)]">
                        <i class="fas fa-camera-retro"></i>
                    </div>
                    <div class="pr-2">
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="font-display font-extrabold text-2xl sm:text-3xl text-white"><?= $totalGlobal ?></span>
                            <span class="text-s text-slate-200 font-medium">Actividades</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CONTENIDO PRINCIPAL: IMPORTANTE overflow-x-clip (no overflow-hidden) para que position: sticky funcione -->
<section class="relative py-12 sm:py-16 overflow-x-clip">
    <!-- Luces Aurora difuminadas -->
    <div class="absolute top-10 left-1/4 -translate-x-1/2 w-[36rem] h-[24rem] bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-40 right-8 w-[28rem] h-[22rem] bg-institucional-gold/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-20 left-1/3 w-[34rem] h-[24rem] bg-institucional-navy/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- =============================================================
                 COLUMNA IZQUIERDA FIJA AL HACER SCROLL (Sticky Sidebar)
                 ============================================================= -->
            <aside class="lg:col-span-4 space-y-5 lg:sticky lg:top-24 self-start z-20">
                
                <!-- 1. Panel de Categorías Desplegable (Abierto por defecto + SLUG SEO) -->
                <div class="code-window-panel overflow-hidden">
                    <button type="button"
                            id="btn-toggle-categorias-ext"
                            aria-expanded="true"
                            class="w-full bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-5 py-3.5 border-b border-institucional-gold/40 flex items-center justify-between text-left focus:outline-none group">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold"></span>
                            <span class="font-code text-xs text-institucional-gold font-medium uppercase tracking-wider">
                                &lt;/&gt; Categorías
                            </span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-200 group-hover:text-institucional-gold transition-colors">
                            <span id="txt-estado-cat-ext" class="font-code text-[11px] opacity-80">Ocultar</span>
                            <i id="icon-chevron-cat-ext" class="fas fa-chevron-up text-xs transition-transform duration-200"></i>
                        </div>
                    </button>

                    <!-- Lista desplegable (Visible por defecto) -->
                    <div id="lista-categorias-ext" class="p-3.5 space-y-1 transition-all duration-200">
                        <a href="/extension"
                           class="flex items-center justify-between px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all <?= $catActiva === 0 ? 'bg-institucional-navy text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100' ?>">
                            <span class="flex items-center gap-2.5">
                                <i class="fas fa-globe <?= $catActiva === 0 ? 'text-institucional-gold' : 'text-institucional-navy' ?>"></i>
                                <span>Todas las actividades</span>
                            </span>
                            <i class="fas fa-chevron-right text-[10px] opacity-60"></i>
                        </a>

                        <?php foreach ($categorias as $cat): ?>
                            <?php
                                $idCat    = (int) ($cat['id_cat_ext'] ?? 0);
                                $nomCat   = trim((string) ($cat['nom_cat'] ?? 'Categoría'));
                                $slugCat  = trim((string) ($cat['slug_cat'] ?? ''));
                                $esActiva = ($catActiva === $idCat);
                                $metaEstilo = obtenerEstiloCategoriaExtension($cat['color_badge'] ?? 'blue', $nomCat);
                                // Usamos el SLUG SEO limpio (/extension/ciberseguridad-ctf)
                                $hrefCat  = $slugCat !== '' ? '/extension/' . rawurlencode($slugCat) : '/extension?cat=' . $idCat;
                            ?>
                            <a href="<?= htmlspecialchars($hrefCat) ?>"
                               class="flex items-center justify-between px-4 py-2.5 rounded-xl text-xs sm:text-sm font-medium transition-all <?= $esActiva ? 'bg-institucional-navy text-white font-semibold shadow-sm' : 'text-slate-700 hover:bg-slate-100' ?>">
                                <span class="flex items-center gap-2.5 truncate pr-2">
                                    <i class="<?= $metaEstilo['icono'] ?> w-4 text-center <?= $esActiva ? 'text-institucional-gold' : 'text-institucional-navy' ?>"></i>
                                    <span class="truncate"><?= htmlspecialchars($nomCat) ?></span>
                                </span>
                                <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 2. Tarjeta Siempre Visible: "¿Te apasiona la tecnología?" -->
                <div class="tech-card-spotlight p-5 sm:p-6 relative overflow-hidden">
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-institucional-gold via-yellow-400 to-institucional-navy"></div>
                    
                    <div class="flex items-center gap-3 mb-3.5">
                        <div class="w-11 h-11 rounded-xl bg-institucional-navy text-institucional-gold flex items-center justify-center text-lg shadow-md flex-shrink-0">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <div>
                            <span class="font-code text-[10px] uppercase tracking-wider text-institucional-navy font-bold block">
                                Tu futuro empieza aquí
                            </span>
                            <h3 class="font-display text-lg font-bold text-slate-900 leading-tight">
                                ¿Te apasiona la tecnología?
                            </h3>
                        </div>
                    </div>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                        Sé parte de nuestra comunidad estudiantil, compite a nivel nacional en programación, ciberseguridad y robótica en una <strong>Carrera Acreditada al CEUB</strong>.
                    </p>

                    <div class="flex flex-col gap-2">
                        <a href="/admision"
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full bg-institucional-navy hover:bg-institucional-navy-dark text-white text-xs font-semibold transition-all shadow-md">
                            <i class="fas fa-user-plus text-institucional-gold"></i>
                            <span>Postular en Admisión</span>
                        </a>
                        <a href="/academico/plan-estudios"
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full bg-white hover:bg-slate-100 text-institucional-navy border border-blue-200 text-xs font-semibold transition-all">
                            <i class="fas fa-sitemap"></i>
                            <span>Conocer el Plan de Estudios</span>
                        </a>
                    </div>
                </div>

            </aside>

            <!-- =============================================================
                 COLUMNA CENTRAL/DERECHA (8 cols en Desktop): Feed Social de Posts
                 ============================================================= -->
            <div class="lg:col-span-8 space-y-8">

                <?php if (!empty($publicaciones)): ?>
                    <?php foreach ($publicaciones as $post): ?>
                        <?php
                            $idExt         = (int) ($post['id_ext'] ?? 0);
                            $titulo        = trim((string) ($post['nom_ext'] ?? 'Actividad de Extensión'));
                            $fechaSocial   = formatearFechaEventoSocial($post['fech_ext'] ?? null);
                            $lugar         = !empty($post['lugar_ext']) ? trim((string) $post['lugar_ext']) : null;
                            $descRaw       = trim((string) ($post['desc_ext'] ?? ''));
                            $descHtml      = formatearTextoQuill($descRaw);
                            $enlaceExterno = !empty($post['enlace_externo']) ? trim((string) $post['enlace_externo']) : null;
                            $esDestacado   = ((int) ($post['destacado'] ?? 0) === 1);
                            $nomCategoria  = !empty($post['nom_cat']) ? trim((string) $post['nom_cat']) : 'Extensión Universitaria';
                            $slugPostCat   = !empty($post['slug_cat']) ? trim((string) $post['slug_cat']) : '';
                            $estiloCat     = obtenerEstiloCategoriaExtension($post['color_badge'] ?? 'blue', $nomCategoria);

                            // Procesar arreglo de fotos del post (/uploads/img/extension/)
                            $fotosRaw      = is_array($post['fotos'] ?? null) ? $post['fotos'] : [];
                            $urlsFotos     = [];
                            foreach ($fotosRaw as $f) {
                                $nomFoto = trim((string) ($f['nom_foto'] ?? ''));
                                if ($nomFoto !== '') {
                                    $urlsFotos[] = '/uploads/img/extension/' . rawurlencode($nomFoto);
                                }
                            }
                            $totalFotos    = count($urlsFotos);
                            $jsonFotos     = htmlspecialchars((string) json_encode($urlsFotos, JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8');

                            $atributosGaleria = sprintf(
                                'data-titulo="%s" data-fecha="%s" data-categoria="%s" data-lugar="%s" data-fotos="%s"',
                                htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars($fechaSocial, ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars($nomCategoria, ENT_QUOTES, 'UTF-8'),
                                htmlspecialchars($lugar ?? 'Ingeniería Informática · UNSXX', ENT_QUOTES, 'UTF-8'),
                                $jsonFotos
                            );
                        ?>

                        <!-- TARJETA DE PUBLICACIÓN ESTILO RED SOCIAL -->
                        <article id="actividad-<?= $idExt ?>" class="post-extension <?= $esDestacado ? 'tech-card-spotlight' : 'tech-card' ?> overflow-hidden relative">
                            <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r <?= $esDestacado ? 'from-institucional-gold via-yellow-400 to-institucional-navy' : 'from-institucional-navy via-blue-600 to-institucional-gold' ?>"></div>

                            <!-- 1. CABECERA DEL POST -->
                            <div class="p-5 sm:p-6 pb-4 flex flex-wrap items-start justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-12 h-12 rounded-2xl bg-white border-2 border-institucional-gold p-1.5 shadow-sm flex items-center justify-center flex-shrink-0">
                                        <img src="/assets/img/logo-informatica.png" alt="Ingeniería Informática UNSXX" class="w-full h-full object-contain">
                                    </div>

                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-display font-bold text-sm sm:text-base text-slate-900">
                                                Ingeniería Informática · UNSXX
                                            </span>
                                            <i class="fas fa-circle-check text-blue-600 text-xs" title="Cuenta Institucional Oficial"></i>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500 mt-0.5">
                                            <span class="inline-flex items-center gap-1">
                                                <i class="far fa-calendar text-institucional-navy"></i>
                                                <?= htmlspecialchars($fechaSocial) ?>
                                            </span>

                                            <?php if ($lugar !== null): ?>
                                                <span class="inline-flex items-center gap-1 text-slate-600 font-medium">
                                                    <i class="fas fa-location-dot text-institucional-gold"></i>
                                                    <?= htmlspecialchars($lugar) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Insignias de Categoría (Con link SEO) y Post Destacado -->
                                <div class="flex flex-wrap items-center gap-2">
                                    <?php if ($esDestacado): ?>
                                        <span class="font-code inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-900 border border-amber-300 shadow-sm">
                                            <i class="fas fa-thumbtack text-institucional-gold rotate-45 text-[10px]"></i>
                                            Destacado
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($slugPostCat !== ''): ?>
                                        <a href="/extension/<?= htmlspecialchars(rawurlencode($slugPostCat)) ?>"
                                           class="font-code inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold border hover:opacity-85 transition-opacity <?= $estiloCat['badge'] ?>">
                                            <i class="<?= $estiloCat['icono'] ?> text-[10px]"></i>
                                            <?= htmlspecialchars($nomCategoria) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="font-code inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold border <?= $estiloCat['badge'] ?>">
                                            <i class="<?= $estiloCat['icono'] ?> text-[10px]"></i>
                                            <?= htmlspecialchars($nomCategoria) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- 2. TÍTULO Y DESCRIPCIÓN DE LA PUBLICACIÓN -->
                            <div class="px-5 sm:px-6 pb-4">
                                <h2 class="font-display text-xl sm:text-2xl font-bold text-slate-900 leading-snug mb-2.5">
                                    <?= htmlspecialchars($titulo) ?>
                                </h2>

                                <?php if ($descHtml !== ''): ?>
                                    <div class="texto-post-extension quill-content text-sm sm:text-base text-slate-700 leading-relaxed line-clamp-3">
                                        <?= $descHtml ?>
                                    </div>
                                    <button type="button"
                                            class="btn-toggle-texto-post hidden mt-1.5 inline-flex items-center gap-1 text-xs font-bold text-institucional-navy hover:text-yellow-600 transition-colors focus:outline-none">
                                        <span class="lbl-toggle">Ver más</span>
                                        <i class="fas fa-angle-down text-xs icon-toggle"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($enlaceExterno !== null): ?>
                                    <div class="mt-3 pt-2">
                                        <a href="<?= htmlspecialchars($enlaceExterno) ?>"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 hover:bg-institucional-navy text-institucional-navy hover:text-white border border-blue-200 text-xs font-semibold transition-all">
                                            <i class="fas fa-link text-[11px]"></i>
                                            <span>Enlace oficial del evento / Más información</span>
                                            <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- 3. COLLAGE / MOSAICO FOTOGRÁFICO ESTILO FACEBOOK -->
                            <?php if ($totalFotos > 0): ?>
                                <div class="px-2 sm:px-3 pb-2">
                                    <?php if ($totalFotos === 1): ?>
                                        <div class="rounded-xl overflow-hidden bg-slate-950 border border-slate-200 max-h-[460px]">
                                            <img src="<?= htmlspecialchars($urlsFotos[0]) ?>"
                                                 alt="<?= htmlspecialchars($titulo) ?>"
                                                 loading="lazy"
                                                 class="w-full h-full max-h-[460px] object-cover hover:scale-[1.02] transition-transform duration-300 cursor-pointer btn-abrir-album-extension"
                                                 data-index="0"
                                                 <?= $atributosGaleria ?>>
                                        </div>

                                    <?php elseif ($totalFotos === 2): ?>
                                        <div class="grid grid-cols-2 gap-1.5 rounded-xl overflow-hidden">
                                            <?php for ($i = 0; $i < 2; $i++): ?>
                                                <div class="aspect-[4/3] bg-slate-950 overflow-hidden">
                                                    <img src="<?= htmlspecialchars($urlsFotos[$i]) ?>"
                                                         alt="<?= htmlspecialchars($titulo) ?> - Foto <?= $i + 1 ?>"
                                                         loading="lazy"
                                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300 cursor-pointer btn-abrir-album-extension"
                                                         data-index="<?= $i ?>"
                                                         <?= $atributosGaleria ?>>
                                                </div>
                                            <?php endfor; ?>
                                        </div>

                                    <?php elseif ($totalFotos === 3): ?>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1.5 rounded-xl overflow-hidden sm:h-[380px]">
                                            <div class="sm:col-span-2 h-64 sm:h-full bg-slate-950 overflow-hidden">
                                                <img src="<?= htmlspecialchars($urlsFotos[0]) ?>"
                                                     alt="<?= htmlspecialchars($titulo) ?> - Portada"
                                                     loading="lazy"
                                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300 cursor-pointer btn-abrir-album-extension"
                                                     data-index="0"
                                                     <?= $atributosGaleria ?>>
                                            </div>
                                            <div class="grid grid-cols-2 sm:grid-cols-1 sm:grid-rows-2 gap-1.5 h-36 sm:h-full">
                                                <?php for ($i = 1; $i <= 2; $i++): ?>
                                                    <div class="bg-slate-950 overflow-hidden h-full">
                                                        <img src="<?= htmlspecialchars($urlsFotos[$i]) ?>"
                                                             alt="<?= htmlspecialchars($titulo) ?> - Foto <?= $i + 1 ?>"
                                                             loading="lazy"
                                                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300 cursor-pointer btn-abrir-album-extension"
                                                             data-index="<?= $i ?>"
                                                             <?= $atributosGaleria ?>>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>

                                    <?php else: ?>
                                        <div class="space-y-1.5 rounded-xl overflow-hidden">
                                            <div class="h-64 sm:h-80 bg-slate-950 overflow-hidden">
                                                <img src="<?= htmlspecialchars($urlsFotos[0]) ?>"
                                                     alt="<?= htmlspecialchars($titulo) ?> - Portada"
                                                     loading="lazy"
                                                     class="w-full h-full object-cover hover:scale-[1.02] transition-transform duration-300 cursor-pointer btn-abrir-album-extension"
                                                     data-index="0"
                                                     <?= $atributosGaleria ?>>
                                            </div>

                                            <div class="grid grid-cols-3 gap-1.5 h-28 sm:h-36">
                                                <?php for ($i = 1; $i <= 3; $i++): ?>
                                                    <?php $restantes = $totalFotos - 4; ?>
                                                    <div class="relative bg-slate-950 overflow-hidden h-full group/thumb">
                                                        <img src="<?= htmlspecialchars($urlsFotos[$i]) ?>"
                                                             alt="<?= htmlspecialchars($titulo) ?> - Foto <?= $i + 1 ?>"
                                                             loading="lazy"
                                                             class="w-full h-full object-cover group-hover/thumb:scale-105 transition-transform duration-300 cursor-pointer btn-abrir-album-extension"
                                                             data-index="<?= $i ?>"
                                                             <?= $atributosGaleria ?>>

                                                        <?php if ($i === 3 && $restantes > 0): ?>
                                                            <button type="button"
                                                                    class="btn-abrir-album-extension absolute inset-0 bg-slate-950/75 hover:bg-institucional-navy/85 backdrop-blur-[2px] flex flex-col items-center justify-center text-white transition-colors focus:outline-none"
                                                                    data-index="3"
                                                                    <?= $atributosGaleria ?>>
                                                                <span class="font-display text-2xl sm:text-3xl font-extrabold text-institucional-gold">
                                                                    +<?= $restantes ?>
                                                                </span>
                                                                <span class="font-code text-[11px] uppercase tracking-wider text-white">
                                                                    Ver todas
                                                                </span>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- 4. PIE INTERACTIVO DEL POST -->
                            <div class="px-5 sm:px-6 py-3.5 bg-slate-50/90 border-t border-slate-200/80 flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                    <span class="w-7 h-7 rounded-full bg-institucional-navy/10 text-institucional-navy flex items-center justify-center">
                                        <i class="fas fa-images text-xs"></i>
                                    </span>
                                    <span class="font-medium text-slate-700">
                                        <?= $totalFotos ?> <?= $totalFotos === 1 ? 'fotografía' : 'fotografías en este álbum' ?>
                                    </span>
                                </div>

                                <div class="flex items-center gap-2 ml-auto">
                                    <?php if ($totalFotos > 0): ?>
                                        <button type="button"
                                                class="btn-abrir-album-extension inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-institucional-navy hover:bg-institucional-navy-dark text-white text-xs font-medium transition-all shadow-sm"
                                                data-index="0"
                                                <?= $atributosGaleria ?>>
                                            <i class="fas fa-expand text-institucional-gold text-xs"></i>
                                            <span>Ver álbum fotográfico</span>
                                        </button>
                                    <?php endif; ?>

                                    <button type="button"
                                            class="btn-compartir-post inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 text-xs font-semibold transition-all shadow-sm"
                                            data-titulo="<?= htmlspecialchars($titulo) ?>"
                                            data-id="actividad-<?= $idExt ?>"
                                            title="Compartir enlace de esta actividad">
                                        <i class="fas fa-share-nodes text-institucional-navy text-xs"></i>
                                        <span>Compartir</span>
                                    </button>
                                </div>
                            </div>

                        </article>
                    <?php endforeach; ?>

                    <!-- =========================================================
                         PAGINACIÓN INSTITUCIONAL (10 ACTIVIDADES POR PÁGINA)
                         ========================================================= -->
                    <?php if ($totPaginas > 1): ?>
                        <nav aria-label="Paginación de actividades de extensión" class="code-window-panel p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <span class="font-code text-xs text-slate-600">
                                Página <strong class="text-institucional-navy"><?= $pagActual ?></strong> de <strong class="text-institucional-navy"><?= $totPaginas ?></strong> (<?= $limitePorPagina ?> actividades por página)
                            </span>

                            <div class="flex flex-wrap items-center gap-1.5">
                                <?php if ($pagActual > 1): ?>
                                    <a href="<?= htmlspecialchars($armarUrlPagina($pagActual - 1)) ?>"
                                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white hover:bg-institucional-navy text-slate-700 hover:text-white border border-slate-200 text-xs font-semibold transition-all shadow-sm">
                                        <i class="fas fa-chevron-left text-[10px]"></i>
                                        <span>Anterior</span>
                                    </a>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-slate-100 text-slate-400 border border-slate-200 text-xs font-medium cursor-not-allowed">
                                        <i class="fas fa-chevron-left text-[10px]"></i>
                                        <span>Anterior</span>
                                    </span>
                                <?php endif; ?>

                                <?php
                                    $ini = max(1, $pagActual - 2);
                                    $fin = min($totPaginas, $pagActual + 2);
                                ?>
                                <?php for ($p = $ini; $p <= $fin; $p++): ?>
                                    <a href="<?= htmlspecialchars($armarUrlPagina($p)) ?>"
                                       class="w-8 h-8 rounded-full flex items-center justify-center font-code text-xs font-bold transition-all <?= $p === $pagActual ? 'bg-institucional-navy text-institucional-gold shadow-md border border-institucional-gold/50' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200' ?>">
                                        <?= $p ?>
                                    </a>
                                <?php endfor; ?>

                                <?php if ($pagActual < $totPaginas): ?>
                                    <a href="<?= htmlspecialchars($armarUrlPagina($pagActual + 1)) ?>"
                                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white hover:bg-institucional-navy text-slate-700 hover:text-white border border-slate-200 text-xs font-semibold transition-all shadow-sm">
                                        <span>Siguiente</span>
                                        <i class="fas fa-chevron-right text-[10px]"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-slate-100 text-slate-400 border border-slate-200 text-xs font-medium cursor-not-allowed">
                                        <span>Siguiente</span>
                                        <i class="fas fa-chevron-right text-[10px]"></i>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </nav>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="code-window-panel p-12 text-center space-y-3">
                        <div class="w-14 h-14 rounded-2xl bg-institucional-navy/10 text-institucional-navy mx-auto flex items-center justify-center text-2xl">
                            <i class="fas fa-camera-retro"></i>
                        </div>
                        <h3 class="font-display text-xl font-bold text-slate-900">No hay actividades registradas en esta categoría</h3>
                        <p class="text-sm text-slate-500">Selecciona otra categoría o explora todas las actividades de extensión.</p>
                        <?php if ($catActiva > 0): ?>
                            <div class="pt-2">
                                <a href="/extension" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-institucional-navy text-white text-xs font-semibold">
                                    Ver todas las actividades
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>

        </div>

    </div>
</section>

<!-- MODAL LIGHTBOX SOCIAL DE ÁLBUM FOTOGRÁFICO -->
<div id="modal-album-extension" class="fixed inset-0 z-50 hidden bg-slate-950/95 backdrop-blur-md flex items-center justify-center p-2 sm:p-4 opacity-0 transition-opacity duration-200">
    <div id="modal-album-extension-card" class="bg-slate-900 rounded-2xl border border-white/15 shadow-2xl max-w-5xl w-full overflow-hidden flex flex-col max-h-[94vh] transform scale-95 transition-transform duration-200">
        
        <div class="px-5 py-3.5 bg-institucional-navy border-b border-institucional-gold/40 flex items-center justify-between gap-4 flex-shrink-0">
            <div class="min-w-0">
                <div class="flex items-center gap-2.5">
                    <span id="modal-album-contador" class="font-code text-xs px-2.5 py-0.5 rounded-full bg-institucional-gold text-slate-900 font-bold">1 / 1</span>
                    <span id="modal-album-categoria" class="font-code text-xs text-institucional-gold truncate"></span>
                </div>
                <h3 id="modal-album-titulo" class="font-display text-sm sm:text-lg font-bold text-white truncate mt-0.5"></h3>
            </div>

            <button type="button" id="btn-cerrar-album-extension" class="w-9 h-9 rounded-full bg-white/10 hover:bg-institucional-gold text-white hover:text-slate-900 flex items-center justify-center transition-colors flex-shrink-0">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>

        <div class="relative bg-slate-950 flex-grow flex items-center justify-center h-[56vh] sm:h-[65vh] select-none">
            <button type="button" id="btn-album-prev" class="absolute left-3 sm:left-5 z-10 w-11 h-11 rounded-full bg-slate-900/80 hover:bg-institucional-gold text-white hover:text-slate-900 border border-white/15 flex items-center justify-center transition-all shadow-lg">
                <i class="fas fa-chevron-left"></i>
            </button>

            <img id="modal-album-img" src="" alt="Fotografía de Actividad" class="max-h-full max-w-full object-contain p-2">

            <button type="button" id="btn-album-next" class="absolute right-3 sm:right-5 z-10 w-11 h-11 rounded-full bg-slate-900/80 hover:bg-institucional-gold text-white hover:text-slate-900 border border-white/15 flex items-center justify-center transition-all shadow-lg">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="px-5 py-3 bg-slate-900 border-t border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-3 flex-shrink-0">
            <div class="text-xs text-slate-300 flex items-center gap-3">
                <span id="modal-album-fecha" class="inline-flex items-center gap-1.5"></span>
                <span class="text-slate-600">·</span>
                <span id="modal-album-lugar" class="inline-flex items-center gap-1.5 text-institucional-gold"></span>
            </div>

            <div id="modal-album-thumbs" class="flex items-center gap-2 overflow-x-auto pb-1 max-w-full sm:max-w-md"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Acordeón desplegable de Categorías (Desplegado por defecto)
        const btnToggleCat = document.getElementById('btn-toggle-categorias-ext');
        const listaCat = document.getElementById('lista-categorias-ext');
        const txtEstadoCat = document.getElementById('txt-estado-cat-ext');
        const iconChevronCat = document.getElementById('icon-chevron-cat-ext');

        if (btnToggleCat && listaCat) {
            let desplegado = true;
            btnToggleCat.addEventListener('click', () => {
                desplegado = !desplegado;
                btnToggleCat.setAttribute('aria-expanded', desplegado ? 'true' : 'false');
                listaCat.classList.toggle('hidden', !desplegado);
                if (txtEstadoCat) txtEstadoCat.textContent = desplegado ? 'Ocultar' : 'Mostrar';
                if (iconChevronCat) {
                    iconChevronCat.className = desplegado
                        ? 'fas fa-chevron-up text-xs transition-transform duration-200'
                        : 'fas fa-chevron-down text-xs transition-transform duration-200';
                }
            });
        }

        // 2. Detectar textos largos en los posts y habilitar "Ver más / Ver menos"
        const inicializarTextosPosts = () => {
            document.querySelectorAll('.post-extension').forEach((post) => {
                const textoEl = post.querySelector('.texto-post-extension');
                const btnToggle = post.querySelector('.btn-toggle-texto-post');
                if (!textoEl || !btnToggle) return;

                const estaCortado = textoEl.scrollHeight > (textoEl.clientHeight + 2);
                if (estaCortado || !textoEl.classList.contains('line-clamp-3')) {
                    btnToggle.classList.remove('hidden');
                } else {
                    btnToggle.classList.add('hidden');
                }
            });
        };

        inicializarTextosPosts();
        window.addEventListener('load', inicializarTextosPosts);
        window.addEventListener('resize', inicializarTextosPosts);

        document.querySelectorAll('.btn-toggle-texto-post').forEach((btn) => {
            btn.addEventListener('click', () => {
                const post = btn.closest('.post-extension');
                const textoEl = post?.querySelector('.texto-post-extension');
                const lbl = btn.querySelector('.lbl-toggle');
                const icon = btn.querySelector('.icon-toggle');
                if (!textoEl) return;

                const expandido = !textoEl.classList.contains('line-clamp-3');
                textoEl.classList.toggle('line-clamp-3', expandido);

                if (lbl) lbl.textContent = expandido ? 'Ver más' : 'Ver menos';
                if (icon) icon.className = expandido ? 'fas fa-angle-down text-xs icon-toggle' : 'fas fa-angle-up text-xs icon-toggle';
            });
        });

        // 3. Modal Lightbox del Álbum Fotográfico
        const modalAlbum = document.getElementById('modal-album-extension');
        const cardAlbum = document.getElementById('modal-album-extension-card');
        const imgAlbum = document.getElementById('modal-album-img');
        const tituloAlbum = document.getElementById('modal-album-titulo');
        const catAlbum = document.getElementById('modal-album-categoria');
        const fechaAlbum = document.getElementById('modal-album-fecha');
        const lugarAlbum = document.getElementById('modal-album-lugar');
        const contadorAlbum = document.getElementById('modal-album-contador');
        const thumbsContainer = document.getElementById('modal-album-thumbs');
        const btnPrev = document.getElementById('btn-album-prev');
        const btnNext = document.getElementById('btn-album-next');
        const btnCerrar = document.getElementById('btn-cerrar-album-extension');

        let fotosAlbum = [];
        let indiceFoto = 0;

        const actualizarVistaAlbum = () => {
            if (fotosAlbum.length === 0) return;
            imgAlbum.src = fotosAlbum[indiceFoto];
            contadorAlbum.textContent = `${indiceFoto + 1} / ${fotosAlbum.length}`;
            btnPrev.classList.toggle('hidden', fotosAlbum.length <= 1);
            btnNext.classList.toggle('hidden', fotosAlbum.length <= 1);

            thumbsContainer.querySelectorAll('button').forEach((b, idx) => {
                if (idx === indiceFoto) {
                    b.className = 'w-12 h-9 rounded-lg overflow-hidden border-2 border-institucional-gold flex-shrink-0 transition-all';
                } else {
                    b.className = 'w-12 h-9 rounded-lg overflow-hidden border border-white/20 opacity-50 hover:opacity-100 flex-shrink-0 transition-all';
                }
            });
        };

        document.querySelectorAll('.btn-abrir-album-extension').forEach((btn) => {
            btn.addEventListener('click', () => {
                try {
                    fotosAlbum = JSON.parse(btn.getAttribute('data-fotos') || '[]');
                } catch {
                    fotosAlbum = [];
                }
                if (fotosAlbum.length === 0) return;

                indiceFoto = parseInt(btn.getAttribute('data-index') || '0', 10) || 0;
                tituloAlbum.textContent = btn.getAttribute('data-titulo') || '';
                catAlbum.textContent = '</> ' + (btn.getAttribute('data-categoria') || 'Extensión');
                fechaAlbum.innerHTML = `<i class="far fa-calendar"></i> ${btn.getAttribute('data-fecha') || ''}`;
                lugarAlbum.innerHTML = `<i class="fas fa-location-dot"></i> ${btn.getAttribute('data-lugar') || ''}`;

                thumbsContainer.innerHTML = '';
                fotosAlbum.forEach((src, idx) => {
                    const thumbBtn = document.createElement('button');
                    thumbBtn.type = 'button';
                    thumbBtn.innerHTML = `<img src="${src}" alt="Miniatura ${idx + 1}" class="w-full h-full object-cover">`;
                    thumbBtn.addEventListener('click', () => {
                        indiceFoto = idx;
                        actualizarVistaAlbum();
                    });
                    thumbsContainer.appendChild(thumbBtn);
                });

                actualizarVistaAlbum();

                modalAlbum.classList.remove('hidden');
                void modalAlbum.offsetWidth;
                modalAlbum.classList.remove('opacity-0');
                cardAlbum.classList.remove('scale-95');
            });
        });

        btnPrev?.addEventListener('click', () => {
            if (fotosAlbum.length <= 1) return;
            indiceFoto = (indiceFoto - 1 + fotosAlbum.length) % fotosAlbum.length;
            actualizarVistaAlbum();
        });

        btnNext?.addEventListener('click', () => {
            if (fotosAlbum.length <= 1) return;
            indiceFoto = (indiceFoto + 1) % fotosAlbum.length;
            actualizarVistaAlbum();
        });

        const cerrarAlbum = () => {
            modalAlbum.classList.add('opacity-0');
            cardAlbum.classList.add('scale-95');
            setTimeout(() => {
                modalAlbum.classList.add('hidden');
                imgAlbum.src = '';
            }, 200);
        };

        btnCerrar?.addEventListener('click', cerrarAlbum);
        modalAlbum?.addEventListener('click', (e) => {
            if (e.target === modalAlbum) cerrarAlbum();
        });

        document.addEventListener('keydown', (e) => {
            if (modalAlbum?.classList.contains('hidden')) return;
            if (e.key === 'ArrowLeft') btnPrev?.click();
            if (e.key === 'ArrowRight') btnNext?.click();
            if (e.key === 'Escape') cerrarAlbum();
        });

        // 4. Botón Compartir Actividad
        document.querySelectorAll('.btn-compartir-post').forEach((btn) => {
            btn.addEventListener('click', async () => {
                const titulo = btn.getAttribute('data-titulo') || 'Extensión Universitaria - Informática UNSXX';
                const idAncla = btn.getAttribute('data-id') || '';
                const urlCompartir = `${window.location.origin}${window.location.pathname}#${idAncla}`;

                if (navigator.share) {
                    try {
                        await navigator.share({ title: titulo, url: urlCompartir });
                        return;
                    } catch {}
                }

                try {
                    await navigator.clipboard.writeText(urlCompartir);
                    const span = btn.querySelector('span');
                    if (span) {
                        const original = span.textContent;
                        span.textContent = '¡Enlace copiado!';
                        setTimeout(() => { span.textContent = original; }, 2000);
                    }
                } catch {}
            });
        });
    });
</script>