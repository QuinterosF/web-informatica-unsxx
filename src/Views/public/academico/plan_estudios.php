<?php
/**
 * Vista: Plan de Estudios (src/Views/public/academico/plan_estudios.php)
 * Variables inyectadas por HomeController::planEstudios():
 * @var array $documents Documentos oficiales con categoria = 'plan_estudios'
 */

$documentosPlan = $documents ?? [];

/**
 * Niveles de Formación Oficiales (Tríptico Institucional)
 */
$nivelesFormacion = [
    [
        'tiempo'  => '2 Años',
        'grado'   => 'Técnico Universitario Medio',
        'diploma' => 'Técnico Universitario Medio en Programación de Sistemas Informáticos',
        'titulo'  => 'Técnico Universitario Medio en Programación de Sistemas Informáticos',
        'icono'   => 'fas fa-code',
        'badge'   => 'bg-slate-100 text-slate-800 border-slate-300',
        'bar'     => 'from-institucional-navy via-blue-600 to-institucional-navy',
        'card'    => 'tech-card'
    ],
    [
        'tiempo'  => '3 Años',
        'grado'   => 'Técnico Universitario Superior',
        'diploma' => 'Técnico Universitario Superior en Sistemas Informáticos',
        'titulo'  => 'Técnico Universitario Superior en Sistemas Informáticos',
        'icono'   => 'fas fa-network-wired',
        'badge'   => 'bg-blue-50 text-institucional-navy border-blue-200',
        'bar'     => 'from-institucional-navy via-blue-600 to-institucional-gold',
        'card'    => 'tech-card'
    ],
    [
        'tiempo'  => '5 Años',
        'grado'   => 'Licenciatura',
        'diploma' => 'Licenciatura en Ing. Informática',
        'titulo'  => 'Ingeniero Informático con mención: Seguridad Informática / Computación Móvil',
        'icono'   => 'fas fa-user-graduate',
        'badge'   => 'bg-amber-50 text-amber-900 border-amber-300',
        'bar'     => 'from-institucional-gold via-yellow-400 to-institucional-navy',
        'card'    => 'tech-card-spotlight'
    ],
];

/**
 * Mapa Curricular Estático de 1ro a 5to Año (Régimen Anual)
 * Separando Asignaturas Troncales y Asignaturas de Mención (3er y 4to año)
 */
$mapaCurricular = [
    1 => [
        'anio'        => 'Primer Año',
        'etiqueta'    => 'Nivel 01 · Fundamentos y Ciencias Exactas',
        'descripcion' => 'Formación matemática, física, lógica algorítmica, arquitectura de hardware y bases de investigación.',
        'hito'        => null,
        'troncales'   => [
            'Álgebra Superior y Lineal',
            'Análisis Matemático I',
            'Física I',
            'Metodología de la Programación',
            'Taller de Programación',
            'Arquitectura de Computadoras y Dispositivos Móviles',
            'Contabilidad Básica y Administración General',
            'Metodología de la Investigación',
            'Inglés Técnico',
            'Filosofía',
            'Historia del Movimiento Obrero I',
        ],
        'menciones'   => []
    ],
    2 => [
        'anio'        => 'Segundo Año',
        'etiqueta'    => 'Nivel 02 · Ingeniería de Datos y Desarrollo de Software',
        'descripcion' => 'Estructuras de datos, bases de datos, sistemas operativos, análisis de sistemas y desarrollo web.',
        'hito'        => 'Certificación Intermedia: Técnico Universitario Medio en Programación de Sistemas Informáticos',
        'troncales'   => [
            'Análisis Matemático II',
            'Física II',
            'Estadística y Probabilidad',
            'Desarrollo Web',
            'Taller de Desarrollo de Software',
            'Estructura de Datos',
            'Diseño y Administración de Base de Datos',
            'Análisis y Diseño de Sistemas',
            'Sistemas Operativos',
            'Economía Política',
            'Historia del Movimiento Obrero II',
        ],
        'menciones'   => []
    ],
    3 => [
        'anio'        => 'Tercer Año',
        'etiqueta'    => 'Nivel 03 · Redes, Robótica e Inicio de Menciones',
        'descripcion' => 'Infraestructura de redes, telecomunicaciones, simulación y apertura de las dos rutas de especialidad.',
        'hito'        => 'Certificación Intermedia: Técnico Universitario Superior en Sistemas Informáticos',
        'troncales'   => [
            'Redes I',
            'Tecnología y Programación en Robótica Móvil',
            'Telecomunicaciones',
            'Tecnologías Emergentes',
            'Simulación de Sistemas',
            'Investigación Operativa',
            'Legislación Social Boliviana',
            'Formación Social Boliviana',
        ],
        'menciones'   => [
            'seguridad' => [
                'nombre'   => 'Mención: Seguridad Informática',
                'icono'    => 'fas fa-shield-halved',
                'materias' => [
                    'Servidores y Seguridad en Redes',
                    'Ciberseguridad y Análisis de Vulnerabilidades',
                    'Seguridad en Sistemas de Información y Base de Datos',
                    'Criptografía y Esteganografía',
                ]
            ],
            'movil' => [
                'nombre'   => 'Mención: Computación Móvil',
                'icono'    => 'fas fa-mobile-screen-button',
                'materias' => [
                    'Programación de Dispositivos Móviles',
                    'Desarrollo de Videojuegos para Dispositivos Móviles',
                    'Redes Móviles y Cloud Computing',
                    'Desarrollo de Aplicaciones Móviles para Geolocalización',
                ]
            ]
        ]
    ],
    4 => [
        'anio'        => 'Cuarto Año',
        'etiqueta'    => 'Nivel 04 · Sistemas Inteligentes y Alta Especialización',
        'descripcion' => 'Inteligencia artificial, ingeniería de software avanzada y profundización técnica según la mención elegida.',
        'hito'        => null,
        'troncales'   => [
            'Inteligencia Artificial',
            'Ingeniería de Software',
            'Ingeniería de Sistemas',
            'Programación Gráfica',
            'Electiva I',
            'Política Económica Nacional',
            'Política Nacional',
        ],
        'menciones'   => [
            'seguridad' => [
                'nombre'   => 'Mención: Seguridad Informática',
                'icono'    => 'fas fa-user-secret',
                'materias' => [
                    'Inteligencia de Amenazas y Ethical Hacking',
                    'Informática Forense',
                    'Auditoría Informática',
                    'Sistemas de Gestión de Seguridad de Tecnologías de la Información',
                ]
            ],
            'movil' => [
                'nombre'   => 'Mención: Computación Móvil',
                'icono'    => 'fas fa-vr-cardboard',
                'materias' => [
                    'Realidad Virtual y Aumentada',
                    'Visión Artificial para Dispositivos Móviles',
                    'Taller de Desarrollo de Aplicaciones para Dispositivos Móviles',
                    'Internet de las Cosas',
                ]
            ]
        ]
    ],
    5 => [
        'anio'        => 'Quinto Año',
        'etiqueta'    => 'Nivel 05 · Graduación, Práctica Profesional y Gestión de Proyectos',
        'descripcion' => 'Investigación de grado, inserción laboral mediante prácticas profesionales y alta gerencia tecnológica.',
        'hito'        => 'Culminación: Licenciatura en Ingeniería Informática',
        'troncales'   => [
            'Taller de Graduación',
            'Prácticas Profesionales',
            'Emprendimiento, Preparación, Evaluación y Gestión de Proyectos y Alta Gerencia',
            'Electiva II',
            'Dirección Política',
            'Tesis Política',
        ],
        'menciones'   => []
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
                    <span class="font-code text-xs font-medium text-institucional-gold uppercase tracking-wider">&lt;/&gt; plan de estudios</span>
                </div>

                <h1 class="font-display text-3xl sm:text-5xl font-bold tracking-tight leading-[1.13] text-white">
                    Plan de <span class="text-institucional-gold">Estudios</span>
                </h1>
                
                <p class="mt-4 text-base text-slate-200 font-light leading-relaxed">
                    Estructura curricular bajo régimen anual de 5 años con titulaciones intermedias y especialización en Seguridad Informática y Computación Móvil.
                </p>
            </div>

            <!-- Indicador Estadístico Compacto -->
            <div class="relative self-start lg:self-center w-full sm:w-auto">
                <div class="bg-[#0f1f4d]/95 border border-institucional-gold/40 rounded-xl p-5 flex items-center gap-5 backdrop-blur-xl shadow-2xl">
                    <div class="w-13 h-13 p-3.5 rounded-xl bg-institucional-gold text-slate-900 flex items-center justify-center text-xl flex-shrink-0 shadow-[0_0_20px_rgba(234,179,8,0.4)]">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div class="pr-2">
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="font-display font-extrabold text-2xl sm:text-3xl text-white">5 Años</span>
                            <span class="text-s text-slate-200 font-medium">de formación profesional</span>
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
             BLOQUE 1: NIVELES DE FORMACIÓN Y GRADOS ACADÉMICOS (2, 3 Y 5 AÑOS)
             ================================================================= -->
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-200/90 pb-4">
                <div>
                    <span class="font-code text-xs font-medium uppercase tracking-wider text-institucional-navy bg-blue-50 px-3 py-1 rounded-full border border-blue-200/80">
                        &lt;/&gt; Certificación Progresiva
                    </span>
                    <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900 mt-2.5">
                        Niveles de Formación y Grados Académicos
                    </h2>
                </div>
                <p class="text-sm text-slate-600 max-w-md">
                    El plan curricular otorga grados académicos de Técnico Medio al 2do año, Técnico Superior al 3er año y Licenciatura al 5to año.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($nivelesFormacion as $niv): ?>
                    <article class="<?= $niv['card'] ?> p-6 flex flex-col justify-between relative overflow-hidden">
                        <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r <?= $niv['bar'] ?>"></div>

                        <div>
                            <div class="flex items-center justify-between mb-5">
                                <div class="w-11 h-11 rounded-xl bg-institucional-navy text-institucional-gold flex items-center justify-center text-lg shadow-md">
                                    <i class="<?= $niv['icono'] ?>"></i>
                                </div>
                                <span class="font-code px-3.5 py-1 rounded-full text-xs font-semibold border <?= $niv['badge'] ?>">
                                    Tiempo: <?= htmlspecialchars($niv['tiempo']) ?>
                                </span>
                            </div>

                            <span class="font-code text-[11px] uppercase tracking-wider text-slate-400 font-semibold block">Grado Académico</span>
                            <h3 class="font-display text-xl font-bold text-slate-900 mb-4">
                                <?= htmlspecialchars($niv['grado']) ?>
                            </h3>
                        </div>

                        <div class="bg-slate-900/[0.03] rounded-xl p-4 border border-slate-200/90 space-y-3 text-xs">
                            <div>
                                <span class="font-code uppercase tracking-wider text-[10px] text-institucional-navy font-bold block mb-0.5">Diploma Académico</span>
                                <p class="text-slate-700 font-medium leading-snug"><?= htmlspecialchars($niv['diploma']) ?></p>
                            </div>
                            <div class="pt-2.5 border-t border-slate-200/80">
                                <span class="font-code uppercase tracking-wider text-[10px] text-yellow-700 font-bold block mb-0.5">Título en Provisión Nacional</span>
                                <p class="text-slate-800 font-semibold leading-snug"><?= htmlspecialchars($niv['titulo']) ?></p>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>


        <!-- =================================================================
             BLOQUE 2: MAPA Y RUTA CURRICULAR POR AÑO (1RO A 5TO AÑO)
             ================================================================= -->
        <div class="code-window-panel overflow-hidden">
            <!-- Barra Superior Estilo Consola + Selector Interactivo de Ruta -->
            <div class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-4 border-b border-institucional-gold/40 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                    </div>
                    <span class="font-code text-[13px] text-institucional-gold font-medium pl-2 border-l border-white/20">
                        &lt;/&gt; Mapa Curricular de Formación (1ro a 5to Año)
                    </span>
                </div>

                <!-- Botones para resaltar las rutas de mención en 3er y 4to año -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="font-code text-[11px] text-slate-300 mr-1">Enfocar ruta:</span>
                    <button type="button" data-ruta="ambas" class="btn-ruta-mencion px-3.5 py-1 rounded-full text-xs font-semibold bg-institucional-gold text-slate-900 transition-all shadow-sm">
                        Ambas Menciones
                    </button>
                    <button type="button" data-ruta="seguridad" class="btn-ruta-mencion px-3.5 py-1 rounded-full text-xs font-medium bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-all">
                        <i class="fas fa-shield-halved text-institucional-gold mr-1"></i> Seguridad Informática
                    </button>
                    <button type="button" data-ruta="movil" class="btn-ruta-mencion px-3.5 py-1 rounded-full text-xs font-medium bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-all">
                        <i class="fas fa-mobile-screen-button text-institucional-gold mr-1"></i> Computación Móvil
                    </button>
                </div>
            </div>

            <div class="p-6 sm:p-8 lg:p-10">
                <!-- Línea de Ruta Vertical -->
                <div class="relative space-y-10">
                    <!-- Conector vertical de la ruta -->
                    <div class="hidden md:block absolute left-7 top-8 bottom-8 w-1 bg-gradient-to-b from-institucional-navy via-institucional-gold to-institucional-navy rounded-full"></div>

                    <?php foreach ($mapaCurricular as $numAnio => $datosAnio): ?>
                        <?php $tieneMenciones = !empty($datosAnio['menciones']); ?>

                        <div class="relative md:pl-20">
                            <!-- Nodo Numerado del Año (1° al 5°) -->
                            <div class="hidden md:flex absolute left-0 top-0 w-14 h-14 rounded-2xl bg-institucional-navy border-2 border-institucional-gold text-institucional-gold flex-col items-center justify-center shadow-lg z-10">
                                <span class="font-code text-[10px] uppercase leading-none opacity-80">Año</span>
                                <span class="font-display text-xl font-extrabold leading-tight">0<?= $numAnio ?></span>
                            </div>

                            <!-- Contenedor del Año -->
                            <div class="tech-card p-6 sm:p-7 relative overflow-hidden">
                                <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r <?= $tieneMenciones ? 'from-institucional-gold via-yellow-400 to-institucional-navy' : 'from-institucional-navy via-blue-600 to-institucional-gold' ?>"></div>

                                <!-- Cabecera del Año -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-slate-200/90">
                                    <div>
                                        <span class="font-code text-xs font-semibold text-institucional-navy uppercase tracking-wider block">
                                            <?= htmlspecialchars($datosAnio['etiqueta']) ?>
                                        </span>
                                        <h3 class="font-display text-2xl font-bold text-slate-900 mt-0.5">
                                            <?= htmlspecialchars($datosAnio['anio']) ?>
                                        </h3>
                                        <p class="text-xs sm:text-sm text-slate-600 mt-1">
                                            <?= htmlspecialchars($datosAnio['descripcion']) ?>
                                        </p>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-2 self-start sm:self-center">
                                        <span class="font-code px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-institucional-navy border border-blue-200">
                                            <?= count($datosAnio['troncales']) ?> Troncales
                                        </span>
                                        <?php if ($tieneMenciones): ?>
                                            <span class="font-code px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-900 border border-amber-300">
                                                + 4 de Mención
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Materias de Tronco Común -->
                                <div class="mt-5">
                                    <h4 class="font-code text-xs uppercase tracking-wider text-slate-400 font-bold mb-3 flex items-center gap-2">
                                        <i class="fas fa-layer-group text-institucional-navy"></i> Asignaturas de Formación Común (Troncales)
                                    </h4>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                                        <?php foreach ($datosAnio['troncales'] as $idx => $materia): ?>
                                            <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-50/90 hover:bg-white border border-slate-200/80 hover:border-institucional-navy/30 transition-all">
                                                <span class="font-code text-[11px] font-bold text-institucional-navy bg-blue-100/80 w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                                    <?= str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT) ?>
                                                </span>
                                                <span class="text-xs sm:text-sm font-medium text-slate-800 leading-snug">
                                                    <?= htmlspecialchars($materia) ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- Bifurcación de Menciones (Solo en 3er y 4to Año) -->
                                <?php if ($tieneMenciones): ?>
                                    <div class="mt-7 pt-6 border-t-2 border-dashed border-institucional-gold/50">
                                        <div class="flex items-center gap-2 mb-4">
                                            <span class="w-2 h-2 rounded-full bg-institucional-gold"></span>
                                            <h4 class="font-code text-xs uppercase tracking-wider text-institucional-navy font-bold">
                                                Rutas de Especialización por Mención (<?= htmlspecialchars($datosAnio['anio']) ?>)
                                            </h4>
                                        </div>

                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                                            <!-- Panel Mención Seguridad Informática -->
                                            <div class="panel-mencion-seguridad rounded-2xl p-5 bg-gradient-to-br from-blue-50 via-white to-amber-50/40 text-slate-900 border-2 border-institucional-navy/25 shadow-md transition-all duration-300">
                                                <div class="flex items-center justify-between pb-3 mb-3.5 border-b border-slate-200">
                                                    <div class="flex items-center gap-2.5">
                                                        <div class="w-9 h-9 rounded-xl bg-institucional-navy text-institucional-gold flex items-center justify-center text-sm font-bold shadow-sm">
                                                            <i class="<?= $datosAnio['menciones']['seguridad']['icono'] ?>"></i>
                                                        </div>
                                                        <div>
                                                            <span class="font-code text-[10px] uppercase tracking-wider text-institucional-navy font-semibold block">Ruta de Especialidad</span>
                                                            <h5 class="font-display text-base font-bold text-slate-900">
                                                                <?= htmlspecialchars($datosAnio['menciones']['seguridad']['nombre']) ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>

                                                <ul class="space-y-2">
                                                    <?php foreach ($datosAnio['menciones']['seguridad']['materias'] as $matMov): ?>
                                                        <li class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-800 bg-white px-3.5 py-2.5 rounded-xl border border-slate-200/90 shadow-sm">
                                                            <i class="fas fa-check-circle text-institucional-navy mt-0.5 text-xs"></i>
                                                            <span class="font-medium"><?= htmlspecialchars($matMov) ?></span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>

                                            <!-- Panel Mención Computación Móvil -->
                                            <div class="panel-mencion-movil rounded-2xl p-5 bg-gradient-to-br from-blue-50 via-white to-amber-50/40 text-slate-900 border-2 border-institucional-navy/25 shadow-md transition-all duration-300">
                                                <div class="flex items-center justify-between pb-3 mb-3.5 border-b border-slate-200">
                                                    <div class="flex items-center gap-2.5">
                                                        <div class="w-9 h-9 rounded-xl bg-institucional-navy text-institucional-gold flex items-center justify-center text-sm font-bold shadow-sm">
                                                            <i class="<?= $datosAnio['menciones']['movil']['icono'] ?>"></i>
                                                        </div>
                                                        <div>
                                                            <span class="font-code text-[10px] uppercase tracking-wider text-institucional-navy font-semibold block">Ruta de Especialidad</span>
                                                            <h5 class="font-display text-base font-bold text-slate-900">
                                                                <?= htmlspecialchars($datosAnio['menciones']['movil']['nombre']) ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>

                                                <ul class="space-y-2">
                                                    <?php foreach ($datosAnio['menciones']['movil']['materias'] as $matMov): ?>
                                                        <li class="flex items-start gap-2.5 text-xs sm:text-sm text-slate-800 bg-white px-3.5 py-2.5 rounded-xl border border-slate-200/90 shadow-sm">
                                                            <i class="fas fa-check-circle text-institucional-navy mt-0.5 text-xs"></i>
                                                            <span class="font-medium"><?= htmlspecialchars($matMov) ?></span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Hito de Titulación al concluir el Año (2do, 3ro y 5to) -->
                                <?php if (!empty($datosAnio['hito'])): ?>
                                    <div class="mt-6 pt-4 border-t border-slate-200/80 flex items-center gap-3 text-xs sm:text-sm font-semibold text-institucional-navy bg-amber-50/70 px-4 py-3 rounded-xl border border-institucional-gold/50">
                                        <i class="fas fa-award text-institucional-gold text-base"></i>
                                        <span><?= htmlspecialchars($datosAnio['hito']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>


        <!-- =================================================================
             BLOQUE 3: DOCUMENTOS OFICIALES DEL PLAN DE ESTUDIOS (PDFs de la BD)
             ================================================================= -->
        <div class="code-window-panel overflow-hidden">
            <div class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-3.5 border-b border-institucional-gold/40 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                    </div>
                    <span class="font-code text-[13px] text-institucional-gold font-medium tracking-normal pl-2 border-l border-white/20">
                        &lt;/&gt; Documentos Curriculares y Malla Oficial (PDF)
                    </span>
                </div>
                <span class="font-code text-xs text-slate-200 flex items-center gap-2">
                    <i class="fas fa-file-pdf text-institucional-gold"></i> Descarga y lectura en línea
                </span>
            </div>

            <div class="p-6 sm:p-8 lg:p-10">
                <?php if (!empty($documentosPlan)): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($documentosPlan as $doc): ?>
                            <?php
                                $titulo     = trim((string) ($doc['titulo'] ?? 'Plan de Estudios'));
                                $gestion    = !empty($doc['gestion']) ? trim((string) $doc['gestion']) : null;
                                $archivoPdf = trim((string) ($doc['archivo_pdf'] ?? ''));
                                $urlPdf     = '/uploads/pdf/documents/' . rawurlencode($archivoPdf);
                            ?>

                            <article class="tech-card flex flex-col justify-between relative overflow-hidden group">
                                <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-institucional-navy via-blue-600 to-institucional-gold"></div>

                                <div class="p-6 flex items-start gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-institucional-navy text-institucional-gold group-hover:bg-institucional-gold group-hover:text-slate-900 flex items-center justify-center text-2xl flex-shrink-0 shadow-md transition-colors">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <span class="font-code inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                <i class="fas fa-book text-[10px]"></i> Documento Curricular
                                            </span>
                                            <?php if ($gestion !== null): ?>
                                                <span class="font-code px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                    Gestión <?= htmlspecialchars($gestion) ?>
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
                <?php else: ?>
                    <div class="text-center py-8 bg-white/60 rounded-xl border border-dashed border-slate-300 text-sm text-slate-500">
                        No hay archivos PDF de malla o plan de estudios registrados en este momento.
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>

<!-- MODAL VISOR DE PDF INTEGRADO -->
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
            <iframe id="iframe-visor-pdf" src="" title="Visor PDF Institucional" class="w-full h-full border-0"></iframe>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Selector para resaltar una ruta de mención específica
        const btnsRuta = document.querySelectorAll('.btn-ruta-mencion');
        const panelesSeg = document.querySelectorAll('.panel-mencion-seguridad');
        const panelesMov = document.querySelectorAll('.panel-mencion-movil');

        btnsRuta.forEach((btn) => {
            btn.addEventListener('click', () => {
                const ruta = btn.getAttribute('data-ruta') || 'ambas';

                btnsRuta.forEach((b) => {
                    b.className = 'btn-ruta-mencion px-3.5 py-1 rounded-full text-xs font-medium bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-all';
                });
                btn.className = 'btn-ruta-mencion px-3.5 py-1 rounded-full text-xs font-semibold bg-institucional-gold text-slate-900 transition-all shadow-sm';

                panelesSeg.forEach((p) => {
                    p.classList.toggle('opacity-35', ruta === 'movil');
                    p.classList.toggle('scale-[0.98]', ruta === 'movil');
                });
                panelesMov.forEach((p) => {
                    p.classList.toggle('opacity-35', ruta === 'seguridad');
                    p.classList.toggle('scale-[0.98]', ruta === 'seguridad');
                });
            });
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
                const titulo = btn.getAttribute('data-titulo') || 'Documento PDF';

                tituloPdf.textContent = titulo;
                iframePdf.src = url;
                linkPestana.href = url;

                modalPdf.classList.remove('hidden');
                void modalPdf.offsetWidth;
                modalPdf.classList.remove('opacity-0');
                cardPdf.classList.remove('scale-95');
            });
        });

        const cerrarVisor = () => {
            modalPdf.classList.add('opacity-0');
            cardPdf.classList.add('scale-95');
            setTimeout(() => {
                modalPdf.classList.add('hidden');
                iframePdf.src = '';
            }, 200);
        };

        btnCerrarPdf?.addEventListener('click', cerrarVisor);
        modalPdf?.addEventListener('click', (e) => {
            if (e.target === modalPdf) cerrarVisor();
        });
    });
</script>