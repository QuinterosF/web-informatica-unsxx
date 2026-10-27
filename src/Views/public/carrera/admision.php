<?php
/**
 * Vista: Admisión y Postulantes (src/Views/public/carrera/admision.php)
 * Variables inyectadas por HomeController::admision():
 * @var array $admissions Registros activos de la tabla `admision` (prueba y preuni)
 * @var array $documents  Documentos PDF con categoria = 'reglamento_admision'
 */

$convocatorias = $admissions ?? [];
$reglamentos   = $documents ?? [];

// Obtenemos el año de gestión más reciente entre las convocatorias activas (o el año actual por defecto)
$gestionActual = (int) date('Y');
if (!empty($convocatorias[0]['gestion'])) {
    $gestionActual = (int) $convocatorias[0]['gestion'];
}

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
 * Verifica que el afiche exista en /uploads/img/admission/
 */
if (!function_exists('obtenerUrlAficheAdmision')) {
    function obtenerUrlAficheAdmision(?string $archivo): ?string {
        $limpio = trim((string) $archivo);
        if ($limpio === '' || strtolower($limpio) === 'default.png') {
            return null;
        }
        return '/uploads/img/admission/' . rawurlencode($limpio);
    }
}

/**
 * Las 4 Modalidades de Admisión Oficiales (Tríptico Institucional)
 */
$modalidadesIngreso = [
    [
        'numero'      => '01',
        'titulo'      => 'Prueba de Admisión',
        'icono'       => 'fas fa-file-signature',
        'badge'       => 'Evaluación Directa',
        'descripcion' => 'Evaluación académica y diagnóstico psicométrico sobre conocimientos del nivel secundario para el ingreso directo a primer año.',
        'card_cls'    => 'tech-card-spotlight',
        'bar_cls'     => 'from-institucional-gold via-yellow-400 to-institucional-navy'
    ],
    [
        'numero'      => '02',
        'titulo'      => 'Curso Preuniversitario',
        'icono'       => 'fas fa-chalkboard-user',
        'badge'       => 'Nivelación Académica',
        'descripcion' => 'Modalidad preparatoria de nivelación en asignaturas básicas de matemáticas, programación y formación político-sindical.',
        'card_cls'    => 'tech-card',
        'bar_cls'     => 'from-institucional-navy via-blue-600 to-institucional-gold'
    ],
    [
        'numero'      => '03',
        'titulo'      => 'Ingreso Directo (Mejor Alumno)',
        'icono'       => 'fas fa-medal',
        'badge'       => 'Excelencia Secundaria',
        'descripcion' => 'Admisión directa sin examen para los bachilleres destacados con el mejor promedio de su establecimiento educativo.',
        'card_cls'    => 'tech-card',
        'bar_cls'     => 'from-institucional-navy via-blue-600 to-institucional-gold'
    ],
    [
        'numero'      => '04',
        'titulo'      => 'Admisión Especial (Convenio)',
        'icono'       => 'fas fa-handshake',
        'badge'       => 'Convenios UNSXX',
        'descripcion' => 'Ingreso mediante convenios interinstitucionales, sectores sociales, organizaciones sindicales y profesionales titulados.',
        'card_cls'    => 'tech-card',
        'bar_cls'     => 'from-institucional-navy via-blue-600 to-institucional-gold'
    ],
];

/**
 * Becas y Beneficios Universitarios (Tríptico Institucional)
 */
$becasUniversitarias = [
    [
        'nombre'      => 'Beca Auxiliar de Docencia',
        'icono'       => 'fas fa-laptop-code',
        'categoria'   => 'Mérito Académico',
        'descripcion' => 'Apoyo económico mensual para estudiantes destacados que colaboran en la enseñanza práctica de asignaturas y laboratorios.'
    ],
    [
        'nombre'      => 'Beca Investigación',
        'icono'       => 'fas fa-microscope',
        'categoria'   => 'Científica · IIDAI',
        'descripcion' => 'Incentivo para estudiantes que desarrollan proyectos científicos y tecnológicos dentro del Instituto de Investigación IIDAI.'
    ],
    [
        'nombre'      => 'Beca Trabajo Tesista',
        'icono'       => 'fas fa-graduation-cap',
        'categoria'   => 'Culminación de Estudios',
        'descripcion' => 'Beneficio dirigido a egresados que realizan su proyecto de grado, tesis o trabajo dirigido institucional.'
    ],
    [
        'nombre'      => 'Beca Comedor',
        'icono'       => 'fas fa-utensils',
        'categoria'   => 'Bienestar Estudiantil',
        'descripcion' => 'Servicio de alimentación diaria otorgado por la Universidad Nacional "Siglo XX" para apoyar la permanencia estudiantil.'
    ],
    [
        'nombre'      => 'Beca Albergue',
        'icono'       => 'fas fa-house-user',
        'categoria'   => 'Residencia Universitaria',
        'descripcion' => 'Alojamiento estudiantil destinado a universitarios provenientes de otras provincias y departamentos del país.'
    ],
];
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
                    <span class="font-code text-xs font-medium text-institucional-gold uppercase tracking-wider">&lt;/&gt; admisión y postulantes</span>
                </div>

                <h1 class="font-display text-3xl sm:text-5xl font-bold tracking-tight leading-[1.13] text-white">
                    Admisión <span class="text-institucional-gold"><?= $gestionActual ?></span>
                </h1>
                
                <p class="mt-4 text-base text-slate-200 font-light leading-relaxed">
                    Inicia tu formación profesional en una Carrera Acreditada al CEUB. Conoce los procesos vigentes, requisitos de inscripción, modalidades de ingreso y becas estudiantiles.
                </p>
            </div>

            <!-- Indicador Estadístico Compacto -->
            <div class="relative self-start lg:self-center w-full sm:w-auto">
                <div class="bg-[#0f1f4d]/95 border border-institucional-gold/40 rounded-xl p-5 flex items-center gap-5 backdrop-blur-xl shadow-2xl">
                    <div class="w-13 h-13 p-3.5 rounded-xl bg-institucional-gold text-slate-900 flex items-center justify-center text-xl flex-shrink-0 shadow-[0_0_20px_rgba(234,179,8,0.4)]">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="pr-2">
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="font-display font-extrabold text-2xl sm:text-3xl text-white"><?= count($convocatorias) ?></span>
                            <span class="text-s text-slate-200 font-medium">Convocatorias</span>
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

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-16">

        <!-- =================================================================
             BLOQUE 1: PROCESOS DE ADMISIÓN VIGENTES (DINÁMICO DE LA TABLA `admision`)
             ================================================================= -->
        <div class="space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-200/90 pb-4">
                <div>
                    <span class="font-code text-xs font-medium uppercase tracking-wider text-institucional-navy bg-blue-50 px-3 py-1 rounded-full border border-blue-200/80">
                        &lt;/&gt; Convocatorias Oficiales
                    </span>
                    <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900 mt-2.5">
                        Procesos de Admisión Habilitados
                    </h2>
                </div>
                <p class="text-sm text-slate-600 max-w-md">
                    Revisa las características, requisitos de postulación y afiches oficiales de cada modalidad vigente.
                </p>
            </div>

            <?php if (!empty($convocatorias)): ?>
                <div class="space-y-10">
                    <?php foreach ($convocatorias as $conv): ?>
                        <?php
                            $idAdm        = (int) ($conv['id_adm'] ?? 0);
                            $tipo         = strtolower(trim((string) ($conv['tipo'] ?? 'prueba')));
                            $gestion      = (int) ($conv['gestion'] ?? $gestionActual);
                            $esPrueba     = ($tipo === 'prueba');

                            $nombreProceso = $esPrueba
                                ? 'Prueba de Admisión Académica'
                                : 'Curso Preuniversitario';
                            $etiquetaTipo  = $esPrueba
                                ? 'Examen Directo'
                                : 'Curso de Nivelación y Preparación';
                            $iconoProceso  = $esPrueba
                                ? 'fas fa-file-signature'
                                : 'fas fa-chalkboard-user';

                            $procHtml     = formatearTextoQuill($conv['proc_adm'] ?? '');
                            $reqHtml      = formatearTextoQuill($conv['req_adm'] ?? '');
                            $urlAfiche    = obtenerUrlAficheAdmision($conv['img_adm'] ?? null);
                        ?>

                        <article id="admision-<?= $idAdm ?>" class="code-window-panel overflow-hidden">
                            <!-- Barra Superior Estilo Consola -->
                            <div class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-3.5 border-b border-institucional-gold/40 flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span>
                                        <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold inline-block"></span>
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                                    </div>
                                    <span class="font-code text-[13px] text-institucional-gold font-medium pl-2 border-l border-white/20">
                                        &lt;/&gt; <?= htmlspecialchars($nombreProceso) ?> · Gestión <?= $gestion ?>
                                    </span>
                                </div>

                                <span class="font-code text-xs text-slate-200 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    Convocatoria Vigente <?= $gestion ?>
                                </span>
                            </div>

                            <!-- Cuerpo del Proceso: Izquierda (Proceso y Requisitos) + Derecha (Afiche Oficial) -->
                            <div class="p-6 sm:p-8 lg:p-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                                
                                <!-- Columna de Información (7 cols si hay afiche, 12 si no hay) -->
                                <div class="<?= $urlAfiche !== null ? 'lg:col-span-7' : 'lg:col-span-12' ?> space-y-6">
                                    
                                    <!-- Encabezado de la Convocatoria -->
                                    <div class="flex items-start gap-4">
                                        <div class="w-13 h-13 p-3.5 rounded-2xl bg-institucional-navy text-institucional-gold flex items-center justify-center text-xl shadow-md flex-shrink-0">
                                            <i class="<?= $iconoProceso ?>"></i>
                                        </div>
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                                <span class="font-code px-3 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-900 border border-amber-300">
                                                    Gestión <?= $gestion ?>
                                                </span>
                                                <span class="font-code px-3 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-institucional-navy border border-blue-200">
                                                    <?= htmlspecialchars($etiquetaTipo) ?>
                                                </span>
                                            </div>
                                            <h3 class="font-display text-2xl sm:text-3xl font-bold text-slate-900">
                                                <?= htmlspecialchars($nombreProceso) ?>
                                            </h3>
                                        </div>
                                    </div>

                                    <!-- Tarjeta 1: Descripción del Proceso (`proc_adm`) -->
                                    <?php if ($procHtml !== ''): ?>
                                        <div class="tech-card p-6 relative overflow-hidden">
                                            <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-institucional-navy via-blue-600 to-institucional-gold"></div>
                                            <h4 class="font-code text-xs uppercase tracking-wider text-institucional-navy font-bold mb-3 flex items-center gap-2">
                                                <i class="fas fa-circle-info text-institucional-gold"></i> Acerca de esta Modalidad
                                            </h4>
                                            <div class="quill-content text-sm sm:text-base text-slate-700 leading-relaxed">
                                                <?= $procHtml ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Tarjeta 2: Requisitos de Inscripción (`req_adm`) -->
                                    <?php if ($reqHtml !== ''): ?>
                                        <div class="tech-card-spotlight p-6 relative overflow-hidden">
                                            <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-institucional-gold via-yellow-400 to-institucional-navy"></div>
                                            <h4 class="font-code text-xs uppercase tracking-wider text-institucional-navy font-bold mb-3 flex items-center gap-2">
                                                <i class="fas fa-clipboard-check text-institucional-navy"></i> Requisitos de Postulación
                                            </h4>
                                            <div class="quill-content text-sm sm:text-base text-slate-700 leading-relaxed bg-white/85 p-4 rounded-xl border border-blue-200/70">
                                                <?= $reqHtml ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Barra rápida de consulta por WhatsApp / Contacto -->
                                    <div class="pt-2 flex flex-wrap items-center justify-between gap-3">
                                        <span class="text-xs text-slate-500 flex items-center gap-1.5">
                                            <i class="fas fa-building-columns text-institucional-navy"></i>
                                            Inscripciones en el Departamento de Registros e Inscripciones · UNSXX
                                        </span>
                                        <a href="/contacto" class="inline-flex items-center gap-1.5 text-xs font-bold text-institucional-navy hover:text-yellow-600 transition-colors">
                                            <span>Consultar teléfonos y ubicación</span>
                                            <i class="fas fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </div>
                                </div>

                                <!-- Columna del Afiche Oficial (5 cols) -->
                                <?php if ($urlAfiche !== null): ?>
                                    <div class="lg:col-span-5">
                                        <div class="tech-card p-4 relative overflow-hidden group">
                                            <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-institucional-gold via-yellow-400 to-institucional-navy"></div>
                                            
                                            <div class="relative rounded-xl overflow-hidden bg-slate-950 border border-slate-200">
                                                <img src="<?= htmlspecialchars($urlAfiche) ?>" 
                                                     alt="Afiche <?= htmlspecialchars($nombreProceso) ?>" 
                                                     loading="lazy"
                                                     class="w-full h-auto max-h-[480px] object-contain mx-auto group-hover:scale-[1.02] transition-transform duration-300 cursor-pointer btn-ampliar-afiche-admision"
                                                     data-foto="<?= htmlspecialchars($urlAfiche) ?>"
                                                     data-titulo="<?= htmlspecialchars($nombreProceso . ' · Gestión ' . $gestion) ?>"
                                                     onerror="this.closest('.lg\\:col-span-5').classList.add('hidden');">
                                            </div>

                                            <div class="mt-3 flex items-center justify-between gap-2 pt-2">
                                                <span class="font-code text-[11px] text-slate-500">
                                                    <i class="far fa-image text-institucional-navy mr-1"></i> Afiche Oficial
                                                </span>
                                                <div class="flex items-center gap-2">
                                                    <button type="button"
                                                            class="btn-ampliar-afiche-admision inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-institucional-navy hover:bg-institucional-navy-dark text-white text-xs font-medium transition-all shadow-sm"
                                                            data-foto="<?= htmlspecialchars($urlAfiche) ?>"
                                                            data-titulo="<?= htmlspecialchars($nombreProceso . ' · Gestión ' . $gestion) ?>">
                                                        <i class="fas fa-expand text-institucional-gold text-xs"></i>
                                                        <span>Ampliar afiche</span>
                                                    </button>
                                                    <a href="<?= htmlspecialchars($urlAfiche) ?>"
                                                       download="Afiche_<?= htmlspecialchars(str_replace(' ', '_', $nombreProceso)) ?>_<?= $gestion ?>.jpg"
                                                       target="_blank"
                                                       rel="noopener noreferrer"
                                                       class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs transition-colors"
                                                       title="Descargar afiche">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="code-window-panel p-10 text-center text-slate-500">
                    Las convocatorias de admisión para la presente gestión se encuentran en actualización.
                </div>
            <?php endif; ?>
        </div>


        <!-- =================================================================
             BLOQUE 2: LAS 4 MODALIDADES DE INGRESO (ESTÁTICO DEL TRÍPTICO)
             ================================================================= -->
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-200/90 pb-4">
                <div>
                    <span class="font-code text-xs font-medium uppercase tracking-wider text-institucional-navy bg-blue-50 px-3 py-1 rounded-full border border-blue-200/80">
                        &lt;/&gt; Vías de Ingreso a la Carrera
                    </span>
                    <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900 mt-2.5">
                        Modalidades de Admisión
                    </h2>
                </div>
                <p class="text-sm text-slate-600 max-w-md">
                    La Universidad Nacional "Siglo XX" y la Carrera de Ingeniería Informática contemplan cuatro vías oficiales de admisión estudiantil.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($modalidadesIngreso as $modIng): ?>
                    <article class="<?= $modIng['card_cls'] ?> p-6 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r <?= $modIng['bar_cls'] ?>"></div>

                        <div>
                            <div class="flex items-center justify-between mb-5">
                                <div class="w-11 h-11 rounded-xl bg-institucional-navy text-institucional-gold flex items-center justify-center text-lg shadow-md group-hover:scale-105 transition-transform">
                                    <i class="<?= $modIng['icono'] ?>"></i>
                                </div>
                                <span class="font-code text-xs font-bold text-slate-400">
                                    ING // <?= htmlspecialchars($modIng['numero']) ?>
                                </span>
                            </div>

                            <span class="font-code inline-block px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-blue-50 text-institucional-navy border border-blue-200 mb-2">
                                <?= htmlspecialchars($modIng['badge']) ?>
                            </span>

                            <h3 class="font-display text-lg font-bold text-slate-900 mb-2">
                                <?= htmlspecialchars($modIng['titulo']) ?>
                            </h3>

                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                <?= htmlspecialchars($modIng['descripcion']) ?>
                            </p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>


        <!-- =================================================================
             BLOQUE 3: BECAS Y BENEFICIOS ESTUDIANTILES (ESTÁTICO DEL TRÍPTICO)
             ================================================================= -->
        <div class="code-window-panel overflow-hidden">
            <div class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-3.5 border-b border-institucional-gold/40 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                    </div>
                    <span class="font-code text-[13px] text-institucional-gold font-medium pl-2 border-l border-white/20">
                        &lt;/&gt; Becas y Beneficios Universitarios
                    </span>
                </div>
                <span class="font-code text-xs text-slate-200 flex items-center gap-2">
                    <i class="fas fa-hand-holding-heart text-institucional-gold"></i> Apoyo al Estudiante · UNSXX
                </span>
            </div>

            <div class="p-6 sm:p-8 lg:p-10">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                    <div>
                        <span class="font-code text-xs font-medium uppercase tracking-wider text-amber-900 bg-amber-50 px-3 py-1 rounded-full border border-amber-300">
                            Bienestar e Incentivo Académico
                        </span>
                        <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900 mt-2.5">
                            Sistema de Becas Universitarias
                        </h2>
                    </div>
                    <p class="text-sm text-slate-600 max-w-md">
                        Como estudiante regular de Ingeniería Informática puedes postular a cinco becas académicas, investigativas y sociales.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-5">
                    <?php foreach ($becasUniversitarias as $idxBeca => $beca): ?>
                        <?php $colBeca = $idxBeca < 3 ? 'lg:col-span-2' : 'lg:col-span-3'; ?>
                        <div class="tech-card <?= $colBeca ?> p-6 flex flex-col justify-between relative overflow-hidden group">
                            <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-institucional-navy via-blue-600 to-institucional-gold"></div>
                            <div>
                                <div class="flex items-center justify-between gap-3 mb-4">
                                    <div class="w-11 h-11 rounded-xl bg-institucional-navy/10 text-institucional-navy group-hover:bg-institucional-navy group-hover:text-institucional-gold flex items-center justify-center text-lg transition-colors">
                                        <i class="<?= $beca['icono'] ?>"></i>
                                    </div>
                                    <span class="font-code px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                        <?= htmlspecialchars($beca['categoria']) ?>
                                    </span>
                                </div>
                                <h3 class="font-display text-lg font-bold text-slate-900 mb-2">
                                    <?= htmlspecialchars($beca['nombre']) ?>
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                    <?= htmlspecialchars($beca['descripcion']) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>


        <!-- =================================================================
             BLOQUE 4: REGLAMENTO OFICIAL DE ADMISIÓN EN PDF (`reglamento_admision`)
             ================================================================= -->
        <?php if (!empty($reglamentos)): ?>
            <div class="code-window-panel overflow-hidden">
                <div class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-3.5 border-b border-institucional-gold/40 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold inline-block"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                        </div>
                        <span class="font-code text-[13px] text-institucional-gold font-medium pl-2 border-l border-white/20">
                            &lt;/&gt; Normativa y Reglamento de Admisión (PDF)
                        </span>
                    </div>
                    <span class="font-code text-xs text-slate-200 flex items-center gap-2">
                        <i class="fas fa-file-pdf text-institucional-gold"></i> Documento Oficial
                    </span>
                </div>

                <div class="p-6 sm:p-8 lg:p-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($reglamentos as $doc): ?>
                            <?php
                                $titulo     = trim((string) ($doc['titulo'] ?? 'Reglamento de Admisión'));
                                $gestionDoc = !empty($doc['gestion']) ? trim((string) $doc['gestion']) : null;
                                $archivoPdf = trim((string) ($doc['archivo_pdf'] ?? ''));
                                $urlPdf     = '/uploads/pdf/documents/' . rawurlencode($archivoPdf);
                            ?>

                            <article class="tech-card flex flex-col justify-between relative overflow-hidden group">
                                <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-institucional-gold via-yellow-400 to-institucional-navy"></div>

                                <div class="p-6 flex items-start gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-institucional-navy text-institucional-gold group-hover:bg-institucional-gold group-hover:text-slate-900 flex items-center justify-center text-2xl flex-shrink-0 shadow-md transition-colors">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <span class="font-code inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-blue-50 text-institucional-navy border border-blue-200">
                                                <i class="fas fa-scale-balanced text-[10px]"></i> Reglamento de Admisión
                                            </span>
                                            <?php if ($gestionDoc !== null): ?>
                                                <span class="font-code px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                    Gestión <?= htmlspecialchars($gestionDoc) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <h3 class="font-display text-lg sm:text-xl font-bold text-slate-900 leading-snug group-hover:text-institucional-navy transition-colors">
                                            <?= htmlspecialchars($titulo) ?>
                                        </h3>
                                    </div>
                                </div>

                                <div class="px-6 py-3.5 bg-slate-50/90 border-t border-slate-200/80 flex flex-wrap items-center justify-between gap-2">
                                    <span class="font-code text-[11px] text-slate-500 flex items-center gap-1.5">
                                        <i class="far fa-file-lines text-institucional-navy"></i> Formato PDF Oficial
                                    </span>

                                    <div class="flex items-center gap-2 ml-auto">
                                        <button type="button"
                                                class="btn-previsualizar-pdf inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white hover:bg-slate-100 text-institucional-navy border border-slate-300 text-xs font-semibold transition-all shadow-sm"
                                                data-pdf="<?= htmlspecialchars($urlPdf) ?>"
                                                data-titulo="<?= htmlspecialchars($titulo) ?>">
                                            <i class="fas fa-eye text-xs"></i>
                                            <span>Ver PDF</span>
                                        </button>

                                        <a href="<?= htmlspecialchars($urlPdf) ?>" 
                                           download="<?= htmlspecialchars($titulo . '.pdf') ?>"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-institucional-navy hover:bg-institucional-navy-dark text-white text-xs font-medium transition-all shadow-sm">
                                            <i class="fas fa-download text-institucional-gold text-xs"></i>
                                            <span>Descargar</span>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- 1. MODAL LIGHTBOX PARA AMPLIAR AFICHE DE ADMISIÓN -->
<div id="modal-afiche-admision" class="fixed inset-0 z-50 hidden bg-slate-900/85 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-200">
    <div id="modal-afiche-admision-card" class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-3xl w-full overflow-hidden transform scale-95 transition-transform duration-200">
        <div class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-3.5 border-b-2 border-institucional-gold flex items-center justify-between">
            <span id="modal-afiche-titulo" class="font-code text-xs text-institucional-gold font-medium uppercase tracking-wider"></span>
            <button type="button" id="btn-cerrar-modal-afiche" class="text-slate-300 hover:text-institucional-gold transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="p-4 sm:p-6 bg-slate-950 text-center">
            <img id="modal-afiche-img" src="" alt="Afiche de Admisión" class="max-h-[75vh] w-auto mx-auto rounded-xl object-contain">
        </div>
    </div>
</div>

<!-- 2. MODAL VISOR DE PDF INTEGRADO -->
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
            <iframe id="iframe-visor-pdf" src="" title="Visor PDF Reglamento de Admisión" class="w-full h-full border-0"></iframe>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Lightbox para ampliar afiche de Admisión
        const modalAfiche = document.getElementById('modal-afiche-admision');
        const cardAfiche = document.getElementById('modal-afiche-admision-card');
        const imgAfiche = document.getElementById('modal-afiche-img');
        const tituloAfiche = document.getElementById('modal-afiche-titulo');
        const btnCerrarAfiche = document.getElementById('btn-cerrar-modal-afiche');

        document.querySelectorAll('.btn-ampliar-afiche-admision').forEach((btn) => {
            btn.addEventListener('click', () => {
                imgAfiche.src = btn.getAttribute('data-foto') || '';
                tituloAfiche.textContent = '</> ' + (btn.getAttribute('data-titulo') || 'Convocatoria de Admisión');

                modalAfiche.classList.remove('hidden');
                void modalAfiche.offsetWidth;
                modalAfiche.classList.remove('opacity-0');
                cardAfiche.classList.remove('scale-95');
            });
        });

        const cerrarAfiche = () => {
            modalAfiche.classList.add('opacity-0');
            cardAfiche.classList.add('scale-95');
            setTimeout(() => {
                modalAfiche.classList.add('hidden');
                imgAfiche.src = '';
            }, 200);
        };

        btnCerrarAfiche?.addEventListener('click', cerrarAfiche);
        modalAfiche?.addEventListener('click', (e) => {
            if (e.target === modalAfiche) cerrarAfiche();
        });

        // 2. Modal Visor PDF
        const modalPdf = document.getElementById('modal-visor-pdf');
        const cardPdf = document.getElementById('modal-visor-pdf-card');
        const iframePdf = document.getElementById('iframe-visor-pdf');
        const tituloPdf = document.getElementById('modal-pdf-titulo');
        const linkPestana = document.getElementById('modal-pdf-nueva-pestana');
        const btnCerrarPdf = document.getElementById('btn-cerrar-visor-pdf');

        document.querySelectorAll('.btn-previsualizar-pdf').forEach((btn) => {
            btn.addEventListener('click', () => {
                const url = btn.getAttribute('data-pdf') || '';
                const titulo = btn.getAttribute('data-titulo') || 'Reglamento de Admisión';

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