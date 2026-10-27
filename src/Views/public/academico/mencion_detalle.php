<?php
/**
 * Vista Única Reutilizable: Menciones (Seguridad Informática / Computación Móvil) e Instituto IIDAI
 * Archivo: src/Views/public/academico/mencion_detalle.php
 *
 * Variables inyectadas por HomeController:
 * @var array|null $item                 Registro de la tabla `mencion_instituto` con datos del coordinador
 * @var bool       $esIidai              true = Instituto IIDAI ("Descripción"), false = Mención ("Perfil Profesional")
 * @var array      $enlacesInvestigacion Enlaces con ubicacion = 'menu_investigacion'
 */

$esInstituto = !empty($esIidai);
$nombreCorto = trim((string) ($item['nombre'] ?? ($esInstituto ? 'IIDAI' : 'Especialidad')));
$nomLower    = mb_strtolower($nombreCorto, 'UTF-8');

$etiquetaBreadcrumb     = $esInstituto ? 'investigación / instituto iidai' : 'mención / ' . $nomLower;
$tituloSeccionContenido = $esInstituto ? 'Descripción' : 'Perfil Profesional';
$subtituloConsola       = $esInstituto ? 'Instituto de Investigación y Desarrollo de Aplicaciones Informáticas' : 'Competencias y Plan de Estudios de la Mención';

// Escudo oficial en /uploads/img/menciones/
$escudoArchivo = trim((string) ($item['escudo_url'] ?? ''));
$urlEscudo = ($escudoArchivo !== '' && strtolower($escudoArchivo) !== 'default.png')
    ? '/uploads/img/menciones/' . rawurlencode($escudoArchivo)
    : '/assets/img/logo-informatica.png';

/**
 * Helper global para sanitizar y formatear textos que pueden venir de Quill.js (HTML) o texto plano antiguo
 */
if (!function_exists('formatearTextoQuill')) {
    function formatearTextoQuill(?string $texto): string {
        $raw = trim((string) $texto);
        if ($raw === '') {
            return '';
        }
        // Si contiene etiquetas HTML de Quill (<p>, <strong>, <ul>, <ol>, <li>, <br>, etc.)
        if ($raw !== strip_tags($raw)) {
            $limpio = strip_tags($raw, '<p><br><strong><b><em><i><u><ul><ol><li><h3><h4><span><a>');
            // Quitamos estilos background-color inline que pega Quill para mantener limpieza visual
            $limpio = preg_replace('/background-color:\s*[^;"\']+;?/i', '', (string) $limpio);
            return trim((string) $limpio);
        }
        // Si es texto plano tradicional
        return nl2br(htmlspecialchars($raw, ENT_QUOTES, 'UTF-8'));
    }
}

/**
 * Procesamiento inteligente de `perfil_profesional` (Soporta HTML de Quill.js o texto con guiones "-")
 */
$textoRaw = trim((string) ($item['perfil_profesional'] ?? ''));
$contieneHtmlQuill = ($textoRaw !== strip_tags($textoRaw));
$itemsLista = [];
$htmlFormateado = '';

if ($contieneHtmlQuill) {
    $htmlFormateado = formatearTextoQuill($textoRaw);
} else {
    $lineasRaw = preg_split('/\r\n|\r|\n/', $textoRaw) ?: [];
    $parrafosPlanos = [];
    foreach ($lineasRaw as $linea) {
        $lineaLimpia = trim($linea);
        if ($lineaLimpia === '') {
            continue;
        }
        if (!$esInstituto && str_starts_with($lineaLimpia, '-')) {
            $itemsLista[] = trim(ltrim($lineaLimpia, '-'));
        } else {
            $parrafosPlanos[] = '<p>' . htmlspecialchars($lineaLimpia, ENT_QUOTES, 'UTF-8') . '</p>';
        }
    }
    $htmlFormateado = implode('', $parrafosPlanos);
}

/**
 * Materias específicas de 3° y 4° Año según la Mención (Tríptico Oficial)
 */
$materiasMencion = [];
if (!$esInstituto) {
    if (str_contains($nomLower, 'seguridad')) {
        $materiasMencion = [
            'Tercer Año' => [
                'icono'    => 'fas fa-shield-halved',
                'subtitulo'=> 'Seguridad en Infraestructura, Redes y Datos',
                'materias' => [
                    'Servidores y Seguridad en Redes',
                    'Ciberseguridad y Análisis de Vulnerabilidades',
                    'Seguridad en Sistemas de Información y Base de Datos',
                    'Criptografía y Estenografía',
                ],
            ],
            'Cuarto Año' => [
                'icono'    => 'fas fa-user-secret',
                'subtitulo'=> 'Hacking Ético, Forense y Auditoría de TI',
                'materias' => [
                    'Inteligencia de Amenazas y Ethical Hacking',
                    'Informática Forense',
                    'Auditoría Informática',
                    'Sistemas de Gestión de Seguridad de Tecnologías de la Información',
                ],
            ],
        ];
    } elseif (str_contains($nomLower, 'móvil') || str_contains($nomLower, 'movil')) {
        $materiasMencion = [
            'Tercer Año' => [
                'icono'    => 'fas fa-mobile-screen-button',
                'subtitulo'=> 'Arquitectura Móvil, Cloud y Videojuegos',
                'materias' => [
                    'Programación de Dispositivos Móviles',
                    'Desarrollo de Videojuegos para Dispositivos Móviles',
                    'Redes Móviles y Cloud Computing',
                    'Desarrollo de Aplicaciones Móviles para Geolocalización',
                ],
            ],
            'Cuarto Año' => [
                'icono'    => 'fas fa-vr-cardboard',
                'subtitulo'=> 'Realidad Extendida, Visión Artificial e IoT',
                'materias' => [
                    'Realidad Virtual y Aumentada',
                    'Visión Artificial para Dispositivos Móviles',
                    'Taller de Desarrollo de Aplicaciones Móviles',
                    'Internet de las Cosas',
                ],
            ],
        ];
    }
}

/**
 * Helpers para la tarjeta y modal del Coordinador
 */
if (!function_exists('obtenerInicialesDocente')) {
    function obtenerInicialesDocente(string $nombreCompleto): string {
        $limpio = preg_replace('/\b(M\.Sc\.|M\.Ss\.|Ph\.D\.|Ing\.|Lic\.|Dr\.|Mgr\.)\s*/i', '', $nombreCompleto);
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

// Datos del Docente Coordinador
$tieneCoord    = !empty($item['coordinador_nombre']);
$coordNombre   = trim((string) ($item['coordinador_nombre'] ?? 'Por designar'));
$coordCat      = trim((string) ($item['coordinador_categoria'] ?? ($esInstituto ? 'Coordinador del IIDAI' : 'Coordinador de Mención')));
$coordDescRaw  = trim((string) ($item['coordinador_desc'] ?? ''));
$coordDescHtml = formatearTextoQuill($coordDescRaw !== '' ? $coordDescRaw : "{$coordCat} de la Carrera de Ingeniería Informática - UNSXX.");
$coordFoto     = obtenerUrlFotoDocente($item['coordinador_foto'] ?? null);
$coordInic     = obtenerInicialesDocente($coordNombre);
$coordEmail    = !empty($item['coordinador_email']) ? trim((string) $item['coordinador_email']) : null;
$coordCel      = !empty($item['coordinador_cel']) ? trim((string) $item['coordinador_cel']) : null;
$coordWeb      = !empty($item['coordinador_web']) ? trim((string) $item['coordinador_web']) : null;

$coordWaHref = null;
if ($coordCel !== null) {
    $celLimpio = preg_replace('/[^0-9]/', '', $coordCel);
    $codWa = (strlen($celLimpio) === 8) ? '591' . $celLimpio : $celLimpio;
    $coordWaHref = "https://wa.me/{$codWa}";
}

$modalCoordAttrs = sprintf(
    'data-nombre="%s" data-categoria="%s" data-descripcion="%s" data-foto="%s" data-iniciales="%s" data-email="%s" data-whatsapp="%s" data-cel="%s" data-web="%s"',
    htmlspecialchars($coordNombre, ENT_QUOTES, 'UTF-8'),
    htmlspecialchars($coordCat, ENT_QUOTES, 'UTF-8'),
    htmlspecialchars($coordDescHtml, ENT_QUOTES, 'UTF-8'),
    htmlspecialchars($coordFoto ?? '', ENT_QUOTES, 'UTF-8'),
    htmlspecialchars($coordInic, ENT_QUOTES, 'UTF-8'),
    htmlspecialchars($coordEmail ?? '', ENT_QUOTES, 'UTF-8'),
    htmlspecialchars($coordWaHref ?? '', ENT_QUOTES, 'UTF-8'),
    htmlspecialchars($coordCel ?? '', ENT_QUOTES, 'UTF-8'),
    htmlspecialchars($coordWeb ?? '', ENT_QUOTES, 'UTF-8')
);
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
                    <span class="font-code text-xs font-medium text-institucional-gold uppercase tracking-wider">&lt;/&gt; <?= htmlspecialchars($etiquetaBreadcrumb) ?></span>
                </div>

                <?php if ($esInstituto): ?>
                    <h1 class="font-display text-3xl sm:text-5xl font-bold tracking-tight leading-[1.13] text-white">
                        Instituto <span class="text-institucional-gold"><?= htmlspecialchars($nombreCorto) ?></span>
                    </h1>
                    <p class="mt-4 text-base text-slate-200 font-light leading-relaxed">
                        Instituto de Investigación y Desarrollo de Aplicaciones Informáticas de la Carrera de Ingeniería Informática · Universidad Nacional "Siglo XX".
                    </p>
                <?php else: ?>
                    <h1 class="font-display text-3xl sm:text-5xl font-bold tracking-tight leading-[1.13] text-white">
                        Mención <span class="text-institucional-gold"><?= htmlspecialchars($nombreCorto) ?></span>
                    </h1>
                    <p class="mt-4 text-base text-slate-200 font-light leading-relaxed">
                        Especialidad curricular de formación profesional a nivel Licenciatura en la Carrera de Ingeniería Informática · Universidad Nacional "Siglo XX".
                    </p>
                <?php endif; ?>
            </div>

            <!-- Insignia / Escudo en el Hero -->
            <div class="relative self-start lg:self-center w-full sm:w-auto">
                <div class="bg-[#0f1f4d]/95 border border-institucional-gold/40 rounded-2xl p-4 sm:p-5 flex items-center gap-4 backdrop-blur-xl shadow-2xl">
                    <div class="w-16 h-16 rounded-xl bg-white p-1.5 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <img src="<?= htmlspecialchars($urlEscudo) ?>" 
                            alt="Escudo <?= htmlspecialchars($nombreCorto) ?>" 
                            class="w-full h-full object-contain">
                    </div>
                    <div class="pr-2">
                        <span class="font-code text-[11px] uppercase tracking-wider text-institucional-gold block">
                            <?= $esInstituto ? '' : 'Mención' ?>
                        </span>
                        <span class="font-display font-extrabold text-xl sm:text-2xl text-white block mt-0.5">
                            <?= htmlspecialchars($nombreCorto) ?>
                        </span>
                        <span class="text-xs text-slate-300 font-light block mt-0.5">
                            <?= $esInstituto ? 'Instituto de Investigación y Desarrollo de Aplicaciones Informáticas' : 'Ingeniería Informática · UNSXX' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CONTENIDO PRINCIPAL: Lienzo Claro con Luces Aurora -->
<section class="relative py-12 sm:py-16 overflow-hidden">
    <!-- Luces Aurora difuminadas -->
    <div class="absolute top-10 left-1/4 -translate-x-1/2 w-[36rem] h-[24rem] bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-32 right-8 w-[28rem] h-[22rem] bg-institucional-gold/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-20 left-1/3 w-[34rem] h-[24rem] bg-institucional-navy/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-12">

        <?php if ($item !== null): ?>
            <div class="code-window-panel overflow-hidden">
                <!-- Barra Superior Estilo Consola -->
                <div class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-3.5 border-b border-institucional-gold/40 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold inline-block"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                        </div>
                        <span class="font-code text-[13px] text-institucional-gold font-medium pl-2 border-l border-white/20">
                            &lt;/&gt; <?= htmlspecialchars($subtituloConsola) ?>
                        </span>
                    </div>
                    <span class="font-code text-xs text-slate-200 flex items-center gap-2">
                        <i class="fas <?= $esInstituto ? 'fa-flask' : 'fa-graduation-cap' ?> text-institucional-gold"></i>
                        <?= htmlspecialchars($tituloSeccionContenido) ?>
                    </span>
                </div>

                <!-- Cuerpo en 2 Columnas -->
                <div class="p-6 sm:p-8 lg:p-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    <!-- COLUMNA IZQUIERDA (7 cols): Perfil Profesional / Descripción + Materias de 3° y 4° Año -->
                    <div class="lg:col-span-7 flex flex-col space-y-6">
                        
                        <!-- Tarjeta 1: Perfil Profesional (Menciones) o Descripción (IIDAI) -->
                        <div class="tech-card w-full p-6 sm:p-8 relative overflow-hidden">
                            <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-institucional-navy via-blue-600 to-institucional-gold"></div>

                            <div class="flex items-center justify-between gap-4 pb-5 mb-6 border-b border-slate-200/90">
                                <div>
                                    <span class="font-code text-xs font-medium uppercase tracking-wider text-institucional-navy bg-blue-50 px-3 py-1 rounded-full border border-blue-200/80">
                                        <?= $esInstituto ? 'Investigación e Innovación' : 'Formación Especializada' ?>
                                    </span>
                                    <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900 mt-2.5">
                                        <?= htmlspecialchars($tituloSeccionContenido) ?>
                                    </h2>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-institucional-navy text-institucional-gold flex items-center justify-center text-xl shadow-md flex-shrink-0">
                                    <i class="fas <?= $esInstituto ? 'fa-microscope' : 'fa-user-check' ?>"></i>
                                </div>
                            </div>

                            <!-- Contenido enriquecido de Quill.js (<p>, <ul>, <strong>, etc.) o párrafos descriptivos -->
                            <?php if ($htmlFormateado !== ''): ?>
                                <?php if (!$esInstituto && !empty($materiasMencion)): ?>
                                    <p class="text-sm text-slate-600 mb-4">
                                        El Ingeniero Informático titulado con la mención en <strong><?= htmlspecialchars($nombreCorto) ?></strong> cuenta con las siguientes competencias profesionales:
                                    </p>
                                <?php endif; ?>
                                <div class="quill-content text-sm sm:text-base text-slate-700 leading-relaxed bg-slate-50/80 p-5 rounded-xl border border-slate-200/80 <?= !empty($itemsLista) ? 'mt-5' : '' ?>">
                                    <?= $htmlFormateado ?>
                                </div>
                            <?php endif; ?>

                            <!-- Si es IIDAI y existen enlaces de investigación, los listamos aquí -->
                            <?php if ($esInstituto && !empty($enlacesInvestigacion)): ?>
                                <div class="mt-7 pt-6 border-t border-slate-200/90">
                                    <span class="font-code text-xs uppercase tracking-wider text-institucional-navy font-bold block mb-3">
                                        <i class="fas fa-book-open text-institucional-gold mr-1"></i> Publicaciones y Enlaces Científicos
                                    </span>
                                    <div class="flex flex-wrap gap-3">
                                        <?php foreach ($enlacesInvestigacion as $enlInv): ?>
                                            <a href="<?= htmlspecialchars($enlInv['url']) ?>"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-institucional-navy hover:bg-institucional-navy-dark text-white text-xs font-medium transition-all shadow-sm">
                                                <i class="<?= htmlspecialchars($enlInv['icono'] ?: 'fas fa-book-open') ?> text-institucional-gold"></i>
                                                <span><?= htmlspecialchars($enlInv['nombre']) ?></span>
                                                <i class="fas fa-arrow-up-right-from-square text-[10px] text-institucional-gold/80"></i>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Tarjeta 2: Plan de Estudios de la Mención (Solo 3° y 4° Año de la Mención correspondiente) -->
                        <?php if (!$esInstituto && !empty($materiasMencion)): ?>
                            <div class="tech-card w-full p-6 sm:p-8 relative overflow-hidden">
                                <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-institucional-gold via-yellow-400 to-institucional-navy"></div>

                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 mb-6 border-b border-slate-200/90">
                                    <div>
                                        <span class="font-code text-xs font-medium uppercase tracking-wider text-amber-900 bg-amber-50 px-3 py-1 rounded-full border border-amber-300">
                                            &lt;/&gt; Ruta de Especialización
                                        </span>
                                        <h3 class="font-display text-xl sm:text-2xl font-bold text-slate-900 mt-2">
                                            Asignaturas de la Mención (3° y 4° Año)
                                        </h3>
                                    </div>
                                    <a href="/academico/plan-estudios" class="inline-flex items-center gap-1.5 text-xs font-semibold text-institucional-navy hover:text-yellow-600 transition-colors self-start sm:self-center">
                                        <span>Ver malla completa</span>
                                        <i class="fas fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <?php foreach ($materiasMencion as $nombreAnio => $infoAnio): ?>
                                        <div class="rounded-2xl p-5 bg-slate-50/90 border border-slate-200/90 flex flex-col justify-between">
                                            <div>
                                                <div class="flex items-center gap-3 pb-3 mb-4 border-b border-slate-200">
                                                    <div class="w-10 h-10 rounded-xl bg-institucional-navy text-institucional-gold flex items-center justify-center text-base shadow-sm flex-shrink-0">
                                                        <i class="<?= $infoAnio['icono'] ?>"></i>
                                                    </div>
                                                    <div>
                                                        <span class="font-code text-[11px] uppercase tracking-wider text-institucional-navy font-bold block">
                                                            <?= htmlspecialchars($nombreAnio) ?>
                                                        </span>
                                                        <span class="text-xs text-slate-500 font-medium">
                                                            <?= htmlspecialchars($infoAnio['subtitulo']) ?>
                                                        </span>
                                                    </div>
                                                </div>

                                                <ul class="space-y-2.5">
                                                    <?php foreach ($infoAnio['materias'] as $mat): ?>
                                                        <li class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-800 bg-white p-3 rounded-xl border border-slate-200/80 shadow-sm">
                                                            <i class="fas fa-check-circle text-institucional-gold mt-0.5 text-xs flex-shrink-0"></i>
                                                            <span class="font-medium leading-snug"><?= htmlspecialchars($mat) ?></span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>

                    <!-- COLUMNA DERECHA (5 cols): Escudo Oficial Grande Centrado en Fondo Blanco + Coordinador -->
                    <div class="lg:col-span-5 flex flex-col space-y-6">
                        
                        <!-- Tarjeta del Escudo Oficial: Grande, Centrado y con Fondo Blanco -->
                        <div class="tech-card p-6 sm:p-8 flex flex-col items-center text-center relative overflow-hidden">
                            <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-institucional-gold via-yellow-400 to-institucional-navy"></div>
                            
                            <span class="font-code text-[11px] uppercase tracking-wider text-slate-400 font-semibold mb-4">
                                Escudo Oficial
                            </span>

                            <!-- Contenedor Blanco Grande y Centrado para el Escudo -->
                            <div class="w-36 h-36 sm:w-44 sm:h-44 rounded-3xl bg-white p-4 flex items-center justify-center border-2 border-institucional-gold/70 shadow-[0_12px_30px_-8px_rgba(30,58,138,0.16)] mb-5">
                                <img src="<?= htmlspecialchars($urlEscudo) ?>" 
                                     alt="Escudo <?= htmlspecialchars($nombreCorto) ?>" 
                                     class="w-full h-full object-contain">
                            </div>

                            <h3 class="font-display text-xl sm:text-2xl font-bold text-slate-900">
                                <?= htmlspecialchars($esInstituto ? 'Instituto ' . $nombreCorto : 'Mención ' . $nombreCorto) ?>
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">
                                Carrera de Ingeniería Informática · U.N.S.XX
                            </p>
                        </div>

                        <!-- Tarjeta del Docente Coordinador -->
                        <?php if ($tieneCoord): ?>
                            <article class="tarjeta-docente tech-card-spotlight flex flex-col justify-between relative overflow-hidden group">
                                <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-institucional-navy via-blue-600 to-institucional-gold"></div>

                                <div class="p-6 flex-grow flex flex-col">
                                    <span class="font-code text-[11px] uppercase tracking-wider text-institucional-navy font-bold block mb-4">
                                        &lt;/&gt; <?= $esInstituto ? 'Coordinación del Instituto IIDAI' : 'Coordinación de la Mención' ?>
                                    </span>

                                    <div class="flex items-start gap-4 mb-5">
                                        <button type="button" 
                                                class="btn-abrir-modal-docente relative flex-shrink-0 focus:outline-none"
                                                title="Ver ficha completa de <?= htmlspecialchars($coordNombre) ?>"
                                                <?= $modalCoordAttrs ?>>
                                            <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-institucional-gold shadow-md bg-gradient-to-br from-institucional-navy to-[#172554] flex items-center justify-center transition-colors">
                                                <?php if ($coordFoto !== null): ?>
                                                    <img src="<?= htmlspecialchars($coordFoto) ?>" 
                                                         alt="<?= htmlspecialchars($coordNombre) ?>" 
                                                         loading="lazy"
                                                         class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300"
                                                         onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                                                    <span class="hidden font-display font-bold text-xl text-institucional-gold tracking-wider">
                                                        <?= htmlspecialchars($coordInic) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="font-display font-bold text-xl text-institucional-gold tracking-wider">
                                                        <?= htmlspecialchars($coordInic) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </button>

                                        <div class="min-w-0 flex-1">
                                            <span class="font-code inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-blue-50 text-institucional-navy border border-blue-200 mb-2">
                                                <?= htmlspecialchars($coordCat) ?>
                                            </span>
                                            <h3 class="font-display text-lg font-bold text-slate-900 leading-snug group-hover:text-institucional-navy transition-colors">
                                                <button type="button" class="btn-abrir-modal-docente text-left hover:underline focus:outline-none" <?= $modalCoordAttrs ?>>
                                                    <?= htmlspecialchars($coordNombre) ?>
                                                </button>
                                            </h3>
                                        </div>
                                    </div>

                                    <!-- Resumen Curricular con soporte Quill.js y "Ver más" automático -->
                                    <div class="bg-white/85 rounded-xl p-4 border border-blue-200/60 flex-grow flex flex-col justify-between">
                                        <div class="texto-resumen-docente quill-content text-sm text-slate-600 leading-relaxed line-clamp-3">
                                            <?= $coordDescHtml ?>
                                        </div>

                                        <div class="contenedor-ver-mas hidden mt-3 pt-2 border-t border-slate-200/60 flex justify-end">
                                            <button type="button"
                                                    class="btn-abrir-modal-docente inline-flex items-center gap-1.5 text-xs font-semibold text-institucional-navy hover:text-yellow-600 transition-colors"
                                                    <?= $modalCoordAttrs ?>>
                                                <span>Ver más</span>
                                                <i class="fas fa-angle-down text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pie de Tarjeta del Coordinador -->
                                <div class="px-6 py-3.5 bg-white/90 border-t border-blue-200/60 flex flex-wrap items-center justify-between gap-2">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <?php if ($coordEmail !== null): ?>
                                            <a href="mailto:<?= htmlspecialchars($coordEmail) ?>" 
                                               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full bg-slate-50 hover:bg-institucional-navy text-slate-700 hover:text-white border border-slate-200 text-xs font-medium transition-all shadow-sm"
                                               title="Enviar correo a <?= htmlspecialchars($coordEmail) ?>">
                                                <i class="fas fa-envelope text-institucional-navy group-hover:text-current text-[11px]"></i>
                                                <span class="truncate max-w-[125px]"><?= htmlspecialchars($coordEmail) ?></span>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($coordCel !== null && $coordWaHref !== null): ?>
                                            <a href="<?= htmlspecialchars($coordWaHref) ?>" 
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full bg-emerald-50 hover:bg-emerald-600 text-emerald-800 hover:text-white border border-emerald-200 text-xs font-mono font-medium transition-all shadow-sm"
                                               title="Contactar por WhatsApp">
                                                <i class="fab fa-whatsapp text-xs"></i>
                                                <span><?= htmlspecialchars($coordCel) ?></span>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($coordWeb !== null): ?>
                                            <a href="<?= htmlspecialchars($coordWeb) ?>" 
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-white hover:bg-institucional-navy text-institucional-navy hover:text-institucional-gold border border-slate-200 text-xs transition-all shadow-sm"
                                               title="Visitar Sitio Web / Portafolio">
                                                <i class="fas fa-globe text-[11px]"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>

                                    <button type="button"
                                            class="btn-abrir-modal-docente inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-institucional-navy hover:bg-institucional-navy-dark text-white text-xs font-medium transition-all shadow-sm ml-auto"
                                            <?= $modalCoordAttrs ?>>
                                        <i class="far fa-id-badge text-institucional-gold text-xs"></i>
                                        <span>Ver perfil</span>
                                    </button>
                                </div>
                            </article>
                        <?php endif; ?>

                    </div>

                </div>
            </div>
        <?php else: ?>
            <div class="code-window-panel p-10 text-center text-slate-500">
                Información en proceso de actualización.
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- MODAL DE PERFIL ACADÉMICO DEL COORDINADOR -->
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
                    <img id="modal-docente-foto" src="" alt="Foto Coordinador" class="hidden w-full h-full object-cover object-top">
                    <span id="modal-docente-iniciales" class="font-display font-bold text-4xl text-institucional-gold tracking-wider"></span>
                </div>

                <h3 id="modal-docente-nombre" class="font-display text-xl sm:text-2xl font-bold text-slate-900 leading-snug"></h3>
                <span id="modal-docente-categoria" class="font-code inline-block px-3.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-institucional-navy border border-blue-200 mt-2 mb-1"></span>
                <p class="text-xs text-slate-500 mt-1">Carrera Ingeniería Informática · U.N.S.XX</p>
            </div>

            <div class="mt-5">
                <div id="modal-docente-descripcion" class="quill-content text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-200/80"></div>
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