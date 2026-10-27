<?php
/**
 * Variables opcionales para enlaces dinámicos del pie de página:
 * @var array $enlacesFooterInstitucional (ubicacion = 'footer_enlaces')
 * @var array $enlacesRedesSociales       (ubicacion = 'footer_redes')
 */
$enlacesFooterInstitucional = $enlacesFooterInstitucional ?? [];
$enlacesRedesSociales = $enlacesRedesSociales ?? [];

$numSoporte = $telefonoSoporte['num_cel'] ?? '67264027';
$numSoporteLimpio = '591' . preg_replace('/[^0-9]/', '', $numSoporte);
$urlActual = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . ($_SERVER['HTTP_HOST'] ?? 'informatica.unsxx.bo') . ($_SERVER['REQUEST_URI'] ?? '/');
$msgReporte = rawurlencode("Hola Soporte Técnico de Ing. Informática UNSXX, deseo reportar un inconveniente técnico en el portal web público (URL: {$urlActual}). Detalle: ");
?>
<footer class="bg-institucional-navy text-slate-200 pt-12 pb-8 border-t-4 border-institucional-gold relative z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">

            <!-- Columna 1: Identidad -->
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center gap-2">
                        <img src="/assets/img/logo-unsxx.png" alt="Escudo UNSXX"
                            class="h-12 sm:h-14 w-auto drop-shadow-[0_0_5px_rgba(255,255,255,0.8)]">
                        <img src="/assets/img/logo-informatica.png" alt="Escudo Informática"
                            class="h-12 sm:h-14 w-auto drop-shadow-[0_0_5px_rgba(255,255,255,0.8)]">
                    </div>
                    <div class="flex flex-col justify-center">
                        <h3 class="text-white font-bold text-base sm:text-lg leading-tight">Ingeniería Informática</h3>
                        <p class="font-cursive text-institucional-gold text-[0.95rem] leading-none mt-1">Universidad
                            Nacional "Siglo XX"</p>
                    </div>
                </div>
                <p class="text-sm text-slate-300 mb-6 leading-relaxed">
                    Formando profesionales orgánicos con capacidad científica, tecnológica e innovación para el
                    desarrollo de la región y el país.
                </p>
                <div
                    class="inline-block px-3 py-1.5 border border-institucional-gold/60 rounded-full text-xs font-semibold text-institucional-gold">
                    <i class="fas fa-check-circle mr-1"></i> Carrera Acreditada al CEUB
                </div>
            </div>

            <!-- Columna 2: Enlaces Rápidos + Enlaces Institucionales Dinámicos ('footer_enlaces') -->
            <div>
                <h4
                    class="text-white font-bold mb-4 uppercase tracking-wider text-sm border-b border-institucional-gold/30 pb-2">
                    Enlaces Rápidos</h4>
                <ul class="space-y-2.5 text-sm mt-4">
                    <li><a href="/admision" class="hover:text-institucional-gold transition-colors flex items-center"><i
                                class="fas fa-angle-right mr-2 text-institucional-gold"></i> Admisión</a></li>
                    <li><a href="/academico/plan-estudios"
                            class="hover:text-institucional-gold transition-colors flex items-center"><i
                                class="fas fa-angle-right mr-2 text-institucional-gold"></i> Plan de Estudios</a></li>
                    <li><a href="/comunicados"
                            class="hover:text-institucional-gold transition-colors flex items-center"><i
                                class="fas fa-angle-right mr-2 text-institucional-gold"></i> Comunicados y Avisos</a>
                    </li>

                    <?php foreach ($enlacesFooterInstitucional as $enl): ?>
                        <li>
                            <a href="<?= htmlspecialchars($enl['url']) ?>" target="_blank" rel="noopener noreferrer"
                                class="hover:text-institucional-gold transition-colors flex items-center">
                                <i class="fas fa-angle-right mr-2 text-institucional-gold"></i>
                                <?= htmlspecialchars($enl['nombre']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Columna 3: Contacto y Atención -->
            <div>
                <h4
                    class="text-white font-bold mb-4 uppercase tracking-wider text-sm border-b border-institucional-gold/30 pb-2">
                    Contacto</h4>
                <ul class="space-y-3 text-sm text-slate-300 mt-4">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt mt-1 text-institucional-gold"></i>
                        <span>Campus María Barzola (Carretera a Catavi).<br>Edificio Bloque A, 1er Piso. Llallagua,
                            Potosí.</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-envelope text-institucional-gold"></i>
                        <a href="mailto:informatica@unsxx.bo"
                            class="hover:text-white transition-colors">informatica@unsxx.bo</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-phone-alt text-institucional-gold"></i>
                        <span>Dirección: 60492494 - 72457622</span>
                    </li>
                    <li class="flex items-start gap-3 mt-4 pt-4 border-t border-institucional-gold/30">
                        <i class="fas fa-clock mt-1 text-institucional-gold"></i>
                        <div>
                            <span class="block text-white font-medium mb-1">Horarios de Atención:</span>
                            Lunes a Viernes<br>
                            08:00 a 12:00 y de 14:00 a 18:00
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright + Reporte de Incidencia + Redes Sociales ('footer_redes') -->
        <div
            class="mt-12 pt-6 border-t border-institucional-gold/30 flex flex-col lg:flex-row justify-between items-center gap-4 text-center lg:text-left">
            <p class="text-sm text-slate-300">
                &copy; <?= date('Y') ?> Carrera de Ingeniería Informática UNSXX. Todos los derechos reservados.
            </p>


            <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-6">
                <a href="https://wa.me/<?= $numSoporteLimpio ?>?text=<?= $msgReporte ?>" target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/5 hover:bg-white/15 border border-white/15 hover:border-institucional-gold/50 text-xs text-slate-300 hover:text-white transition-all"
                    title="¿Encontraste un enlace roto o error visual en el portal? Repórtalo a Soporte Técnico">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-mono text-[11px]">¿Falla en el sitio web? <strong
                            class="text-institucional-gold font-sans">Reportar incidencia</strong></span>
                </a>

                <?php if (!empty($enlacesRedesSociales)): ?>
                    <div class="flex gap-4 pl-3 border-l border-institucional-gold/30">
                        <?php foreach ($enlacesRedesSociales as $red): ?>
                            <a href="<?= htmlspecialchars($red['url']) ?>" target="_blank" rel="noopener noreferrer"
                                class="text-slate-300 hover:text-institucional-gold transition-colors"
                                title="<?= htmlspecialchars($red['nombre']) ?>">
                                <i class="<?= htmlspecialchars($red['icono'] ?: 'fas fa-share-alt') ?> text-base"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>