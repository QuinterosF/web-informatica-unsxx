<?php
/**
 * Vista: Modalidades de Titulación (src/Views/public/carrera/titulacion.php)
 * Variables inyectadas por HomeController::titulacion():
 * @var array $documents Documentos oficiales con categoria = 'reglamento_titulacion'
 */

$reglamentos = $documents ?? [];

/**
 *Las 5 Modalidades de Titulación Oficiales (Tríptico Institucional)
 */
$modalidadesTitulacion = [
    [
        'numero'      => '01',
        'titulo'      => 'Tesis de Grado',
        'etiqueta'    => 'Investigación Científica',
        'icono'       => 'fas fa-microscope',
        'enfoque'     => 'Generación de nuevo conocimiento científico o teórico en Ciencias de la Computación.',
        'descripcion' => 'Trabajo de investigación inédito y riguroso que aborda un problema científico o tecnológico mediante el método científico, aportando modelos, algoritmos o marcos teóricos validables.',
        'aspectos'    => [
            'Enfoque en investigación pura o aplicada',
            'Defensa pública ante tribunal examinador',
            'Aporte teórico y experimental documentado'
        ],
        'card_cls'    => 'tech-card',
        'bar_cls'     => 'from-institucional-navy via-blue-600 to-institucional-gold',
        'badge_cls'   => 'bg-blue-50 text-institucional-navy border-blue-200'
    ],
    [
        'numero'      => '02',
        'titulo'      => 'Proyecto de Grado',
        'etiqueta'    => 'Desarrollo e Ingeniería',
        'icono'       => 'fas fa-laptop-code',
        'enfoque'     => 'Diseño e implementación de soluciones tecnológicas de software, hardware o redes.',
        'descripcion' => 'Aplicación práctica de la ingeniería informática para resolver una necesidad concreta mediante el análisis, diseño, construcción, pruebas y despliegue de un producto tecnológico completo.',
        'aspectos'    => [
            'Soluciones en Seguridad Informática o Computación Móvil',
            'Metodologías ágiles o clásicas de ingeniería de software',
            'Producto funcional validado con pruebas técnicas'
        ],
        'card_cls'    => 'tech-card-spotlight',
        'bar_cls'     => 'from-institucional-gold via-yellow-400 to-institucional-navy',
        'badge_cls'   => 'bg-amber-50 text-amber-900 border-amber-300'
    ],
    [
        'numero'      => '03',
        'titulo'      => 'Trabajo Dirigido',
        'etiqueta'    => 'Interacción Institucional',
        'icono'       => 'fas fa-building-shield',
        'enfoque'     => 'Práctica profesional supervisada en instituciones públicas, privadas o centros de investigación.',
        'descripcion' => 'Desarrollo e implementación de un sistema, infraestructura o auditoría técnica dentro de una entidad receptora bajo convenio interinstitucional y supervisión académica conjunta.',
        'aspectos'    => [
            'Ejecución directa en una institución o empresa',
            'Supervisión de tutor académico y guía institucional',
            'Impacto social y operativo verificable en la entidad'
        ],
        'card_cls'    => 'tech-card',
        'bar_cls'     => 'from-institucional-navy via-blue-600 to-institucional-gold',
        'badge_cls'   => 'bg-blue-50 text-institucional-navy border-blue-200'
    ],
    [
        'numero'      => '04',
        'titulo'      => 'Excelencia Académica',
        'etiqueta'    => 'Mérito Estudiantil',
        'icono'       => 'fas fa-award',
        'enfoque'     => 'Titulación directa en reconocimiento al alto rendimiento académico durante toda la carrera.',
        'descripcion' => 'Modalidad de graduación directa que premia la constancia y el desempeño sobresaliente del estudiante que ha aprobado la totalidad del plan de estudios sin reprobaciones y con promedio distinguido.',
        'aspectos'    => [
            'Aprobación en primera instancia de los 5 años del plan de estudios',
            'Promedio general igual o superior al exigido por reglamento',
            'Eximición de modalidad escrita tradicional por mérito'
        ],
        'card_cls'    => 'tech-card',
        'bar_cls'     => 'from-institucional-gold via-yellow-400 to-institucional-navy',
        'badge_cls'   => 'bg-amber-50 text-amber-900 border-amber-300'
    ],
    [
        'numero'      => '05',
        'titulo'      => 'Vía Diplomado',
        'etiqueta'    => 'Doble titulación',
        'icono'       => 'fas fa-user-graduate',
        'enfoque'     => 'Graduación mediante programas de formación continua y especialización posgradual.',
        'descripcion' => 'Permite al egresado profundizar conocimientos en áreas tecnológicas emergentes cursando y aprobando módulos especializados de diplomado junto con la presentación de una monografía.',
        'aspectos'    => [
            'Módulos de especialización tecnológica actualizada',
            'Elaboración y presentación de monografía aplicada',
            'Doble titulación: titulación de grado y formación posgradual'
        ],
        'card_cls'    => 'tech-card',
        'bar_cls'     => 'from-institucional-navy via-blue-600 to-institucional-gold',
        'badge_cls'   => 'bg-blue-50 text-institucional-navy border-blue-200'
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
                    <span class="font-code text-xs font-medium text-institucional-gold uppercase tracking-wider">&lt;/&gt; modalidades de titulación</span>
                </div>

                <h1 class="font-display text-3xl sm:text-5xl font-bold tracking-tight leading-[1.13] text-white">
                    Modalidades de <span class="text-institucional-gold">Titulación</span>
                </h1>
                
                <p class="mt-4 text-base text-slate-200 font-light leading-relaxed">
                    Opciones académicas y reglamentarias vigentes para la obtención del Grado de Licenciatura en Ingeniería Informática en la Universidad Nacional "Siglo XX".
                </p>
            </div>

            <!-- Indicador Estadístico Compacto -->
            <div class="relative self-start lg:self-center w-full sm:w-auto">
                <div class="bg-[#0f1f4d]/95 border border-institucional-gold/40 rounded-xl p-5 flex items-center gap-5 backdrop-blur-xl shadow-2xl">
                    <div class="w-13 h-13 p-3.5 rounded-xl bg-institucional-gold text-slate-900 flex items-center justify-center text-xl flex-shrink-0 shadow-[0_0_20px_rgba(234,179,8,0.4)]">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="pr-2">
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="font-display font-extrabold text-2xl sm:text-3xl text-white"><?= count($modalidadesTitulacion) ?></span>
                            <span class="text-s text-slate-200 font-medium">Modalidades</span>
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
    <div class="absolute top-32 right-8 w-[28rem] h-[22rem] bg-institucional-gold/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-20 left-1/3 w-[34rem] h-[24rem] bg-institucional-navy/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-16">

        <!-- =================================================================
             BLOQUE 1: LAS 5 MODALIDADES DE TITULACIÓN (ESTÁTICO DEL TRÍPTICO)
             ================================================================= -->
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-200/90 pb-4">
                <div>
                    <span class="font-code text-xs font-medium uppercase tracking-wider text-institucional-navy bg-blue-50 px-3 py-1 rounded-full border border-blue-200/80">
                        &lt;/&gt; Vías de Graduación Universitaria
                    </span>
                    <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900 mt-2.5">
                        Opciones de Titulación Vigentes
                    </h2>
                </div>
                <p class="text-sm text-slate-600 max-w-md">
                    El estudiante que culmina el plan de estudios puede optar por cualquiera de las cinco modalidades reconocidas por el Sistema de la Universidad Boliviana.
                </p>
            </div>

            <!-- Grid de Modalidades (3 arriba y 2 centradas abajo en escritorio) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
                <?php foreach ($modalidadesTitulacion as $idx => $mod): ?>
                    <?php
                        // Las primeras 3 ocupan 2 columnas cada una (3x2=6); las últimas 2 ocupan 3 columnas cada una (2x3=6)
                        $colSpan = $idx < 3 ? 'lg:col-span-2' : 'lg:col-span-3';
                    ?>
                    <article class="<?= $mod['card_cls'] ?> <?= $colSpan ?> p-6 sm:p-7 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r <?= $mod['bar_cls'] ?>"></div>

                        <div>
                            <!-- Cabecera de la Modalidad -->
                            <div class="flex items-center justify-between gap-3 mb-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-institucional-navy text-institucional-gold flex items-center justify-center text-xl shadow-md group-hover:scale-105 transition-transform">
                                        <i class="<?= $mod['icono'] ?>"></i>
                                    </div>
                                    <span class="font-code text-xs font-bold text-slate-400">
                                        MOD // <?= htmlspecialchars($mod['numero']) ?>
                                    </span>
                                </div>

                                <span class="font-code px-3 py-0.5 rounded-full text-[11px] font-medium border <?= $mod['badge_cls'] ?>">
                                    <?= htmlspecialchars($mod['etiqueta']) ?>
                                </span>
                            </div>

                            <h3 class="font-display text-xl sm:text-2xl font-bold text-slate-900 mb-2 group-hover:text-institucional-navy transition-colors">
                                <?= htmlspecialchars($mod['titulo']) ?>
                            </h3>

                            <p class="text-xs font-semibold text-institucional-navy mb-3">
                                <?= htmlspecialchars($mod['enfoque']) ?>
                            </p>

                            <p class="text-sm text-slate-600 leading-relaxed mb-6">
                                <?= htmlspecialchars($mod['descripcion']) ?>
                            </p>
                        </div>

                        <!-- Puntos Clave de la Modalidad -->
                        <div class="bg-slate-900/[0.03] rounded-xl p-4 border border-slate-200/80 space-y-2">
                            <span class="font-code text-[10px] uppercase tracking-wider text-slate-400 font-bold block mb-1">
                                Características principales
                            </span>
                            <?php foreach ($mod['aspectos'] as $aspecto): ?>
                                <div class="flex items-start gap-2 text-xs text-slate-700">
                                    <i class="fas fa-check-circle text-institucional-gold mt-0.5"></i>
                                    <span><?= htmlspecialchars($aspecto) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>


        <!-- =================================================================
             BLOQUE 2: REGLAMENTOS DE TITULACIÓN EN PDF (DINÁMICO DE LA BD)
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
                        &lt;/&gt; Reglamentos Específicos de Titulación (PDF)
                    </span>
                </div>
                <span class="font-code text-xs text-slate-200 flex items-center gap-2">
                    <i class="fas fa-file-pdf text-institucional-gold"></i> Normativa de Graduación Vigente
                </span>
            </div>

            <div class="p-6 sm:p-8 lg:p-10">
                <?php if (!empty($reglamentos)): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($reglamentos as $doc): ?>
                            <?php
                                $titulo     = trim((string) ($doc['titulo'] ?? 'Reglamento de Titulación'));
                                $gestion    = !empty($doc['gestion']) ? trim((string) $doc['gestion']) : null;
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
                                            <span class="font-code inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-900 border border-amber-300">
                                                <i class="fas fa-graduation-cap text-[10px]"></i> Reglamento de Titulación
                                            </span>
                                            <?php if ($gestion !== null): ?>
                                                <span class="font-code px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                    Vigente desde la gestión <?= htmlspecialchars($gestion) ?>
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
                        No hay reglamentos de titulación en PDF publicados en este momento.
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
            <iframe id="iframe-visor-pdf" src="" title="Visor PDF de Reglamento" class="w-full h-full border-0"></iframe>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modalPdf = document.getElementById('modal-visor-pdf');
        const cardPdf = document.getElementById('modal-visor-pdf-card');
        const iframePdf = document.getElementById('iframe-visor-pdf');
        const tituloPdf = document.getElementById('modal-pdf-titulo');
        const linkPestana = document.getElementById('modal-pdf-nueva-pestana');
        const btnCerrarPdf = document.getElementById('btn-cerrar-visor-pdf');

        document.querySelectorAll('.btn-previsualizar-pdf').forEach((btn) => {
            btn.addEventListener('click', () => {
                const url = btn.getAttribute('data-pdf') || '';
                const titulo = btn.getAttribute('data-titulo') || 'Reglamento PDF';

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