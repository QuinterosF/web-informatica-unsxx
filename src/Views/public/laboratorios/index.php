<?php
/**
 * Vista: Laboratorios de Computación (src/Views/public/laboratorios/index.php)
 * Variables inyectadas por HomeController::laboratorios():
 * @var array $labs Lista de laboratorios activos desde PublicPortal::getLabsWithGallery()
 */

$laboratorios = $labs ?? [];
$totalLabs = count($laboratorios);

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

/**
 * Verifica que el nombre de archivo PDF exista físicamente en public/uploads/pdf/laboratorios/
 */
if (!function_exists('obtenerUrlHorarioLabValido')) {
    function obtenerUrlHorarioLabValido(?string $archivoPdf): ?string {
        $archivo = trim((string) $archivoPdf);
        if ($archivo === '') {
            return null;
        }
        $rutaPublica = dirname(__DIR__, 4) . '/public/uploads/pdf/laboratorios/' . $archivo;
        $rutaDocRoot = !empty($_SERVER['DOCUMENT_ROOT'])
            ? rtrim((string) $_SERVER['DOCUMENT_ROOT'], '/\\') . '/uploads/pdf/laboratorios/' . $archivo
            : '';

        if (is_file($rutaPublica) || ($rutaDocRoot !== '' && is_file($rutaDocRoot))) {
            return '/uploads/pdf/laboratorios/' . rawurlencode($archivo);
        }
        return null;
    }
}

if (!function_exists('obtenerMetaVisualLaboratorio')) {
    function obtenerMetaVisualLaboratorio(string $nombreLab): array {
        $nomLower = mb_strtolower($nombreLab, 'UTF-8');
        if (str_contains($nomLower, 'seguridad')) {
            return [
                'icono' => 'fas fa-shield-halved',
                'tag'   => 'Ciberseguridad, Redes y Forense Digital'
            ];
        }
        if (str_contains($nomLower, 'móvil') || str_contains($nomLower, 'movil')) {
            return [
                'icono' => 'fas fa-mobile-screen-button',
                'tag'   => 'Desarrollo Móvil, IoT y Realidad Virtual'
            ];
        }
        if (str_contains($nomLower, 'hardware') || str_contains($nomLower, 'robótica')) {
            return [
                'icono' => 'fas fa-microchip',
                'tag'   => 'Arquitectura, Electrónica y Telecomunicaciones'
            ];
        }
        return [
            'icono' => 'fas fa-laptop-code',
            'tag'   => 'Ingeniería de Software, Base de Datos y Programación'
        ];
    }
}
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
                    <span class="font-code text-xs font-medium text-institucional-gold uppercase tracking-wider">&lt;/&gt; infraestructura tecnológica</span>
                </div>

                <h1 class="font-display text-3xl sm:text-5xl font-bold tracking-tight leading-[1.13] text-white">
                    Laboratorios de <span class="text-institucional-gold">Computación</span>
                </h1>
                
                <p class="mt-4 text-base text-slate-200 font-light leading-relaxed">
                    Ambientes especializados en desarrollo de software, seguridad informática, computación móvil y hardware para la formación práctica e investigativa.
                </p>
            </div>

            <!-- Indicador Estadístico Compacto -->
            <div class="relative self-start lg:self-center w-full sm:w-auto">
                <div class="bg-[#0f1f4d]/95 border border-institucional-gold/40 rounded-xl p-5 flex items-center gap-5 backdrop-blur-xl shadow-2xl">
                    <div class="w-13 h-13 p-3.5 rounded-xl bg-institucional-gold text-slate-900 flex items-center justify-center text-xl flex-shrink-0 shadow-[0_0_20px_rgba(234,179,8,0.4)]">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="pr-2">
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="font-display font-extrabold text-2xl sm:text-3xl text-white"><?= $totalLabs ?></span>
                            <span class="text-s text-slate-200 font-medium">Laboratorios</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CONTENIDO PRINCIPAL -->
<section class="relative py-12 sm:py-16 overflow-hidden">
    <!-- Luces Aurora difuminadas -->
    <div class="absolute top-10 left-1/4 -translate-x-1/2 w-[36rem] h-[24rem] bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-40 right-8 w-[28rem] h-[22rem] bg-institucional-gold/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-20 left-1/3 w-[34rem] h-[24rem] bg-institucional-navy/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-10">

        <?php if (!empty($laboratorios)): ?>

            <!-- Navegación rápida por laboratorios -->
            <div class="code-window-panel p-4 sm:px-6 flex flex-wrap items-center justify-between gap-3">
                <span class="font-code text-xs uppercase tracking-wider text-slate-500 font-semibold flex items-center gap-2">
                    <i class="fas fa-compass text-institucional-navy"></i> Ir al laboratorio:
                </span>
                <div class="flex flex-wrap items-center gap-2">
                    <?php foreach ($laboratorios as $labNav): ?>
                        <a href="#lab-<?= (int) $labNav['id_lab'] ?>"
                           class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white hover:bg-institucional-navy text-institucional-navy hover:text-white border border-slate-200/90 transition-all shadow-sm">
                            Lab. <?= htmlspecialchars((string) $labNav['nombre_lab']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- LISTADO DE LABORATORIOS -->
            <div class="space-y-12">
                <?php foreach ($laboratorios as $index => $lab): ?>
                    <?php
                        $idLab        = (int) ($lab['id_lab'] ?? 0);
                        $nombreLab    = trim((string) ($lab['nombre_lab'] ?? 'Laboratorio'));
                        $metaLab      = obtenerMetaVisualLaboratorio($nombreLab);

                        // Verificación real en disco del PDF de horario para evitar errores 404
                        $urlHorario   = obtenerUrlHorarioLabValido($lab['horario_lab'] ?? null);

                        // Galería de fotos del laboratorio
                        $fotosRaw     = is_array($lab['fotos'] ?? null) ? $lab['fotos'] : [];
                        $urlsGaleria  = [];
                        foreach ($fotosRaw as $f) {
                            $arch = trim((string) ($f['archivo_url'] ?? ''));
                            if ($arch !== '') {
                                $urlsGaleria[] = '/uploads/img/laboratorios/' . rawurlencode($arch);
                            }
                        }
                        $fotoPortada  = $urlsGaleria[0] ?? null;
                        $jsonGaleria  = htmlspecialchars((string) json_encode($urlsGaleria, JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8');

                        // Datos del Docente Responsable
                        $tieneResp     = !empty($lab['responsable_nombre']);
                        $respNombre    = trim((string) ($lab['responsable_nombre'] ?? 'Por designar'));
                        $respCat       = trim((string) ($lab['responsable_categoria'] ?? 'Responsable de Laboratorio'));
                        $respDescRaw   = trim((string) ($lab['responsable_desc'] ?? ''));
                        $textoRespDesc = formatearTextoQuill($respDescRaw !== '' ? $respDescRaw : "Responsable académico del Laboratorio de {$nombreLab} de la Carrera de Ingeniería Informática - UNSXX.");
                        $respFotoUrl   = obtenerUrlFotoDocente($lab['responsable_foto'] ?? null);
                        $respInic      = obtenerInicialesDocente($respNombre);
                        $respEmail     = !empty($lab['responsable_email']) ? trim((string) $lab['responsable_email']) : null;
                        $respCel       = !empty($lab['responsable_cel']) ? trim((string) $lab['responsable_cel']) : null;
                        $respWeb       = !empty($lab['responsable_web']) ? trim((string) $lab['responsable_web']) : null;

                        $respWaHref = null;
                        if ($respCel !== null) {
                            $celLimpio = preg_replace('/[^0-9]/', '', $respCel);
                            $codWa = (strlen($celLimpio) === 8) ? '591' . $celLimpio : $celLimpio;
                            $respWaHref = "https://wa.me/{$codWa}";
                        }

                        $modalRespAttrs = sprintf(
                            'data-nombre="%s" data-categoria="%s" data-descripcion="%s" data-foto="%s" data-iniciales="%s" data-email="%s" data-whatsapp="%s" data-cel="%s" data-web="%s"',
                            htmlspecialchars($respNombre, ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($respCat, ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($textoRespDesc, ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($respFotoUrl ?? '', ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($respInic, ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($respEmail ?? '', ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($respWaHref ?? '', ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($respCel ?? '', ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($respWeb ?? '', ENT_QUOTES, 'UTF-8')
                        );
                    ?>

                    <article id="lab-<?= $idLab ?>" class="code-window-panel overflow-hidden scroll-mt-24">
                        <!-- Barra Superior Estilo Consola -->
                        <div class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-3.5 border-b border-institucional-gold/40 flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold inline-block"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                                </div>
                                <span class="font-code text-[13px] text-institucional-gold font-medium pl-2 border-l border-white/20">
                                    &lt;/&gt; Laboratorio de <?= htmlspecialchars($nombreLab) ?>
                                </span>
                            </div>
                            <span class="font-code text-xs text-slate-200 flex items-center gap-2">
                                <i class="fas fa-images text-institucional-gold"></i> <?= count($urlsGaleria) ?> fotografías registradas
                            </span>
                        </div>

                        <!-- Cuerpo del Laboratorio: 2 Columnas -->
                        <div class="p-6 sm:p-8 lg:p-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                            
                            <!-- COLUMNA IZQUIERDA (7 cols): Identidad del Lab + Galería Fotográfica -->
                            <div class="lg:col-span-7 space-y-5">
                                <!-- Cabecera del Laboratorio -->
                                <div class="flex items-center gap-4">
                                    <div class="w-13 h-13 p-3.5 rounded-2xl bg-institucional-navy text-institucional-gold flex items-center justify-center text-xl flex-shrink-0 shadow-md">
                                        <i class="<?= $metaLab['icono'] ?>"></i>
                                    </div>
                                    <div>
                                        <span class="font-code text-xs uppercase tracking-wider text-institucional-navy font-semibold block">
                                            <?= htmlspecialchars($metaLab['tag']) ?>
                                        </span>
                                        <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900">
                                            Laboratorio de <?= htmlspecialchars($nombreLab) ?>
                                        </h2>
                                    </div>
                                </div>

                                <?php if ($fotoPortada !== null): ?>
                                    <div class="relative rounded-2xl overflow-hidden border border-slate-200 shadow-md bg-slate-900 aspect-[16/10] group">
                                        <img id="portada-principal-lab-<?= $idLab ?>"
                                             src="<?= htmlspecialchars($fotoPortada) ?>" 
                                             alt="Laboratorio de <?= htmlspecialchars($nombreLab) ?>" 
                                             loading="lazy"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 cursor-pointer btn-abrir-galeria-lab"
                                             data-lab="Laboratorio de <?= htmlspecialchars($nombreLab) ?>"
                                             data-fotos="<?= $jsonGaleria ?>"
                                             data-index="0">

                                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/85 via-slate-900/40 to-transparent p-4 flex items-center justify-between">
                                            <span class="font-code text-xs text-white/90 flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-institucional-gold"></span>
                                                Ambientes del Laboratorio de <?= htmlspecialchars($nombreLab) ?>
                                            </span>
                                            <button type="button"
                                                    class="btn-abrir-galeria-lab inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/95 hover:bg-institucional-gold text-slate-900 text-xs font-semibold transition-all shadow-md"
                                                    data-lab="Laboratorio de <?= htmlspecialchars($nombreLab) ?>"
                                                    data-fotos="<?= $jsonGaleria ?>"
                                                    data-index="0">
                                                <i class="fas fa-expand text-xs"></i>
                                                <span>Ampliar galería (<?= count($urlsGaleria) ?>)</span>
                                            </button>
                                        </div>
                                    </div>

                                    <?php if (count($urlsGaleria) > 1): ?>
                                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-2.5 pt-1">
                                            <?php foreach ($urlsGaleria as $idxFoto => $urlThumb): ?>
                                                <button type="button"
                                                        class="btn-thumb-lab relative rounded-xl overflow-hidden aspect-[4/3] border-2 <?= $idxFoto === 0 ? 'border-institucional-gold ring-2 ring-institucional-gold/30' : 'border-slate-200 opacity-75 hover:opacity-100' ?> transition-all focus:outline-none"
                                                        data-target-img="portada-principal-lab-<?= $idLab ?>"
                                                        data-src="<?= htmlspecialchars($urlThumb) ?>"
                                                        data-index="<?= $idxFoto ?>">
                                                    <img src="<?= htmlspecialchars($urlThumb) ?>" 
                                                         alt="Miniatura <?= $idxFoto + 1 ?>" 
                                                         loading="lazy"
                                                         class="w-full h-full object-cover">
                                                </button>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 aspect-[16/10] flex flex-col items-center justify-center text-slate-400 p-6 text-center">
                                        <i class="fas fa-images text-3xl mb-2 text-institucional-navy/40"></i>
                                        <span class="text-sm font-medium">Galería fotográfica en proceso de carga</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- COLUMNA DERECHA (5 cols): BLOQUE LLAMATIVO DE HORARIO PDF + RESPONSABLE -->
                            <div class="lg:col-span-5 flex flex-col space-y-6">
                                
                                <!-- TARJETA DESTACADA DE HORARIO DEL LABORATORIO (Alto Contraste) -->
                                <?php if ($urlHorario !== null): ?>
                                    <div class="rounded-2xl p-6 bg-gradient-to-br from-institucional-navy via-[#162d6e] to-[#0f1f4d] text-white border-2 border-institucional-gold shadow-xl relative overflow-hidden">
                                        <div class="absolute -right-8 -top-8 w-28 h-28 bg-institucional-gold/20 rounded-full blur-2xl pointer-events-none"></div>

                                        <div class="flex items-start justify-between gap-4 mb-4 relative z-10">
                                            <div class="flex items-center gap-3.5">
                                                <div class="w-12 h-12 rounded-xl bg-institucional-gold text-slate-900 flex items-center justify-center text-xl shadow-[0_0_20px_rgba(234,179,8,0.45)] flex-shrink-0">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                                <div>
                                                    <span class="font-code text-[11px] uppercase tracking-wider text-institucional-gold font-bold flex items-center gap-1.5">
                                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                                        Horario Oficial Disponible
                                                    </span>
                                                    <h3 class="font-display text-lg sm:text-xl font-bold text-white mt-0.5">
                                                        Horario del Laboratorio
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="text-xs text-slate-200 leading-relaxed mb-5 relative z-10">
                                            Consulta la disponibilidad de turnos y sesiones prácticas programadas en el <strong>Laboratorio de <?= htmlspecialchars($nombreLab) ?></strong>.
                                        </p>

                                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 relative z-10">
                                            <button type="button"
                                                    class="btn-previsualizar-pdf flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full bg-institucional-gold hover:bg-yellow-400 text-slate-900 text-xs font-extrabold transition-all shadow-md"
                                                    data-pdf="<?= htmlspecialchars($urlHorario) ?>"
                                                    data-titulo="Horario · Laboratorio de <?= htmlspecialchars($nombreLab) ?>">
                                                <i class="fas fa-eye text-sm"></i>
                                                <span>Ver Horario PDF</span>
                                            </button>

                                            <a href="<?= htmlspecialchars($urlHorario) ?>"
                                               download="Horario_Laboratorio_<?= htmlspecialchars(str_replace(' ', '_', $nombreLab)) ?>.pdf"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full bg-white/10 hover:bg-white/20 text-white border border-white/25 text-xs font-semibold transition-all">
                                                <i class="fas fa-download text-institucional-gold text-xs"></i>
                                                <span>Descargar</span>
                                            </a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <!-- Estado limpio y claro cuando el laboratorio aún no tiene un PDF físico subido -->
                                    <div class="rounded-2xl p-5 bg-white border-2 border-dashed border-slate-300 flex items-center gap-4 shadow-sm">
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-xl flex-shrink-0">
                                            <i class="far fa-clock"></i>
                                        </div>
                                        <div>
                                            <span class="font-code text-[11px] uppercase tracking-wider text-slate-400 font-bold block">
                                                Horario de Laboratorio
                                            </span>
                                            <h3 class="font-display text-base font-bold text-slate-800">
                                                Cronograma en proceso de publicación
                                            </h3>
                                            <p class="text-xs text-slate-500 mt-0.5">
                                                El documento PDF de horarios para este laboratorio estará disponible próximamente.
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- TARJETA DEL DOCENTE RESPONSABLE DEL LABORATORIO -->
                                <?php if ($tieneResp): ?>
                                    <div class="tarjeta-docente tech-card-spotlight flex flex-col justify-between relative overflow-hidden group">
                                        <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-institucional-gold via-yellow-400 to-institucional-navy"></div>

                                        <div class="p-5">
                                            <span class="font-code text-[10px] uppercase tracking-wider text-institucional-navy font-bold block mb-3">
                                                &lt;/&gt; Responsable Académico del Laboratorio
                                            </span>

                                            <div class="flex items-start gap-3.5 mb-4">
                                                <button type="button"
                                                        class="btn-abrir-modal-docente relative flex-shrink-0 focus:outline-none"
                                                        title="Ver perfil de <?= htmlspecialchars($respNombre) ?>"
                                                        <?= $modalRespAttrs ?>>
                                                    <div class="w-16 h-16 rounded-2xl overflow-hidden border-2 border-institucional-gold shadow-md bg-gradient-to-br from-institucional-navy to-[#172554] flex items-center justify-center">
                                                        <?php if ($respFotoUrl !== null): ?>
                                                            <img src="<?= htmlspecialchars($respFotoUrl) ?>" 
                                                                 alt="<?= htmlspecialchars($respNombre) ?>" 
                                                                 loading="lazy"
                                                                 class="w-full h-full object-cover object-top"
                                                                 onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                                                            <span class="hidden font-display font-bold text-lg text-institucional-gold">
                                                                <?= htmlspecialchars($respInic) ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="font-display font-bold text-lg text-institucional-gold">
                                                                <?= htmlspecialchars($respInic) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </button>

                                                <div class="min-w-0 flex-1">
                                                    <span class="font-code inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-white text-institucional-navy border border-blue-200 mb-1.5">
                                                        <?= htmlspecialchars($respCat) ?>
                                                    </span>
                                                    <h3 class="font-display text-base font-bold text-slate-900 leading-snug">
                                                        <button type="button" class="btn-abrir-modal-docente text-left hover:text-institucional-navy hover:underline focus:outline-none" <?= $modalRespAttrs ?>>
                                                            <?= htmlspecialchars($respNombre) ?>
                                                        </button>
                                                    </h3>
                                                </div>
                                            </div>

                                            <div class="bg-white/80 rounded-xl p-3.5 border border-blue-200/60">
                                                <div class="texto-resumen-docente text-xs text-slate-600">
                                                    <?= $textoRespDesc ?>
                                                </div>
                                                <div class="contenedor-ver-mas hidden mt-2 pt-2 border-t border-slate-200/60 flex justify-end">
                                                    <button type="button"
                                                            class="btn-abrir-modal-docente inline-flex items-center gap-1 text-xs font-semibold text-institucional-navy hover:text-yellow-600 transition-colors"
                                                            <?= $modalRespAttrs ?>>
                                                        <span>Ver más</span>
                                                        <i class="fas fa-angle-down text-xs"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="px-5 py-3 bg-white/90 border-t border-blue-200/60 flex flex-wrap items-center justify-between gap-2">
                                            <div class="flex flex-wrap items-center gap-1.5">
                                                <?php if ($respEmail !== null): ?>
                                                    <a href="mailto:<?= htmlspecialchars($respEmail) ?>" 
                                                       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-50 hover:bg-institucional-navy text-slate-700 hover:text-white border border-slate-200 text-xs font-medium transition-all"
                                                       title="Enviar correo a <?= htmlspecialchars($respEmail) ?>">
                                                        <i class="fas fa-envelope text-[10px]"></i>
                                                        <span class="truncate max-w-[120px]"><?= htmlspecialchars($respEmail) ?></span>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if ($respCel !== null && $respWaHref !== null): ?>
                                                    <a href="<?= htmlspecialchars($respWaHref) ?>" 
                                                       target="_blank" 
                                                       rel="noopener noreferrer"
                                                       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 hover:bg-emerald-600 text-emerald-800 hover:text-white border border-emerald-200 text-xs font-mono font-medium transition-all"
                                                       title="Contactar por WhatsApp">
                                                        <i class="fab fa-whatsapp text-xs"></i>
                                                        <span><?= htmlspecialchars($respCel) ?></span>
                                                    </a>
                                                <?php endif; ?>
                                            </div>

                                            <button type="button"
                                                    class="btn-abrir-modal-docente inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-institucional-navy hover:bg-institucional-navy-dark text-white text-xs font-medium transition-all shadow-sm ml-auto"
                                                    <?= $modalRespAttrs ?>>
                                                <i class="far fa-id-badge text-institucional-gold text-xs"></i>
                                                <span>Ver perfil</span>
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>

                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <div class="code-window-panel p-10 text-center text-slate-500">
                No hay laboratorios activos registrados en este momento.
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- 1. MODAL DE PERFIL ACADÉMICO DEL RESPONSABLE -->
<div id="modal-perfil-docente" class="fixed inset-0 z-50 hidden bg-slate-900/75 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-200">
    <div id="modal-perfil-card" class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-xl w-full overflow-hidden transform scale-95 transition-transform duration-200 max-h-[90vh] flex flex-col">
        <div class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-3.5 border-b-2 border-institucional-gold flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold"></span>
                <span class="font-code text-xs text-institucional-gold font-medium uppercase tracking-wider">&lt;/&gt; perfil académico y profesional</span>
            </div>
            <button type="button" id="btn-cerrar-modal-docente" class="text-slate-300 hover:text-institucional-gold transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="p-6 sm:p-8 overflow-y-auto">
            <div class="flex flex-col items-center text-center pb-6 border-b border-slate-200">
                <div class="w-32 h-32 sm:w-36 sm:h-36 rounded-2xl overflow-hidden border-4 border-institucional-gold shadow-lg bg-gradient-to-br from-institucional-navy to-[#172554] flex items-center justify-center mb-4">
                    <img id="modal-docente-foto" src="" alt="Foto Docente" class="hidden w-full h-full object-cover object-top">
                    <span id="modal-docente-iniciales" class="font-display font-bold text-4xl text-institucional-gold tracking-wider"></span>
                </div>

                <h3 id="modal-docente-nombre" class="font-display text-xl sm:text-2xl font-bold text-slate-900 leading-snug"></h3>
                <span id="modal-docente-categoria" class="font-code inline-block px-3.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-institucional-navy border border-blue-200 mt-2 mb-1"></span>
                <p class="text-xs text-slate-500 mt-1">Carrera Ingeniería Informática · U.N.S.XX</p>
            </div>

            <div class="mt-5">
                <div id="modal-docente-descripcion" class="quill-content text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 sm:p-5 rounded-xl border border-slate-200/80"></div>
            </div>

            <div id="modal-docente-contactos" class="mt-6 pt-4 border-t border-slate-200 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2" id="modal-docente-links"></div>
                <button type="button" id="btn-cerrar-modal-inferior" class="px-5 py-2 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors ml-auto">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 2. MODAL VISOR DE HORARIO PDF -->
<div id="modal-visor-pdf" class="fixed inset-0 z-50 hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 opacity-0 transition-opacity duration-200">
    <div id="modal-visor-pdf-card" class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-5xl w-full h-[88vh] flex flex-col overflow-hidden transform scale-95 transition-transform duration-200">
        <div class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-5 py-3.5 border-b-2 border-institucional-gold flex items-center justify-between gap-4 flex-shrink-0">
            <div class="flex items-center gap-2.5 min-w-0">
                <i class="fas fa-file-pdf text-institucional-gold"></i>
                <h3 id="modal-pdf-titulo" class="font-display text-sm sm:text-base font-bold text-white truncate"></h3>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <a id="modal-pdf-nueva-pestana" href="#" target="_blank" rel="noopener noreferrer" class="font-code text-xs text-institucional-gold hover:underline flex items-center gap-1">
                    <span>Abrir en pestaña</span>
                    <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
                <button type="button" id="btn-cerrar-visor-pdf" class="text-slate-300 hover:text-institucional-gold transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
        <div class="flex-grow bg-slate-100">
            <iframe id="iframe-visor-pdf" src="" title="Visor PDF Horario de Laboratorio" class="w-full h-full border-0"></iframe>
        </div>
    </div>
</div>

<!-- 3. MODAL LIGHTBOX DE GALERÍA FOTOGRÁFICA DEL LABORATORIO -->
<div id="modal-galeria-lab" class="fixed inset-0 z-50 hidden bg-slate-950/90 backdrop-blur-md flex items-center justify-center p-4 opacity-0 transition-opacity duration-200">
    <div id="modal-galeria-lab-card" class="bg-slate-900 rounded-2xl border border-white/15 shadow-2xl max-w-4xl w-full overflow-hidden transform scale-95 transition-transform duration-200">
        <div class="px-6 py-3.5 bg-institucional-navy border-b border-institucional-gold/40 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="font-code text-xs text-institucional-gold font-semibold" id="modal-galeria-contador">1 / 1</span>
                <h3 id="modal-galeria-titulo" class="font-display text-sm sm:text-base font-bold text-white"></h3>
            </div>
            <button type="button" id="btn-cerrar-galeria-lab" class="text-slate-300 hover:text-institucional-gold transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="relative bg-slate-950 flex items-center justify-center h-[60vh] sm:h-[68vh]">
            <button type="button" id="btn-galeria-prev" class="absolute left-3 sm:left-5 z-10 w-10 h-10 rounded-full bg-white/10 hover:bg-institucional-gold text-white hover:text-slate-900 flex items-center justify-center transition-colors">
                <i class="fas fa-chevron-left"></i>
            </button>

            <img id="modal-galeria-img" src="" alt="Fotografía de Laboratorio" class="max-h-full max-w-full object-contain p-2">

            <button type="button" id="btn-galeria-next" class="absolute right-3 sm:right-5 z-10 w-10 h-10 rounded-full bg-white/10 hover:bg-institucional-gold text-white hover:text-slate-900 flex items-center justify-center transition-colors">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.btn-thumb-lab').forEach((thumb) => {
            thumb.addEventListener('click', () => {
                const targetId = thumb.getAttribute('data-target-img');
                const nuevaSrc = thumb.getAttribute('data-src');
                const nuevoIdx = thumb.getAttribute('data-index') || '0';
                const imgPrincipal = document.getElementById(targetId);

                if (imgPrincipal && nuevaSrc) {
                    imgPrincipal.src = nuevaSrc;
                    imgPrincipal.setAttribute('data-index', nuevoIdx);
                }

                const contenedorThumbs = thumb.parentElement;
                contenedorThumbs?.querySelectorAll('.btn-thumb-lab').forEach((t) => {
                    t.className = 'btn-thumb-lab relative rounded-xl overflow-hidden aspect-[4/3] border-2 border-slate-200 opacity-75 hover:opacity-100 transition-all focus:outline-none';
                });
                thumb.className = 'btn-thumb-lab relative rounded-xl overflow-hidden aspect-[4/3] border-2 border-institucional-gold ring-2 ring-institucional-gold/30 transition-all focus:outline-none';
            });
        });

        const modalGal = document.getElementById('modal-galeria-lab');
        const cardGal = document.getElementById('modal-galeria-lab-card');
        const imgGal = document.getElementById('modal-galeria-img');
        const tituloGal = document.getElementById('modal-galeria-titulo');
        const contadorGal = document.getElementById('modal-galeria-contador');
        const btnPrev = document.getElementById('btn-galeria-prev');
        const btnNext = document.getElementById('btn-galeria-next');
        const btnCerrarGal = document.getElementById('btn-cerrar-galeria-lab');

        let fotosActuales = [];
        let indiceActual = 0;

        const renderizarFotoGaleria = () => {
            if (fotosActuales.length === 0) return;
            imgGal.src = fotosActuales[indiceActual];
            contadorGal.textContent = `${indiceActual + 1} / ${fotosActuales.length}`;
            btnPrev.classList.toggle('hidden', fotosActuales.length <= 1);
            btnNext.classList.toggle('hidden', fotosActuales.length <= 1);
        };

        document.querySelectorAll('.btn-abrir-galeria-lab').forEach((btn) => {
            btn.addEventListener('click', () => {
                try {
                    fotosActuales = JSON.parse(btn.getAttribute('data-fotos') || '[]');
                } catch {
                    fotosActuales = [];
                }
                if (fotosActuales.length === 0) return;

                indiceActual = parseInt(btn.getAttribute('data-index') || '0', 10) || 0;
                tituloGal.textContent = btn.getAttribute('data-lab') || 'Galería de Laboratorio';
                renderizarFotoGaleria();

                modalGal.classList.remove('hidden');
                void modalGal.offsetWidth;
                modalGal.classList.remove('opacity-0');
                cardGal.classList.remove('scale-95');
            });
        });

        btnPrev?.addEventListener('click', () => {
            if (fotosActuales.length <= 1) return;
            indiceActual = (indiceActual - 1 + fotosActuales.length) % fotosActuales.length;
            renderizarFotoGaleria();
        });

        btnNext?.addEventListener('click', () => {
            if (fotosActuales.length <= 1) return;
            indiceActual = (indiceActual + 1) % fotosActuales.length;
            renderizarFotoGaleria();
        });

        const cerrarGaleria = () => {
            modalGal.classList.add('opacity-0');
            cardGal.classList.add('scale-95');
            setTimeout(() => {
                modalGal.classList.add('hidden');
                imgGal.src = '';
            }, 200);
        };

        btnCerrarGal?.addEventListener('click', cerrarGaleria);
        modalGal?.addEventListener('click', (e) => {
            if (e.target === modalGal) cerrarGaleria();
        });

        const modalPdf = document.getElementById('modal-visor-pdf');
        const cardPdf = document.getElementById('modal-visor-pdf-card');
        const iframePdf = document.getElementById('iframe-visor-pdf');
        const tituloPdf = document.getElementById('modal-pdf-titulo');
        const linkPestana = document.getElementById('modal-pdf-nueva-pestana');
        const btnCerrarPdf = document.getElementById('btn-cerrar-visor-pdf');

        document.querySelectorAll('.btn-previsualizar-pdf').forEach((btn) => {
            btn.addEventListener('click', () => {
                const url = btn.getAttribute('data-pdf') || '';
                const titulo = btn.getAttribute('data-titulo') || 'Horario PDF';

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