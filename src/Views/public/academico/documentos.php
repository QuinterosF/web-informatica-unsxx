<?php
/**
 * Vista: Reglamentos y Documentos Oficiales (src/Views/public/academico/documentos.php)
 * Variables inyectadas por HomeController::documentos():
 * @var array $documents Registros activos de la tabla `documento_oficial`
 */

$listaDocumentos = $documents ?? [];
$totalDocumentos = count($listaDocumentos);

/**
 * Helper para traducir la categoría de la BD a una etiqueta institucional legible
 */
if (!function_exists('obtenerMetaCategoriaDocumento')) {
    function obtenerMetaCategoriaDocumento(string $categoria): array {
        return match ($categoria) {
            'reglamento_interno'    => [
                'label' => 'Reglamento Interno',
                'badge' => 'bg-blue-50 text-institucional-navy border-blue-200',
                'icon'  => 'fas fa-scale-balanced'
            ],
            'reglamento_titulacion' => [
                'label' => 'Reglamento de Titulación',
                'badge' => 'bg-amber-50 text-amber-900 border-amber-300',
                'icon'  => 'fas fa-graduation-cap'
            ],
            'plan_estudios'         => [
                'label' => 'Documento Curricular',
                'badge' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                'icon'  => 'fas fa-book'
            ],
            default                 => [
                'label' => 'Documento Oficial',
                'badge' => 'bg-slate-100 text-slate-700 border-slate-300',
                'icon'  => 'fas fa-file-shield'
            ],
        };
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
                    <span class="font-code text-xs font-medium text-institucional-gold uppercase tracking-wider">&lt;/&gt; reglamentos y documentos</span>
                </div>

                <h1 class="font-display text-3xl sm:text-5xl font-bold tracking-tight leading-[1.13] text-white">
                    Reglamentos y <span class="text-institucional-gold">Documentos</span>
                </h1>
                
                <p class="mt-4 text-base text-slate-200 font-light leading-relaxed">
                    Repositorio oficial de reglamentos internos, normativas universitarias y documentación institucional vigente de la Carrera de Ingeniería Informática.
                </p>
            </div>

            <!-- Indicador Estadístico Compacto -->
            <div class="relative self-start lg:self-center w-full sm:w-auto">
                <div class="bg-[#0f1f4d]/95 border border-institucional-gold/40 rounded-xl p-5 flex items-center gap-5 backdrop-blur-xl shadow-2xl">
                    <div class="w-13 h-13 p-3.5 rounded-xl bg-institucional-gold text-slate-900 flex items-center justify-center text-xl flex-shrink-0 shadow-[0_0_20px_rgba(234,179,8,0.4)]">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="pr-2">
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="font-display font-extrabold text-2xl sm:text-3xl text-white"><?= $totalDocumentos ?></span>
                            <span class="text-s text-slate-200 font-medium">Documentos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CONTENIDO PRINCIPAL: Repositorio de Documentos Oficiales -->
<section class="relative py-12 sm:py-16 overflow-hidden">
    <!-- Luces Aurora difuminadas -->
    <div class="absolute top-10 left-1/4 -translate-x-1/2 w-[36rem] h-[24rem] bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-32 right-8 w-[28rem] h-[22rem] bg-institucional-gold/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-20 left-1/3 w-[34rem] h-[24rem] bg-institucional-navy/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="code-window-panel overflow-hidden">
            <!-- Barra Superior Estilo Consola -->
            <div class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-3.5 border-b border-institucional-gold/40 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                    </div>
                    <span class="font-code text-[13px] text-institucional-gold font-medium tracking-normal pl-2 border-l border-white/20">
                        &lt;/&gt; Repositorio Normativo Institucional
                    </span>
                </div>
                <span class="font-code text-xs text-slate-200 flex items-center gap-2">
                    <i class="fas fa-circle-check text-emerald-400"></i> Documentación Oficial en PDF
                </span>
            </div>

            <div class="p-6 sm:p-8 lg:p-10">
                <?php if (!empty($listaDocumentos)): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($listaDocumentos as $doc): ?>
                            <?php
                                $titulo     = trim((string) ($doc['titulo'] ?? 'Documento Oficial'));
                                $categoria  = (string) ($doc['categoria'] ?? 'otro');
                                $gestion    = !empty($doc['gestion']) ? trim((string) $doc['gestion']) : null;
                                $archivoPdf = trim((string) ($doc['archivo_pdf'] ?? ''));
                                $urlPdf     = '/uploads/pdf/documents/' . rawurlencode($archivoPdf);
                                $metaCat    = obtenerMetaCategoriaDocumento($categoria);
                            ?>

                            <article class="tech-card flex flex-col justify-between relative overflow-hidden group">
                                <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-institucional-navy via-blue-600 to-institucional-gold"></div>

                                <div class="p-6 flex items-start gap-4">
                                    <!-- Icono PDF Institucional -->
                                    <div class="w-14 h-14 rounded-2xl bg-institucional-navy text-institucional-gold group-hover:bg-institucional-gold group-hover:text-slate-900 flex items-center justify-center text-2xl flex-shrink-0 shadow-md transition-colors">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <span class="font-code inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium border <?= $metaCat['badge'] ?>">
                                                <i class="<?= $metaCat['icon'] ?> text-[10px]"></i>
                                                <?= htmlspecialchars($metaCat['label']) ?>
                                            </span>

                                            <?php if ($gestion !== null): ?>
                                                <span class="font-code px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                    Vigente desde la Gestión <?= htmlspecialchars($gestion) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <h2 class="font-display text-lg sm:text-xl font-bold text-slate-900 leading-snug group-hover:text-institucional-navy transition-colors">
                                            <?= htmlspecialchars($titulo) ?>
                                        </h2>
                                    </div>
                                </div>

                                <!-- Acciones del Documento: Previsualizar en Modal / Abrir o Descargar -->
                                <div class="px-6 py-3.5 bg-slate-50/90 border-t border-slate-200/80 flex flex-wrap items-center justify-between gap-2">
                                    <span class="font-code text-[11px] text-slate-500 flex items-center gap-1.5">
                                        <i class="far fa-file-lines text-institucional-navy"></i> Formato PDF
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
                    <div class="text-center py-10 bg-white/60 rounded-xl border border-dashed border-slate-300 text-sm text-slate-500">
                        No hay reglamentos o documentos institucionales publicados en este momento.
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>

<!-- MODAL VISOR DE PDF INTEGRADO -->
<div id="modal-visor-pdf" class="fixed inset-0 z-50 hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 opacity-0 transition-opacity duration-200">
    <div id="modal-visor-pdf-card" class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-5xl w-full h-[88vh] flex flex-col overflow-hidden transform scale-95 transition-transform duration-200">
        <!-- Cabecera del Visor -->
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

        <!-- Iframe del PDF -->
        <div class="flex-grow bg-slate-100">
            <iframe id="iframe-visor-pdf" src="" title="Visor PDF Institucional" class="w-full h-full border-0"></iframe>
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