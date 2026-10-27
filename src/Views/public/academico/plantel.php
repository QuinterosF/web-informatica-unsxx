<?php
/**
 * Vista: Plantel Docente (src/Views/public/academico/plantel.php)
 * Variables inyectadas por HomeController::plantel():
 * @var array $academics ['categories' => [...], 'authorities' => [...], 'teachers' => [...], 'all' => [...]]
 */

$docentes = $academics['teachers'] ?? [];

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

if (!function_exists('obtenerEstiloCategoriaDocente')) {
    function obtenerEstiloCategoriaDocente(string $categoria): array {
        $catLower = mb_strtolower($categoria, 'UTF-8');
        if (str_contains($catLower, 'director')) {
            return [
                'badge_bg' => 'bg-amber-50 text-amber-900 border-amber-300/90',
                'top_bar'  => 'from-institucional-gold via-yellow-400 to-institucional-navy'
            ];
        }
        if (str_contains($catLower, 'coord')) {
            return [
                'badge_bg' => 'bg-blue-50 text-institucional-navy border-blue-200/90',
                'top_bar'  => 'from-institucional-navy via-blue-600 to-institucional-gold'
            ];
        }
        return [
            'badge_bg' => 'bg-slate-100 text-slate-700 border-slate-200/90',
            'top_bar'  => 'from-institucional-navy via-blue-700 to-institucional-navy'
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
                    <span class="font-code text-xs font-medium text-institucional-gold uppercase tracking-wider">&lt;/&gt; plantel docente</span>
                </div>

                <h1 class="font-display text-3xl sm:text-5xl font-bold tracking-tight leading-[1.13] text-white">
                    Plantel <span class="text-institucional-gold">Docente</span>
                </h1>
                
                <p class="mt-4 text-base text-slate-200 font-light leading-relaxed">
                    Docentes altamente capacitados, de un buen nivel académico, científico y técnico. Comprometidos con la formación de profesionales en Ciencias de la Computación, Seguridad Informática y Computación Móvil.
                </p>
            </div>

            <!-- Indicador Estadístico Estilo Consola -->
            <div class="relative self-start lg:self-center w-full sm:w-auto">
                <div class="bg-[#0f1f4d]/95 border border-institucional-gold/40 rounded-xl p-5 flex items-center gap-5 backdrop-blur-xl shadow-2xl">
                    <div class="w-13 h-13 p-3.5 rounded-xl bg-institucional-gold text-slate-900 flex items-center justify-center text-xl flex-shrink-0 shadow-[0_0_20px_rgba(234,179,8,0.4)]">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="pr-2">
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="font-display font-extrabold text-2xl sm:text-3xl text-white"><?= count($docentes) ?></span>
                            <span class="text-s text-slate-200 font-medium">Docentes</span>
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
    <div class="absolute top-10 left-1/4 -translate-x-1/2 w-[36rem] h-[24rem] bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-32 right-8 w-[28rem] h-[22rem] bg-institucional-gold/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-20 left-1/3 w-[34rem] h-[24rem] bg-institucional-navy/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-10">

        <!-- GRID DE TARJETAS DEL PLANTEL DOCENTE -->
        <?php if (!empty($docentes)): ?>
            <div id="grid-docentes" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($docentes as $doc): ?>
                    <?php
                        $nombre      = trim((string) ($doc['nom_acad'] ?? 'Docente'));
                        $categoria   = trim((string) ($doc['cat_nombre'] ?? 'Docente'));
                        $catId       = (int) ($doc['cat_acad'] ?? 0);
                        $descripcion = trim((string) ($doc['desc_acad'] ?? ''));
                        $textoDesc   = formatearTextoQuill($descripcion !== '' ? $descripcion : 'Docente de la Carrera de Ingeniería Informática - UNSXX.');
                        $celular     = !empty($doc['cel_acad']) ? trim((string) $doc['cel_acad']) : null;
                        $email       = !empty($doc['email_acad']) ? trim((string) $doc['email_acad']) : null;
                        $sitioWeb    = !empty($doc['sitio_web']) ? trim((string) $doc['sitio_web']) : null;
                        $urlFoto     = obtenerUrlFotoDocente($doc['foto_acad'] ?? null);
                        $iniciales   = obtenerInicialesDocente($nombre);
                        $estiloCat   = obtenerEstiloCategoriaDocente($categoria);

                        $hrefWhatsapp = null;
                        if ($celular !== null) {
                            $celLimpio = preg_replace('/[^0-9]/', '', $celular);
                            $codWa = (strlen($celLimpio) === 8) ? '591' . $celLimpio : $celLimpio;
                            $hrefWhatsapp = "https://wa.me/{$codWa}";
                        }

                        $modalDataAttrs = sprintf(
                            'data-nombre="%s" data-categoria="%s" data-descripcion="%s" data-foto="%s" data-iniciales="%s" data-email="%s" data-whatsapp="%s" data-cel="%s" data-web="%s"',
                            htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($categoria, ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($textoDesc, ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($urlFoto ?? '', ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($iniciales, ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($hrefWhatsapp ?? '', ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($celular ?? '', ENT_QUOTES, 'UTF-8'),
                            htmlspecialchars($sitioWeb ?? '', ENT_QUOTES, 'UTF-8')
                        );
                    ?>

                    <article class="tarjeta-docente tech-card flex flex-col justify-between relative overflow-hidden group"
                             data-categoria="<?= $catId ?>">
                        
                        <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r <?= $estiloCat['top_bar'] ?>"></div>

                        <div class="p-6 flex-grow flex flex-col">
                            <!-- Cabecera: Foto limpia + Categoría + Nombre -->
                            <div class="flex items-start gap-4 mb-5">
                                <button type="button" 
                                        class="btn-abrir-modal-docente relative flex-shrink-0 focus:outline-none"
                                        title="Ver ficha completa de <?= htmlspecialchars($nombre) ?>"
                                        <?= $modalDataAttrs ?>>
                                    <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-institucional-navy/15 group-hover:border-institucional-gold shadow-md bg-gradient-to-br from-institucional-navy to-[#172554] flex items-center justify-center transition-colors">
                                        <?php if ($urlFoto !== null): ?>
                                            <img src="<?= htmlspecialchars($urlFoto) ?>" 
                                                 alt="<?= htmlspecialchars($nombre) ?>" 
                                                 loading="lazy"
                                                 class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300"
                                                 onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
                                            <span class="hidden font-display font-bold text-xl text-institucional-gold tracking-wider">
                                                <?= htmlspecialchars($iniciales) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="font-display font-bold text-xl text-institucional-gold tracking-wider">
                                                <?= htmlspecialchars($iniciales) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </button>

                                <div class="min-w-0 flex-1">
                                    <span class="font-code inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium border <?= $estiloCat['badge_bg'] ?> mb-2">
                                        <?= htmlspecialchars($categoria) ?>
                                    </span>
                                    <h2 class="font-display text-lg font-bold text-slate-900 leading-snug group-hover:text-institucional-navy transition-colors">
                                        <button type="button" class="btn-abrir-modal-docente text-left hover:underline focus:outline-none" <?= $modalDataAttrs ?>>
                                            <?= htmlspecialchars($nombre) ?>
                                        </button>
                                    </h2>
                                </div>
                            </div>

                            <!-- Resumen de Hoja de Vida (Estrictamente 3 líneas en todas las tarjetas) -->
                            <div class="bg-slate-900/[0.025] rounded-xl p-4 border border-slate-200/80 flex-grow flex flex-col justify-between">
                                <div class="texto-resumen-docente text-sm text-slate-600">
                                    <?= $textoDesc ?>
                                </div>

                                <div class="contenedor-ver-mas hidden mt-3 pt-2 border-t border-slate-200/60 flex justify-end">
                                    <button type="button"
                                            class="btn-abrir-modal-docente inline-flex items-center gap-1.5 text-xs font-semibold text-institucional-navy hover:text-yellow-600 transition-colors"
                                            <?= $modalDataAttrs ?>>
                                        <span>Ver más</span>
                                        <i class="fas fa-angle-down text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Pie de Tarjeta -->
                        <div class="px-6 py-3.5 bg-slate-50/90 border-t border-slate-200/80 flex flex-wrap items-center justify-between gap-2">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <?php if ($email !== null): ?>
                                    <a href="mailto:<?= htmlspecialchars($email) ?>" 
                                       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full bg-white hover:bg-institucional-navy text-slate-700 hover:text-white border border-slate-200/90 text-xs font-medium transition-all shadow-sm"
                                       title="Enviar correo a <?= htmlspecialchars($email) ?>">
                                        <i class="fas fa-envelope text-institucional-navy group-hover:text-current text-[11px]"></i>
                                        <span class="truncate max-w-[120px]"><?= htmlspecialchars($email) ?></span>
                                    </a>
                                <?php endif; ?>

                                <?php if ($celular !== null && $hrefWhatsapp !== null): ?>
                                    <a href="<?= htmlspecialchars($hrefWhatsapp) ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full bg-emerald-50 hover:bg-emerald-600 text-emerald-800 hover:text-white border border-emerald-200 text-xs font-mono font-medium transition-all shadow-sm"
                                       title="Contactar por WhatsApp">
                                        <i class="fab fa-whatsapp text-xs"></i>
                                        <span><?= htmlspecialchars($celular) ?></span>
                                    </a>
                                <?php endif; ?>

                                <?php if ($sitioWeb !== null): ?>
                                    <a href="<?= htmlspecialchars($sitioWeb) ?>" 
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
                                    <?= $modalDataAttrs ?>>
                                <i class="far fa-id-badge text-institucional-gold text-xs"></i>
                                <span>Ver perfil</span>
                            </button>
                        </div>

                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="code-window-panel p-10 text-center text-slate-500">
                No hay registros académicos activos en este momento.
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- MODAL INSTITUCIONAL (Con clase quill-content para mostrar todas las viñetas perfectamente) -->
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

            <!-- Descripción Completa con soporte de viñetas Quill.js -->
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