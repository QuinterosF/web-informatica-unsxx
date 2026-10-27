<?php

require_once __DIR__ . '/../layouts/rich_text.php';

if (class_exists('\App\Models\PublicPortal')) {
    $portal = new \App\Models\PublicPortal();
    $common     = $common ?? $portal->getCommonLayoutData();
    $info       = $info ?? ($common['info'] ?? []);
    $mentions   = $mentions ?? $portal->getMentions();
    $admissions = $admissions ?? $portal->getAdmissions();
    $documents  = $documents ?? $portal->getOfficialDocuments();
    $phones     = $phones ?? ($common['phones'] ?? []);
}

// Localizar PDF del Plan de Estudios y PDFs de Reglamentos de Titulación en `documento_oficial`
$planEstudiosPdf = null;
$reglamentosTitulacion = [];
foreach ($documents ?? [] as $doc) {
    if (($doc['categoria'] ?? '') === 'plan_estudios' && $planEstudiosPdf === null) {
        $planEstudiosPdf = $doc;
    }
    if (($doc['categoria'] ?? '') === 'reglamento_titulacion') {
        $reglamentosTitulacion[] = $doc;
    }
}

// Estructura Curricular Oficial de 1.º a 5.º Año extraída de plan_estudios.pdf (págs. 88–96)
$curriculumYears = [
    1 => [
        'title' => 'PRIMER AÑO',
        'subtitle' => 'Formación Específica (30 hrs) + Idiomas (4 hrs) + F.P.S. (4 hrs)',
        'common' => [
            ['n' => 1,  'sigla' => 'MAT1100', 'materia' => 'ÁLGEBRA SUPERIOR Y LINEAL', 'ht' => 4, 'hl' => 0, 'th' => 4, 'pre' => 'Mod. Ingreso'],
            ['n' => 2,  'sigla' => 'MAT1101', 'materia' => 'ANÁLISIS MATEMÁTICO I', 'ht' => 4, 'hl' => 0, 'th' => 4, 'pre' => 'Mod. Ingreso'],
            ['n' => 3,  'sigla' => 'FIS1100', 'materia' => 'FÍSICA I', 'ht' => 4, 'hl' => 0, 'th' => 4, 'pre' => 'Mod. Ingreso'],
            ['n' => 4,  'sigla' => 'INF1100', 'materia' => 'METODOLOGÍA DE LA PROGRAMACIÓN', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'Mod. Ingreso'],
            ['n' => 5,  'sigla' => 'INF1101', 'materia' => 'ARQUITECTURA DE COMPUTADORAS Y DE DISPOSITIVOS MÓVILES', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'Mod. Ingreso'],
            ['n' => 6,  'sigla' => 'INF1102', 'materia' => 'CONTABILIDAD BÁSICA Y ADMINISTRACIÓN GENERAL', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'Mod. Ingreso'],
            ['n' => 7,  'sigla' => 'INF1120', 'materia' => 'TALLER DE PROGRAMACIÓN', 'ht' => 0, 'hl' => 4, 'th' => 4, 'pre' => 'Mod. Ingreso'],
            ['n' => 8,  'sigla' => 'INF1103', 'materia' => 'METODOLOGÍA DE LA INVESTIGACIÓN', 'ht' => 2, 'hl' => 0, 'th' => 2, 'pre' => 'Mod. Ingreso'],
            ['n' => 9,  'sigla' => 'LIN104',  'materia' => 'INGLÉS TÉCNICO (Idiomas)', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => '—'],
            ['n' => 10, 'sigla' => 'FPS1100', 'materia' => 'HISTORIA DEL MOVIMIENTO OBRERO I (F.P.S.)', 'ht' => 2, 'hl' => 0, 'th' => 2, 'pre' => '—'],
            ['n' => 11, 'sigla' => 'FPS1101', 'materia' => 'FILOSOFÍA (F.P.S.)', 'ht' => 2, 'hl' => 0, 'th' => 2, 'pre' => '—'],
        ],
    ],
    2 => [
        'title' => 'SEGUNDO AÑO',
        'subtitle' => 'Al concluir 2.º año puedes optar al título intermedio de Técnico Universitario Medio en Programación de Sistemas Informáticos',
        'common' => [
            ['n' => 1,  'sigla' => 'MAT1102', 'materia' => 'ANÁLISIS MATEMÁTICO II', 'ht' => 4, 'hl' => 0, 'th' => 4, 'pre' => 'MAT1101'],
            ['n' => 2,  'sigla' => 'FIS1101', 'materia' => 'FÍSICA II', 'ht' => 4, 'hl' => 0, 'th' => 4, 'pre' => 'FIS1100'],
            ['n' => 3,  'sigla' => 'INF2100', 'materia' => 'DESARROLLO WEB', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF1120, INF1100'],
            ['n' => 4,  'sigla' => 'INF2230', 'materia' => 'DISEÑO Y ADMINISTRACIÓN DE BASE DE DATOS', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF1100, MAT1100'],
            ['n' => 5,  'sigla' => 'INF2104', 'materia' => 'TALLER DE DESARROLLO DE SOFTWARE', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF1100, INF1120'],
            ['n' => 6,  'sigla' => 'INF2106', 'materia' => 'SISTEMAS OPERATIVOS', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF1100, INF1120'],
            ['n' => 7,  'sigla' => 'INF2210', 'materia' => 'ESTRUCTURA DE DATOS', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF1100, INF1120'],
            ['n' => 8,  'sigla' => 'INF2220', 'materia' => 'ESTADÍSTICA Y PROBABILIDAD', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'MAT1100, MAT1101'],
            ['n' => 9,  'sigla' => 'INF2240', 'materia' => 'ANÁLISIS Y DISEÑO DE SISTEMAS', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF1100, INF1120'],
            ['n' => 10, 'sigla' => 'FPS2100', 'materia' => 'HISTORIA DEL MOVIMIENTO OBRERO II (F.P.S.)', 'ht' => 2, 'hl' => 0, 'th' => 2, 'pre' => '—'],
            ['n' => 11, 'sigla' => 'FPS2101', 'materia' => 'ECONOMÍA POLÍTICA (F.P.S.)', 'ht' => 2, 'hl' => 0, 'th' => 2, 'pre' => '—'],
        ],
    ],
    3 => [
        'title' => 'TERCER AÑO',
        'subtitle' => 'Tronco Común + Especialización por Mención. Habilita salida lateral a Técnico Universitario Superior en Sistemas Informáticos',
        'common' => [
            ['n' => 1, 'sigla' => 'INF3100', 'materia' => 'INVESTIGACIÓN OPERATIVA', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'MAT1100, MAT1101'],
            ['n' => 2, 'sigla' => 'INF3101', 'materia' => 'SIMULACIÓN DE SISTEMAS', 'ht' => 2, 'hl' => 0, 'th' => 2, 'pre' => 'INF2220'],
            ['n' => 3, 'sigla' => 'INF3300', 'materia' => 'REDES I', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF1101, FIS1100'],
            ['n' => 4, 'sigla' => 'INF3630', 'materia' => 'TECNOLOGÍA Y PROGRAMACIÓN EN ROBÓTICA MÓVIL', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF2106, FIS1100, INF1100'],
            ['n' => 5, 'sigla' => 'INF3720', 'materia' => 'TECNOLOGÍAS EMERGENTES', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF1101, INF2100'],
            ['n' => 6, 'sigla' => 'INF3740', 'materia' => 'TELECOMUNICACIONES', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF2100'],
            ['n' => 7, 'sigla' => 'FPS3000', 'materia' => 'FORMACIÓN SOCIAL BOLIVIANA (F.P.S.)', 'ht' => 2, 'hl' => 0, 'th' => 2, 'pre' => '—'],
            ['n' => 8, 'sigla' => 'FPS3101', 'materia' => 'LEGISLACIÓN SOCIAL BOLIVIANA (F.P.S.)', 'ht' => 2, 'hl' => 0, 'th' => 2, 'pre' => '—'],
        ],
        'mentions' => [
            'Mención Seguridad Informática' => [
                ['n' => 1, 'sigla' => 'INF3400', 'materia' => 'CRIPTOGRAFÍA Y ESTEGANOGRAFÍA', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF2100, INF2210, MAT1100'],
                ['n' => 2, 'sigla' => 'INF3401', 'materia' => 'SERVIDORES Y SEGURIDAD EN REDES', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF2106'],
                ['n' => 3, 'sigla' => 'INF3402', 'materia' => 'SEGURIDAD EN SISTEMAS DE INFORMACIÓN Y BASE DE DATOS', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF2106, INF2130'],
                ['n' => 4, 'sigla' => 'INF3403', 'materia' => 'CIBERSEGURIDAD Y ANÁLISIS DE VULNERABILIDADES', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF2100, INF2106'],
            ],
            'Mención Computación Móvil' => [
                ['n' => 1, 'sigla' => 'INF3200', 'materia' => 'PROGRAMACIÓN DE DISPOSITIVOS MÓVILES', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF2130, INF2104'],
                ['n' => 2, 'sigla' => 'INF3201', 'materia' => 'DESARROLLO DE VIDEOJUEGOS PARA DISPOSITIVOS MÓVILES', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF2100, INF2104'],
                ['n' => 3, 'sigla' => 'INF3202', 'materia' => 'REDES MÓVILES Y CLOUD COMPUTING', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF2100, FIS1101'],
                ['n' => 4, 'sigla' => 'INF3203', 'materia' => 'DESARROLLO DE APLICACIONES MÓVILES PARA GEOLOCALIZACIÓN', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF2100, FIS1101'],
            ],
        ]
    ],
    4 => [
        'title' => 'CUARTO AÑO',
        'subtitle' => 'Ingeniería Avanzada (18 hrs) + Especialidad de Mención (16 hrs) + F.P.S. (4 hrs)',
        'common' => [
            ['n' => 1, 'sigla' => 'INF4100', 'materia' => 'INGENIERÍA DE SOFTWARE', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF2240, INF2104'],
            ['n' => 2, 'sigla' => 'INF4200', 'materia' => 'INTELIGENCIA ARTIFICIAL', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF3101, INF2220, MAT1102'],
            ['n' => 3, 'sigla' => 'INF4300', 'materia' => 'INGENIERÍA DE SISTEMAS', 'ht' => 2, 'hl' => 0, 'th' => 2, 'pre' => 'INF3720, INF2240'],
            ['n' => 4, 'sigla' => 'INF4400', 'materia' => 'PROGRAMACIÓN GRÁFICA', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF3101, INF2104, INF2210'],
            ['n' => 5, 'sigla' => 'INF4700', 'materia' => 'ELECTIVA I', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF3101'],
            ['n' => 6, 'sigla' => 'FPS4000', 'materia' => 'POLÍTICA NACIONAL (F.P.S.)', 'ht' => 2, 'hl' => 0, 'th' => 2, 'pre' => '—'],
            ['n' => 7, 'sigla' => 'FPS4101', 'materia' => 'POLÍTICA ECONÓMICA NACIONAL (F.P.S.)', 'ht' => 2, 'hl' => 0, 'th' => 2, 'pre' => '—'],
        ],
        'mentions' => [
            'Mención Seguridad Informática' => [
                ['n' => 1, 'sigla' => 'INF4600', 'materia' => 'INFORMÁTICA FORENSE', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF3403'],
                ['n' => 2, 'sigla' => 'INF4601', 'materia' => 'INTELIGENCIA DE AMENAZAS Y ETHICAL HACKING', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF3401'],
                ['n' => 3, 'sigla' => 'INF4602', 'materia' => 'SISTEMAS DE GESTIÓN DE SEGURIDAD DE TECNOLOGÍAS DE LA INFORMACIÓN', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF3100, INF3402'],
                ['n' => 4, 'sigla' => 'INF4603', 'materia' => 'AUDITORÍA INFORMÁTICA', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF3401, INF3402'],
            ],
            'Mención Computación Móvil' => [
                ['n' => 1, 'sigla' => 'INF4500', 'materia' => 'REALIDAD VIRTUAL Y AUMENTADA', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF3201, INF3200'],
                ['n' => 2, 'sigla' => 'INF4501', 'materia' => 'TALLER DE DESARROLLO DE APLICACIONES PARA DISPOSITIVOS MÓVILES', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF3200'],
                ['n' => 3, 'sigla' => 'INF4502', 'materia' => 'INTERNET DE LAS COSAS', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF3202, INF3740'],
                ['n' => 4, 'sigla' => 'INF4503', 'materia' => 'VISIÓN ARTIFICIAL PARA DISPOSITIVOS MÓVILES', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF3200'],
            ],
        ]
    ],
    5 => [
        'title' => 'QUINTO AÑO',
        'subtitle' => 'Culminación de Licenciatura, Prácticas Profesionales y Graduación',
        'common' => [
            ['n' => 1, 'sigla' => 'INF5200', 'materia' => 'TALLER DE GRADUACIÓN', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => '4.º Año Venc.'],
            ['n' => 2, 'sigla' => 'INF5201', 'materia' => 'PRÁCTICAS PROFESIONALES', 'ht' => 2, 'hl' => 0, 'th' => 2, 'pre' => '4.º Año Venc.'],
            ['n' => 3, 'sigla' => 'INF5202', 'materia' => 'EMPRENDIMIENTO, PREPARACIÓN, EVALUACIÓN Y GESTIÓN DE PROYECTOS Y ALTA GERENCIA', 'ht' => 2, 'hl' => 2, 'th' => 4, 'pre' => 'INF4300'],
            ['n' => 4, 'sigla' => 'INF5203', 'materia' => 'ELECTIVA II', 'ht' => 2, 'hl' => 4, 'th' => 6, 'pre' => '—'],
            ['n' => 5, 'sigla' => 'FPS5000', 'materia' => 'DIRECCIÓN POLÍTICA (F.P.S.)', 'ht' => 2, 'hl' => 0, 'th' => 2, 'pre' => '—'],
            ['n' => 6, 'sigla' => 'FPS5101', 'materia' => 'TESIS POLÍTICA (F.P.S.)', 'ht' => 2, 'hl' => 0, 'th' => 2, 'pre' => '—'],
        ],
    ],
];

$electivasCatalogo = [
    'Marketing Digital', 'Programación de Agentes Inteligentes', 'Reconocimiento de Patrones',
    'Machine Learning y Deep Learning', 'Programación Concurrente y Paralela', 'Programación Lógica y Funcional',
    'Procesamiento de Imágenes', 'Tecnología de Drones y Droides', 'Complejidad Computacional',
    'Computación Cuántica', 'Bioinformática', 'Teoría de Códigos', 'Geometría Computacional',
    'Gobierno de las TICs y Ciudades Inteligentes', 'Ciencia de Datos', 'Software Libre y Estándares Abiertos',
    'Robótica Industrial', 'Humanoides', 'Sistemas Expertos', 'Gestión del Talento Humano',
    'Testeo y Calidad de Software', 'Virtualización', 'Lingüística Computacional', 'Reingeniería de Sistemas',
    'DevOps', 'Diseño Gráfico', 'Modelado Tridimensional', 'UI/UX, IxD', 'Entornos Virtuales de Aprendizaje',
    'Makers', 'Programación de Autómatas Programables', 'Sistemas Digitales',
    'Programación de Animación y Renderización', 'Redes II', 'Programación Bajo Nivel'
];

$title = 'La Carrera y Plan de Estudios — Ingeniería Informática UNSXX';
ob_start();
?>

<!-- =========================================================================
     1. CABECERA INSTITUCIONAL Y PALABRAS DE DIRECCIÓN (#direccion)
     ========================================================================= -->
<section id="direccion" class="aurora-hero-surface pt-8 pb-14 border-b border-[#dbe3ef] dark:border-[#151e32]">
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">
        <span class="font-mono text-xs font-semibold uppercase tracking-widest text-[#2862d7] dark:text-[#85a6e9]">
            &lt;/&gt; Ingeniería Informática — UNSXX
        </span>
        <h1 class="font-heading text-3xl sm:text-5xl font-extrabold text-[#08304c] dark:text-white mt-1">
            Acerca de Nuestra Carrera
        </h1>

        <?php if (!empty($info['mensaje_director']) || !empty($info['foto_director'])): ?>
            <div class="mt-8 rounded-2xl p-6 sm:p-8 bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540] shadow-card-light dark:shadow-card-dark grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                <?php if (!empty($info['foto_director'])): ?>
                    <div class="lg:col-span-3 flex justify-center">
                        <div class="w-40 h-40 sm:w-48 sm:h-48 rounded-2xl overflow-hidden skeleton-shimmer border-2 border-[#dbe3ef] dark:border-[#24375a]">
                            <img src="/uploads/img/institucion/<?= htmlspecialchars($info['foto_director']) ?>"
                                 alt="Dirección de Carrera" class="lazy-skeleton w-full h-full object-cover">
                        </div>
                    </div>
                <?php endif; ?>
                <div class="<?= !empty($info['foto_director']) ? 'lg:col-span-9' : 'lg:col-span-12' ?>">
                    <span class="inline-block px-3 py-1 rounded-full font-mono text-[11px] font-semibold bg-[#e8f1ff] text-[#08304c] dark:bg-[#12244f] dark:text-yellow-400 mb-3">
                        Palabras de Dirección de Carrera
                    </span>
                    <div class="text-sm sm:text-base text-[#2c3444] dark:text-[#c7c9d1]">
                        <?= renderRichText($info['mensaje_director'] ?? '') ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- =========================================================================
     2. IDENTIDAD: PRESENTACIÓN, PERFIL, MISIÓN, VISIÓN, OBJETIVOS Y RESEÑA (#identidad)
     ========================================================================= -->
<section id="identidad" class="py-16">
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Presentación -->
            <?php if (!empty($info['presentacion'])): ?>
                <div class="rounded-2xl p-6 sm:p-8 bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540] shadow-card-light dark:shadow-card-dark">
                    <span class="font-mono text-xs uppercase text-[#2862d7] dark:text-[#85a6e9]">01 / Identidad</span>
                    <h2 class="font-heading text-2xl font-bold text-[#08304c] dark:text-white mt-1 mb-3">Presentación</h2>
                    <div class="text-sm text-[#2c3444] dark:text-[#c7c9d1]">
                        <?= renderRichText($info['presentacion']) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Perfil Profesional -->
            <?php if (!empty($info['perfil_general'])): ?>
                <div class="rounded-2xl p-6 sm:p-8 bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540] shadow-card-light dark:shadow-card-dark">
                    <span class="font-mono text-xs uppercase text-[#2862d7] dark:text-[#85a6e9]">02 / Competencias</span>
                    <h2 class="font-heading text-2xl font-bold text-[#08304c] dark:text-white mt-1 mb-3">Perfil Profesional</h2>
                    <div class="text-sm text-[#2c3444] dark:text-[#c7c9d1]">
                        <?= renderRichText($info['perfil_general']) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Misión, Visión y Objetivo General (Tríptico + BD) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="rounded-2xl p-6 bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540] shadow-card-light dark:shadow-card-dark">
                <span class="font-mono text-xs uppercase text-[#2862d7] dark:text-[#85a6e9]">03 / Misión</span>
                <h3 class="font-heading text-xl font-bold text-[#08304c] dark:text-white mt-1 mb-3">Misión</h3>
                <div class="text-sm text-[#2c3444] dark:text-[#c7c9d1]">
                    <?= !empty($info['mision'])
                        ? renderRichText($info['mision'])
                        : 'Formar profesionales informáticos orgánicos idóneos con vocación revolucionaria para resolver problemas tecnológicos, coadyuvando en la solución de problemas económicos, políticos y sociales desde el ámbito de las Ciencias de la Computación con menciones de seguridad informática y computación móvil.' ?>
                </div>
            </div>

            <div class="rounded-2xl p-6 bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540] shadow-card-light dark:shadow-card-dark">
                <span class="font-mono text-xs uppercase text-[#2862d7] dark:text-[#85a6e9]">04 / Visión</span>
                <h3 class="font-heading text-xl font-bold text-[#08304c] dark:text-white mt-1 mb-3">Visión</h3>
                <div class="text-sm text-[#2c3444] dark:text-[#c7c9d1]">
                    <?= !empty($info['vision'])
                        ? renderRichText($info['vision'])
                        : 'Ser un centro de formación profesional líder en ingeniería informática de alto nivel académico, tecnológico y científico, constituyéndose en el pilar fundamental para el desarrollo de la ciencia y tecnología a nivel regional, nacional e internacional, promoviendo proyectos de investigación y de extensión.' ?>
                </div>
            </div>

            <div class="rounded-2xl p-6 bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540] shadow-card-light dark:shadow-card-dark">
                <span class="font-mono text-xs uppercase text-[#2862d7] dark:text-[#85a6e9]">05 / Objetivo General</span>
                <h3 class="font-heading text-xl font-bold text-[#08304c] dark:text-white mt-1 mb-3">Objetivo General</h3>
                <div class="text-sm text-[#2c3444] dark:text-[#c7c9d1]">
                    <?= !empty($info['objetivos'])
                        ? renderRichText($info['objetivos'])
                        : 'Formar profesionales orgánicos con capacidad científica, que logren implementar soluciones y aplicaciones tecnológicas en las diferentes áreas de desarrollo de la región y el país.' ?>
                </div>
            </div>
        </div>

        <!-- Reseña Histórica -->
        <?php if (!empty($info['resena_historica'])): ?>
            <div class="rounded-2xl p-6 sm:p-8 bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540] shadow-card-light dark:shadow-card-dark">
                <span class="font-mono text-xs uppercase text-[#2862d7] dark:text-[#85a6e9]">06 / Trayectoria Institucional</span>
                <h2 class="font-heading text-2xl font-bold text-[#08304c] dark:text-white mt-1 mb-3">Reseña Histórica</h2>
                <div class="text-sm text-[#2c3444] dark:text-[#c7c9d1]">
                    <?= renderRichText($info['resena_historica']) ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- =========================================================================
     3. NIVEL DE FORMACIÓN PIRAMIDAL Y MENCIONES (#piramidal y #menciones)
     ========================================================================= -->
<section id="piramidal" class="py-16 bg-[#f4f7fb] dark:bg-[#0b0c0e] border-y border-[#dbe3ef] dark:border-[#151e32]">
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">

        <span class="font-mono text-xs font-semibold uppercase tracking-widest text-[#2862d7] dark:text-[#85a6e9]">
            Estructura Curricular Oficial
        </span>
        <h2 class="font-heading text-3xl font-bold text-[#08304c] dark:text-white mt-1 mb-6">
            Tabla de Nivel de Formación Piramidal
        </h2>

        <div class="rounded-2xl overflow-hidden bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540] shadow-card-light dark:shadow-card-dark mb-14">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="font-mono text-xs uppercase bg-[#08304c] text-white dark:bg-[#12244f]">
                        <tr>
                            <th class="px-5 py-3.5">Tiempo de Estudio</th>
                            <th class="px-5 py-3.5">Grado Académico</th>
                            <th class="px-5 py-3.5">Diploma Académico</th>
                            <th class="px-5 py-3.5">Título en Provisión Nacional</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#edf2f7] dark:divide-[#151e32]">
                        <tr class="bg-[#ebf3ff]/50 dark:bg-[#12244f]/40">
                            <td class="px-5 py-4 font-mono font-bold text-[#08304c] dark:text-yellow-400">5 años</td>
                            <td class="px-5 py-4 font-semibold">Licenciatura</td>
                            <td class="px-5 py-4">Licenciatura en Ing. Informática</td>
                            <td class="px-5 py-4 font-semibold text-[#08304c] dark:text-white">
                                Ingeniero Informático con mención:<br>
                                <span class="font-mono text-xs text-[#2862d7] dark:text-[#85a6e9]">• Seguridad Informática | • Computación Móvil</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-5 py-4 font-mono font-bold">3 años</td>
                            <td class="px-5 py-4 font-semibold">Técnico Universitario Superior</td>
                            <td class="px-5 py-4">Técnico Universitario Superior en Sistemas Informáticos</td>
                            <td class="px-5 py-4">Técnico Universitario Superior en Sistemas Informáticos</td>
                        </tr>
                        <tr>
                            <td class="px-5 py-4 font-mono font-bold">2 años</td>
                            <td class="px-5 py-4 font-semibold">Técnico Universitario Medio</td>
                            <td class="px-5 py-4">Técnico Universitario Medio en Programación de Sistemas Informáticos</td>
                            <td class="px-5 py-4">Técnico Universitario Medio en Programación de Sistemas Informáticos</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Menciones e Instituto IIDAI (#menciones) -->
        <div id="menciones" class="pt-4">
            <h2 class="font-heading text-2xl sm:text-3xl font-bold text-[#08304c] dark:text-white mb-6">
                Menciones de Especialización e Instituto de Investigación (IIDAI)
            </h2>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <?php foreach ($mentions ?? [] as $men): ?>
                    <div class="rounded-2xl p-6 bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540] shadow-card-light dark:shadow-card-dark flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-4 mb-4">
                                <?php if (!empty($men['escudo_url'])): ?>
                                    <div class="w-16 h-16 rounded-2xl overflow-hidden skeleton-shimmer bg-[#f4f7fb] dark:bg-[#12244f] p-1.5 border border-[#dbe3ef] dark:border-[#24375a] shrink-0">
                                        <img src="/uploads/img/mentions/<?= htmlspecialchars($men['escudo_url']) ?>"
                                             alt="<?= htmlspecialchars($men['nombre']) ?>"
                                             class="lazy-skeleton w-full h-full object-contain">
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <h3 class="font-heading font-bold text-lg text-[#08304c] dark:text-white">
                                        <?= htmlspecialchars($men['nombre']) ?>
                                    </h3>
                                    <?php if (!empty($men['coordinador_nombre'])): ?>
                                        <p class="font-mono text-xs text-[#2862d7] dark:text-[#85a6e9] mt-0.5">
                                            Coord: <?= htmlspecialchars($men['coordinador_nombre']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="text-sm text-[#2c3444] dark:text-[#c7c9d1]">
                                <?= renderRichText($men['perfil_profesional'] ?? '') ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</section>

<!-- =========================================================================
     4. PLAN DE ESTUDIOS INTERACTIVO ANUAL DE 1.º A 5.º AÑO (#plan-estudios)
     ========================================================================= -->
<section id="plan-estudios" class="py-16 lg:py-24">
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <span class="font-mono text-xs font-semibold uppercase tracking-widest text-[#2862d7] dark:text-[#85a6e9]">
                    &lt;/&gt; Malla Curricular Oficial • Sistema Anual
                </span>
                <h2 class="font-heading text-3xl sm:text-4xl font-extrabold text-[#08304c] dark:text-white mt-1">
                    Plan de Estudios Interactivo (1.º a 5.º Año)
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-[#abaebb] mt-1">
                    HT = Horas Teóricas • HL = Horas de Laboratorio / Práctica • TH = Total Horas Semanales • PRE-REQ = Pre-requisitos
                </p>
            </div>

            <?php if ($planEstudiosPdf): ?>
                <button type="button"
                        onclick="openPdfModal('/uploads/pdf/documents/<?= htmlspecialchars($planEstudiosPdf['archivo_pdf']) ?>', '<?= htmlspecialchars(addslashes($planEstudiosPdf['titulo'])) ?>', 'Plan de Estudios Oficial')"
                        class="px-5 py-2.5 rounded-full font-heading font-bold text-xs sm:text-sm bg-[#08304c] text-white hover:bg-blue-900 dark:bg-white dark:text-[#0b0c0e] transition-all inline-flex items-center gap-2 shrink-0 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Ver Plan de Estudios en PDF</span>
                </button>
            <?php endif; ?>
        </div>

        <!-- Acordeón Interactivo de los 5 Años (Inspirado en la referencia oficial + AgentQL) -->
        <div class="space-y-3.5">
            <?php foreach ($curriculumYears as $yearNum => $yData): ?>
                <?php $isOpen = ($yearNum === 1); ?>
                <div class="rounded-2xl overflow-hidden border border-[#dbe3ef] dark:border-[#172540] bg-white dark:bg-[#0d172b] shadow-card-light dark:shadow-card-dark">
                    <button type="button"
                            onclick="toggleCurriculumYear(<?= $yearNum ?>)"
                            id="yearHeader-<?= $yearNum ?>"
                            class="w-full px-6 py-4 flex items-center justify-between gap-4 text-left transition-colors cursor-pointer <?= $isOpen ? 'bg-[#2862d7] text-white dark:bg-[#12244f]' : 'bg-white hover:bg-[#f4f7fb] text-[#08304c] dark:bg-[#0d172b] dark:text-white dark:hover:bg-[#12244f]/60' ?>">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <div>
                                <span class="font-heading font-extrabold text-base sm:text-lg tracking-wide block">
                                    <?= htmlspecialchars($yData['title']) ?>
                                </span>
                                <span class="text-xs opacity-85 block font-normal">
                                    <?= htmlspecialchars($yData['subtitle']) ?>
                                </span>
                            </div>
                        </div>
                        <svg id="yearChevron-<?= $yearNum ?>" class="w-5 h-5 transition-transform duration-200 <?= $isOpen ? 'rotate-180' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="yearBody-<?= $yearNum ?>" class="<?= $isOpen ? 'block' : 'hidden' ?> p-4 sm:p-6 border-t border-[#dbe3ef] dark:border-[#172540] space-y-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs sm:text-sm text-left">
                                <thead class="font-mono text-[11px] uppercase text-slate-500 dark:text-[#abaebb] border-b border-[#dbe3ef] dark:border-[#172540]">
                                    <tr>
                                        <th class="py-2.5 px-3 text-center">N°</th>
                                        <th class="py-2.5 px-3">Sigla</th>
                                        <th class="py-2.5 px-3">Materia / Asignatura</th>
                                        <th class="py-2.5 px-3 text-center">Teoría (HT)</th>
                                        <th class="py-2.5 px-3 text-center">Práctica (HL)</th>
                                        <th class="py-2.5 px-3 text-center">Total (TH)</th>
                                        <th class="py-2.5 px-3">Pre-Requisito</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#edf2f7] dark:divide-[#151e32]">
                                    <?php foreach ($yData['common'] as $sub): ?>
                                        <tr class="hover:bg-[#f4f7fb] dark:hover:bg-[#12244f]/40">
                                            <td class="py-3 px-3 text-center font-mono text-slate-500"><?= $sub['n'] ?></td>
                                            <td class="py-3 px-3 font-mono font-semibold text-[#2862d7] dark:text-[#85a6e9]"><?= htmlspecialchars($sub['sigla']) ?></td>
                                            <td class="py-3 px-3 font-semibold text-[#08304c] dark:text-white"><?= htmlspecialchars($sub['materia']) ?></td>
                                            <td class="py-3 px-3 text-center font-mono"><?= $sub['ht'] ?></td>
                                            <td class="py-3 px-3 text-center font-mono"><?= $sub['hl'] ?></td>
                                            <td class="py-3 px-3 text-center font-mono font-bold"><?= $sub['th'] ?></td>
                                            <td class="py-3 px-3 font-mono text-xs text-slate-500 dark:text-[#abaebb]"><?= htmlspecialchars($sub['pre']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Si el año tiene asignaturas de Mención (3.º y 4.º Año) -->
                        <?php if (!empty($yData['mentions'])): ?>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 pt-2">
                                <?php foreach ($yData['mentions'] as $menTitle => $menSubjects): ?>
                                    <div class="rounded-xl p-4 bg-[#f4f7fb] dark:bg-[#0b0c0e] border border-[#dbe3ef] dark:border-[#172540]">
                                        <h4 class="font-heading font-bold text-sm uppercase tracking-wider text-[#1d4ed8] dark:text-yellow-400 mb-3">
                                            • <?= htmlspecialchars($menTitle) ?> (16 hrs)
                                        </h4>
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-xs text-left">
                                                <thead class="font-mono text-[10px] uppercase text-slate-500 dark:text-[#abaebb] border-b border-[#dbe3ef] dark:border-[#151e32]">
                                                    <tr>
                                                        <th class="py-2 pr-2">Sigla</th>
                                                        <th class="py-2 px-2">Asignatura de Mención</th>
                                                        <th class="py-2 px-1 text-center">HT</th>
                                                        <th class="py-2 px-1 text-center">HL</th>
                                                        <th class="py-2 px-1 text-center">TH</th>
                                                        <th class="py-2 pl-2">Pre-Req</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-200/60 dark:divide-[#151e32]">
                                                    <?php foreach ($menSubjects as $ms): ?>
                                                        <tr>
                                                            <td class="py-2.5 pr-2 font-mono font-semibold text-[#2862d7] dark:text-[#85a6e9]"><?= htmlspecialchars($ms['sigla']) ?></td>
                                                            <td class="py-2.5 px-2 font-medium text-[#08304c] dark:text-white"><?= htmlspecialchars($ms['materia']) ?></td>
                                                            <td class="py-2.5 px-1 text-center font-mono"><?= $ms['ht'] ?></td>
                                                            <td class="py-2.5 px-1 text-center font-mono"><?= $ms['hl'] ?></td>
                                                            <td class="py-2.5 px-1 text-center font-mono font-bold"><?= $ms['th'] ?></td>
                                                            <td class="py-2.5 pl-2 font-mono text-[11px] text-slate-500 dark:text-[#abaebb]"><?= htmlspecialchars($ms['pre']) ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Banco Oficial de Asignaturas Electivas (Electiva I y Electiva II) -->
        <div class="mt-8 rounded-2xl p-6 bg-[#f4f7fb] dark:bg-[#0b0c0e] border border-[#dbe3ef] dark:border-[#172540]">
            <h3 class="font-heading font-bold text-lg text-[#08304c] dark:text-white mb-1">
                Catálogo de Asignaturas Electivas (Electiva I y Electiva II)
            </h3>
            <p class="text-xs text-slate-500 dark:text-[#abaebb] mb-4">
                Asignaturas dinámicas de actualización tecnológica continua conforme al Plan de Estudios vigente:
            </p>
            <div class="flex flex-wrap gap-2">
                <?php foreach ($electivasCatalogo as $el): ?>
                    <span class="px-3 py-1 rounded-full font-mono text-xs bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540] text-[#08304c] dark:text-[#c7c9d1]">
                        <?= htmlspecialchars($el) ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</section>

<!-- =========================================================================
     5. MODALIDADES DE ADMISIÓN, TITULACIÓN Y BECAS (#admision, #titulacion, #becas)
     ========================================================================= -->
<section class="py-16 bg-[#f4f7fb] dark:bg-[#0b0c0e] border-t border-[#dbe3ef] dark:border-[#151e32]">
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        <!-- A. MODALIDADES DE ADMISIÓN (Híbrido: 4 vías del Tríptico + Convocatorias Dinámicas BD) -->
        <div id="admision">
            <span class="font-mono text-xs font-semibold uppercase tracking-widest text-[#2862d7] dark:text-[#85a6e9]">
                Postulantes y Nuevos Ingresos
            </span>
            <h2 class="font-heading text-3xl font-bold text-[#08304c] dark:text-white mt-1 mb-6">
                Modalidades de Admisión
            </h2>

            <!-- Las 4 vías oficiales del Tríptico -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="p-5 rounded-2xl bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540]">
                    <span class="font-mono text-xs text-[#2862d7] dark:text-yellow-400 font-bold">01</span>
                    <h3 class="font-heading font-bold text-base text-[#08304c] dark:text-white mt-1">Prueba de Admisión</h3>
                    <p class="text-xs text-slate-500 dark:text-[#abaebb] mt-1">Evaluación directa de conocimientos para bachilleres postulantes.</p>
                </div>
                <div class="p-5 rounded-2xl bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540]">
                    <span class="font-mono text-xs text-[#2862d7] dark:text-yellow-400 font-bold">02</span>
                    <h3 class="font-heading font-bold text-base text-[#08304c] dark:text-white mt-1">Curso Preuniversitario</h3>
                    <p class="text-xs text-slate-500 dark:text-[#abaebb] mt-1">Preparación nivelatoria en ciencias exactas y fundamentos de programación.</p>
                </div>
                <div class="p-5 rounded-2xl bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540]">
                    <span class="font-mono text-xs text-[#2862d7] dark:text-yellow-400 font-bold">03</span>
                    <h3 class="font-heading font-bold text-base text-[#08304c] dark:text-white mt-1">Admisión Especial (Convenio)</h3>
                    <p class="text-xs text-slate-500 dark:text-[#abaebb] mt-1">Ingreso mediante convenios interinstitucionales y sectoriales de la UNSXX.</p>
                </div>
                <div class="p-5 rounded-2xl bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540]">
                    <span class="font-mono text-xs text-[#2862d7] dark:text-yellow-400 font-bold">04</span>
                    <h3 class="font-heading font-bold text-base text-[#08304c] dark:text-white mt-1">Ingreso Directo</h3>
                    <p class="text-xs text-slate-500 dark:text-[#abaebb] mt-1">Modalidad de excelencia para el Mejor Alumno de su Establecimiento Educativo.</p>
                </div>
            </div>

            <!-- Convocatorias Dinámicas de la tabla `admision` -->
            <?php if (!empty($admissions)): ?>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <?php foreach ($admissions as $adm): ?>
                        <?php
                        $rawImg = trim((string)($adm['img_adm'] ?? ''));
                        $hasPoster = ($rawImg !== '' && strtolower($rawImg) !== 'default.png');
                        $posterUrl = $hasPoster ? '/uploads/img/admission/' . $rawImg : null;
                        $tipoNombre = ($adm['tipo'] ?? '') === 'prueba' ? 'Prueba de Admisión' : 'Curso Preuniversitario';
                        ?>
                        <div class="rounded-2xl p-6 bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540] shadow-card-light dark:shadow-card-dark flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <span class="px-3 py-1 rounded-full font-mono text-xs font-bold bg-[#e8f1ff] text-[#08304c] dark:bg-[#12244f] dark:text-yellow-400">
                                        <?= htmlspecialchars($tipoNombre) ?> • Gestión <?= (int)$adm['gestion'] ?>
                                    </span>
                                    <?php if ($posterUrl): ?>
                                        <button type="button"
                                                onclick="openSingleImage('<?= htmlspecialchars($posterUrl) ?>', '<?= htmlspecialchars(addslashes($tipoNombre . ' ' . $adm['gestion'])) ?>')"
                                                class="font-mono text-xs font-semibold text-[#2862d7] dark:text-[#85a6e9] hover:underline cursor-pointer">
                                            Ver Afiche Oficial ↗
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <h4 class="font-heading font-bold text-sm uppercase tracking-wider text-slate-500 dark:text-[#abaebb] mt-3 mb-1">Descripción del Proceso</h4>
                                <div class="text-sm text-[#2c3444] dark:text-[#c7c9d1]">
                                    <?= renderRichText($adm['proc_adm'] ?? '') ?>
                                </div>

                                <h4 class="font-heading font-bold text-sm uppercase tracking-wider text-slate-500 dark:text-[#abaebb] mt-4 mb-1">Requisitos de Inscripción</h4>
                                <div class="text-sm text-[#2c3444] dark:text-[#c7c9d1]">
                                    <?= renderRichText($adm['req_adm'] ?? '') ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- B. MODALIDADES DE TITULACIÓN + ENLACE DIRECTO A REGLAMENTOS PDF (#titulacion) -->
        <div id="titulacion" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-7 rounded-2xl p-6 sm:p-8 bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540] shadow-card-light dark:shadow-card-dark">
                <span class="font-mono text-xs font-semibold uppercase tracking-widest text-[#2862d7] dark:text-[#85a6e9]">
                    Graduación Profesional
                </span>
                <h2 class="font-heading text-2xl sm:text-3xl font-bold text-[#08304c] dark:text-white mt-1 mb-4">
                    Modalidades de Titulación
                </h2>

                <!-- Las 5 modalidades oficiales del Tríptico -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5">
                    <?php foreach (['Trabajo Dirigido', 'Proyecto de Grado', 'Tesis de Grado', 'Excelencia Académica', 'Vía Diplomado'] as $modTit): ?>
                        <div class="p-3.5 rounded-xl bg-[#f4f7fb] dark:bg-[#12244f] border border-[#dbe3ef] dark:border-[#24375a] flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-[#2862d7] dark:bg-yellow-400"></span>
                            <span class="font-heading font-bold text-sm text-[#08304c] dark:text-white"><?= htmlspecialchars($modTit) ?></span>
                        </div>
                    <?php endforeach; ?>
                    <div class="p-3.5 rounded-xl bg-[#f4f7fb] dark:bg-[#151e32] border border-[#dbe3ef] dark:border-[#172540] flex items-center gap-2.5">
                        <span class="font-mono text-xs text-slate-600 dark:text-[#c7c9d1]">Técnico Superior: <strong>Proyecto de Grado Técnico o Tesina</strong></span>
                    </div>
                </div>

                <?php if (!empty($info['texto_titulacion'])): ?>
                    <div class="text-sm text-[#2c3444] dark:text-[#c7c9d1] pt-3 border-t border-slate-100 dark:border-[#151e32]">
                        <?= renderRichText($info['texto_titulacion']) ?>
                    </div>
                <?php endif; ?>

                <!-- Conexión directa a los Reglamentos de Titulación en PDF -->
                <div class="mt-6 pt-4 border-t border-[#dbe3ef] dark:border-[#172540]">
                    <p class="font-mono text-xs uppercase tracking-wider text-slate-500 dark:text-[#abaebb] mb-3">
                        Reglamentos Oficiales de Titulación disponibles para consulta y descarga:
                    </p>
                    <?php if (!empty($reglamentosTitulacion)): ?>
                        <div class="flex flex-wrap gap-2.5">
                            <?php foreach ($reglamentosTitulacion as $reg): ?>
                                <button type="button"
                                        onclick="openPdfModal('/uploads/pdf/documents/<?= htmlspecialchars($reg['archivo_pdf']) ?>', '<?= htmlspecialchars(addslashes($reg['titulo'])) ?>', 'Reglamento de Titulación')"
                                        class="px-4 py-2 rounded-full text-xs font-semibold bg-[#08304c] text-white hover:bg-blue-900 dark:bg-white dark:text-[#0b0c0e] transition-colors inline-flex items-center gap-2 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span><?= htmlspecialchars($reg['titulo']) ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <a href="/documentos?categoria=reglamento_titulacion"
                           class="inline-flex items-center gap-1.5 font-mono text-xs font-semibold text-[#2862d7] dark:text-[#85a6e9] hover:underline">
                            Ir al Repositorio de Reglamentos de Titulación →
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- C. BECAS Y BENEFICIOS ESTUDIANTILES (#becas — Datos Oficiales del Tríptico) -->
            <div id="becas" class="lg:col-span-5 rounded-2xl p-6 sm:p-8 bg-[#ebf3ff] dark:bg-[#12244f] border border-blue-300 dark:border-[#24375a] shadow-card-light dark:shadow-card-dark">
                <span class="font-mono text-xs font-semibold uppercase tracking-widest text-[#1d4ed8] dark:text-yellow-400">
                    Bienestar Estudiantil UNSXX
                </span>
                <h2 class="font-heading text-2xl sm:text-3xl font-bold text-[#08304c] dark:text-white mt-1 mb-4">
                    Becas y Beneficios
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-[#c7c9d1] mb-5">
                    Los estudiantes de la Carrera de Ingeniería Informática tienen acceso a las siguientes becas institucionales de la Universidad Nacional "Siglo XX":
                </p>

                <ul class="space-y-3">
                    <?php
                    $becasOficiales = [
                        ['nombre' => 'Beca Auxiliar de Docencia', 'desc' => 'Apoyo académico remunerado en asignaturas y laboratorios de computación.'],
                        ['nombre' => 'Beca Trabajo Tesista', 'desc' => 'Incentivo para el desarrollo de proyectos de investigación y graduación.'],
                        ['nombre' => 'Beca Comedor', 'desc' => 'Alimentación universitaria diaria para estudiantes destacados y de provincia.'],
                        ['nombre' => 'Beca Investigación', 'desc' => 'Participación en proyectos científicos a través del Instituto IIDAI.'],
                        ['nombre' => 'Beca Albergue', 'desc' => 'Residencia estudiantil universitaria para estudiantes del interior del país.'],
                    ];
                    ?>
                    <?php foreach ($becasOficiales as $b): ?>
                        <li class="p-3.5 rounded-xl bg-white dark:bg-[#0d172b] border border-blue-200/80 dark:border-[#172540]">
                            <p class="font-heading font-bold text-sm text-[#08304c] dark:text-white">• <?= htmlspecialchars($b['nombre']) ?></p>
                            <p class="text-xs text-slate-500 dark:text-[#abaebb] mt-0.5"><?= htmlspecialchars($b['desc']) ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- D. UBICACIÓN Y CONTACTOS (#ubicacion) -->
        <div id="ubicacion" class="rounded-2xl p-6 sm:p-8 bg-white dark:bg-[#0d172b] border border-[#dbe3ef] dark:border-[#172540]">
            <h2 class="font-heading text-2xl font-bold text-[#08304c] dark:text-white mb-4">Ubicación y Contactos Oficiales</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div>
                    <p class="font-mono text-xs uppercase text-[#2862d7] dark:text-[#85a6e9] mb-1">Dirección de Carrera</p>
                    <p class="font-bold text-[#08304c] dark:text-white">Ingeniería Informática — UNSXX</p>
                    <p class="text-xs text-slate-600 dark:text-[#c7c9d1] mt-1">
                        Área Tecnología - Campus María Barzola (Carretera a Catavi). Dirección de Carrera - Edificio Bloque A (Primer Piso).
                    </p>
                </div>
                <div>
                    <p class="font-mono text-xs uppercase text-[#2862d7] dark:text-[#85a6e9] mb-1">Edificio Central UNSXX</p>
                    <p class="font-bold text-[#08304c] dark:text-white">Universidad Nacional "Siglo XX"</p>
                    <p class="text-xs text-slate-600 dark:text-[#c7c9d1] mt-1">
                        Llallagua - Calle Campero N°36. Edificio de la Transparencia. Tel: 02-5820222 • https://unsxx.bo
                    </p>
                </div>
                <div>
                    <p class="font-mono text-xs uppercase text-[#2862d7] dark:text-[#85a6e9] mb-1">Teléfonos y Correo</p>
                    <p class="font-mono text-xs text-[#08304c] dark:text-white">contactos@informatica-unsxx.net</p>
                    <p class="font-mono text-xs text-slate-600 dark:text-[#c7c9d1] mt-1">
                        Dirección: 60492494 - 72457622<br>
                        Coord. Académica: 74259358
                    </p>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
    function toggleCurriculumYear(year) {
        const body = document.getElementById('yearBody-' + year);
        const chevron = document.getElementById('yearChevron-' + year);
        const header = document.getElementById('yearHeader-' + year);
        const isHidden = body.classList.contains('hidden');

        if (isHidden) {
            body.classList.remove('hidden');
            chevron.classList.add('rotate-180');
            header.className = 'w-full px-6 py-4 flex items-center justify-between gap-4 text-left transition-colors cursor-pointer bg-[#2862d7] text-white dark:bg-[#12244f]';
        } else {
            body.classList.add('hidden');
            chevron.classList.remove('rotate-180');
            header.className = 'w-full px-6 py-4 flex items-center justify-between gap-4 text-left transition-colors cursor-pointer bg-white hover:bg-[#f4f7fb] text-[#08304c] dark:bg-[#0d172b] dark:text-white dark:hover:bg-[#12244f]/60';
        }
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/public.php';
?>