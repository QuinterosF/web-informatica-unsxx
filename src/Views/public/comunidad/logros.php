<?php
/**
 * Vista: Logros y Reconocimientos (src/Views/public/comunidad/logros.php)
 * Variables inyectadas por HomeController::logros():
 * @var array $logros Registros activos de la tabla `logro` ordenados por fech_log DESC
 */

$listaLogros = $logros ?? [];
$totalLogros = count($listaLogros);

/**
 * Helper para dar estilo e icono según la posición obtenida
 */
if (!function_exists('obtenerEstiloPosicionLogro')) {
    function obtenerEstiloPosicionLogro(string $posicion): array {
        $posLower = mb_strtolower(trim($posicion), 'UTF-8');

        if (str_contains($posLower, '1er')) {
            return [
                'icon'       => 'fas fa-trophy',
                'icon_bg'    => 'bg-gradient-to-br from-institucional-gold to-amber-500 text-slate-900 shadow-[0_0_20px_rgba(234,179,8,0.45)]',
                'badge_cls'  => 'bg-amber-50 text-amber-900 border-amber-300',
                'top_bar'    => 'from-institucional-gold via-yellow-400 to-institucional-navy',
                'dot_cls'    => 'bg-institucional-gold ring-amber-200'
            ];
        }
        if (str_contains($posLower, '2do')) {
            return [
                'icon'       => 'fas fa-medal',
                'icon_bg'    => 'bg-gradient-to-br from-slate-300 to-slate-400 text-slate-900 shadow-md',
                'badge_cls'  => 'bg-slate-100 text-slate-800 border-slate-300',
                'top_bar'    => 'from-slate-400 via-institucional-navy to-blue-800',
                'dot_cls'    => 'bg-slate-400 ring-slate-200'
            ];
        }
        if (str_contains($posLower, '3er')) {
            return [
                'icon'       => 'fas fa-medal',
                'icon_bg'    => 'bg-gradient-to-br from-amber-600 to-amber-800 text-white shadow-md',
                'badge_cls'  => 'bg-orange-50 text-amber-900 border-orange-200',
                'top_bar'    => 'from-amber-600 via-institucional-navy to-blue-900',
                'dot_cls'    => 'bg-amber-600 ring-orange-200'
            ];
        }

        return [
            'icon'       => 'fas fa-award',
            'icon_bg'    => 'bg-gradient-to-br from-institucional-navy to-blue-800 text-institucional-gold shadow-md',
            'badge_cls'  => 'bg-blue-50 text-institucional-navy border-blue-200',
            'top_bar'    => 'from-institucional-navy via-blue-600 to-institucional-gold',
            'dot_cls'    => 'bg-institucional-navy ring-blue-200'
        ];
    }
}

/**
 * Helper para verificar foto del logro en /uploads/img/logros/
 */
if (!function_exists('obtenerUrlFotoLogro')) {
    function obtenerUrlFotoLogro(?string $archivo): ?string {
        $limpio = trim((string) $archivo);
        if ($limpio === '' || strtolower($limpio) === 'default.png') {
            return null;
        }
        return '/uploads/img/logros/' . rawurlencode($limpio);
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
                    <span class="font-code text-xs font-medium text-institucional-gold uppercase tracking-wider">&lt;/&gt; logros y reconocimientos</span>
                </div>

                <h1 class="font-display text-3xl sm:text-5xl font-bold tracking-tight leading-[1.13] text-white">
                    Logros <span class="text-institucional-gold">Institucionales</span>
                </h1>
                
                <p class="mt-4 text-base text-slate-200 font-light leading-relaxed">
                    Trayectoria de premios, distinciones académicas y certámenes científicos nacionales obtenidos por docentes y estudiantes de Ingeniería Informática.
                </p>
            </div>

            <!-- Indicador Estadístico Compacto -->
            <div class="relative self-start lg:self-center w-full sm:w-auto">
                <div class="bg-[#0f1f4d]/95 border border-institucional-gold/40 rounded-xl p-5 flex items-center gap-5 backdrop-blur-xl shadow-2xl">
                    <div class="w-13 h-13 p-3.5 rounded-xl bg-institucional-gold text-slate-900 flex items-center justify-center text-xl flex-shrink-0 shadow-[0_0_20px_rgba(234,179,8,0.4)]">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="pr-2">
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="font-display font-extrabold text-2xl sm:text-3xl text-white"><?= $totalLogros ?></span>
                            <span class="text-s text-slate-200 font-medium">Reconocimientos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CONTENIDO PRINCIPAL: Línea de Tiempo de Logros -->
<section class="relative py-12 sm:py-16 overflow-hidden">
    <!-- Luces Aurora difuminadas -->
    <div class="absolute top-10 left-1/4 -translate-x-1/2 w-[36rem] h-[24rem] bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-32 right-8 w-[28rem] h-[22rem] bg-institucional-gold/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-20 left-1/3 w-[34rem] h-[24rem] bg-institucional-navy/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <?php if (!empty($listaLogros)): ?>
            <!-- Contenedor de Línea de Tiempo -->
            <div class="relative">
                <!-- Eje vertical central en desktop / izquierdo en móvil -->
                <div class="absolute left-5 md:left-1/2 top-4 bottom-4 w-0.5 md:-translate-x-1/2 bg-gradient-to-b from-institucional-gold via-institucional-navy to-institucional-gold/40"></div>

                <div class="space-y-10">
                    <?php foreach ($listaLogros as $index => $logro): ?>
                        <?php
                            $posicion   = trim((string) ($logro['posicion'] ?? 'Reconocimiento'));
                            $titulo     = trim((string) ($logro['nom_log'] ?? 'Reconocimiento Académico'));
                            $categoria  = !empty($logro['categoria_logro']) ? trim((string) $logro['categoria_logro']) : null;
                            $lugar      = !empty($logro['lugar_log']) ? trim((string) $logro['lugar_log']) : null;
                            $fechaRaw   = (string) ($logro['fech_log'] ?? '');
                            $timestamp  = $fechaRaw !== '' ? strtotime($fechaRaw) : false;
                            $anio       = $timestamp ? date('Y', $timestamp) : '—';
                            $urlFoto    = obtenerUrlFotoLogro($logro['foto_logro'] ?? null);
                            $estilo     = obtenerEstiloPosicionLogro($posicion);
                            $esPar      = ($index % 2 === 0);
                        ?>

                        <div class="relative flex flex-col md:flex-row items-start <?= $esPar ? 'md:flex-row' : 'md:flex-row-reverse' ?> group">
                            
                            <!-- Nodo circular sobre la línea de tiempo -->
                            <div class="absolute left-5 md:left-1/2 top-7 -translate-x-1/2 z-20 flex items-center justify-center">
                                <span class="w-4 h-4 rounded-full <?= $estilo['dot_cls'] ?> ring-4 shadow-md group-hover:scale-125 transition-transform"></span>
                            </div>

                            <!-- Mitad de la tarjeta (Alterna izquierda/derecha en desktop) -->
                            <div class="w-full md:w-1/2 pl-14 md:pl-0 <?= $esPar ? 'md:pr-10' : 'md:pl-10' ?>">
                                <article class="tech-card relative overflow-hidden p-6">
                                    <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r <?= $estilo['top_bar'] ?>"></div>

                                    <!-- Cabecera de la tarjeta: Trofeo + Año + Posición -->
                                    <div class="flex items-start justify-between gap-4 mb-4">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-12 h-12 rounded-xl <?= $estilo['icon_bg'] ?> flex items-center justify-center text-xl flex-shrink-0">
                                                <i class="<?= $estilo['icon'] ?>"></i>
                                            </div>
                                            <div>
                                                <span class="font-code inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold border <?= $estilo['badge_cls'] ?>">
                                                    <?= htmlspecialchars($posicion) ?>
                                                </span>
                                                <?php if ($categoria !== null): ?>
                                                    <span class="block text-xs text-slate-500 font-medium mt-1">
                                                        <?= htmlspecialchars($categoria) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Año destacado en estilo consola -->
                                        <span class="font-display text-2xl font-extrabold text-institucional-navy bg-blue-50/90 border border-blue-200/80 px-3.5 py-1 rounded-xl">
                                            <?= htmlspecialchars($anio) ?>
                                        </span>
                                    </div>

                                    <!-- Nombre del Evento / Logro -->
                                    <h2 class="font-display text-lg sm:text-xl font-bold text-slate-900 leading-snug mb-3">
                                        <?= htmlspecialchars($titulo) ?>
                                    </h2>

                                    <!-- Fotografía opcional del logro -->
                                    <?php if ($urlFoto !== null): ?>
                                        <div class="mt-3 mb-4 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 relative group/img">
                                            <img src="<?= htmlspecialchars($urlFoto) ?>" 
                                                 alt="<?= htmlspecialchars($titulo) ?>" 
                                                 loading="lazy"
                                                 class="w-full h-48 object-cover group-hover/img:scale-105 transition-transform duration-300 cursor-pointer btn-ver-foto-logro"
                                                 data-foto="<?= htmlspecialchars($urlFoto) ?>"
                                                 data-titulo="<?= htmlspecialchars($titulo) ?>"
                                                 data-posicion="<?= htmlspecialchars($posicion . ' · ' . $anio) ?>"
                                                 onerror="this.parentElement.classList.add('hidden');">
                                        </div>
                                    <?php endif; ?>

                                    <!-- Pie de tarjeta: Sede / Ciudad -->
                                    <div class="pt-3 border-t border-slate-200/80 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500">
                                        <?php if ($lugar !== null): ?>
                                            <span class="inline-flex items-center gap-1.5 font-medium text-slate-600">
                                                <i class="fas fa-location-dot text-institucional-gold"></i>
                                                <?= htmlspecialchars($lugar) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5">
                                                <i class="fas fa-university text-institucional-navy"></i>
                                                Ingeniería Informática · UNSXX
                                            </span>
                                        <?php endif; ?>

                                        <?php if ($urlFoto !== null): ?>
                                            <button type="button"
                                                    class="btn-ver-foto-logro inline-flex items-center gap-1.5 font-semibold text-institucional-navy hover:text-yellow-600 transition-colors"
                                                    data-foto="<?= htmlspecialchars($urlFoto) ?>"
                                                    data-titulo="<?= htmlspecialchars($titulo) ?>"
                                                    data-posicion="<?= htmlspecialchars($posicion . ' · ' . $anio) ?>">
                                                <i class="fas fa-expand text-[10px]"></i> Ver fotografía
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="code-window-panel p-10 text-center text-slate-500">
                No hay logros institucionales registrados en este momento.
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- MODAL LIGHTBOX PARA FOTOGRAFÍA DE LOGRO -->
<div id="modal-foto-logro" class="fixed inset-0 z-50 hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-200">
    <div id="modal-foto-logro-card" class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-2xl w-full overflow-hidden transform scale-95 transition-transform duration-200">
        <div class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-3.5 border-b-2 border-institucional-gold flex items-center justify-between">
            <span id="modal-logro-posicion" class="font-code text-xs text-institucional-gold font-medium uppercase tracking-wider"></span>
            <button type="button" id="btn-cerrar-modal-logro" class="text-slate-300 hover:text-institucional-gold transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="p-6 text-center">
            <img id="modal-logro-img" src="" alt="Fotografía del Logro" class="max-h-[65vh] w-auto mx-auto rounded-xl border border-slate-200 shadow-sm object-contain">
            <h3 id="modal-logro-titulo" class="font-display text-lg sm:text-xl font-bold text-slate-900 mt-4"></h3>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('modal-foto-logro');
        const card = document.getElementById('modal-foto-logro-card');
        const imgEl = document.getElementById('modal-logro-img');
        const tituloEl = document.getElementById('modal-logro-titulo');
        const posEl = document.getElementById('modal-logro-posicion');
        const btnCerrar = document.getElementById('btn-cerrar-modal-logro');

        document.querySelectorAll('.btn-ver-foto-logro').forEach((btn) => {
            btn.addEventListener('click', () => {
                imgEl.src = btn.getAttribute('data-foto') || '';
                tituloEl.textContent = btn.getAttribute('data-titulo') || '';
                posEl.textContent = '</> ' + (btn.getAttribute('data-posicion') || 'Logro');

                modal.classList.remove('hidden');
                void modal.offsetWidth;
                modal.classList.remove('opacity-0');
                card.classList.remove('scale-95');
            });
        });

        const cerrar = () => {
            modal.classList.add('opacity-0');
            card.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 200);
        };

        btnCerrar?.addEventListener('click', cerrar);
        modal?.addEventListener('click', (e) => {
            if (e.target === modal) cerrar();
        });
    });
</script>