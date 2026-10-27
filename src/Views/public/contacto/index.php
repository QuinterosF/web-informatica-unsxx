<?php
$telefonosInstitucionales = [];

if (!empty($celulares)) {
    foreach ($celulares as $cel) {
        // Ignoramos el soporte técnico aquí, ya está en el footer global
        if ((int) $cel['es_soporte'] !== 1) {
            $telefonosInstitucionales[] = $cel;
        }
    }
}

if (!function_exists('generarEnlaceContacto')) {
    function generarEnlaceContacto(string $numero, ?string $icono): array
    {
        $limpio = preg_replace('/[^0-9]/', '', $numero);
        $codigoWhatsapp = (strlen($limpio) === 8) ? '591' . $limpio : $limpio;
        $esWhatsapp = ($icono && stripos($icono, 'whatsapp') !== false);

        if ($esWhatsapp) {
            return [
                'href' => "https://wa.me/{$codigoWhatsapp}",
                'target' => '_blank',
                'texto' => 'Iniciar chat en WhatsApp',
                'tipo' => 'whatsapp'
            ];
        }

        return [
            'href' => "tel:+591{$limpio}",
            'target' => '_self',
            'texto' => 'Llamada directa',
            'tipo' => 'telefono'
        ];
    }
}
?>

<!-- HERO SECTION: Aurora Navy Glow -->
<section
    class="aurora-hero-navy text-white py-14 sm:py-16 border-b-4 border-institucional-gold relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 pointer-events-none"
        style="background-image: linear-gradient(to right, #ffffff 1px, transparent 1px), linear-gradient(to bottom, #ffffff 1px, transparent 1px); background-size: 36px 36px;">
    </div>
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div class="max-w-2xl">
                <div
                    class="inline-flex items-center gap-2.5 px-3.5 py-1 rounded-full bg-white/10 border border-institucional-gold/40 backdrop-blur-md mb-5">
                    <span class="w-2 h-2 rounded-full bg-institucional-gold animate-pulse"></span>
                    <a href="/" class="text-xs font-medium text-slate-200 hover:text-white transition-colors">Inicio</a>
                    <span class="text-xs text-institucional-gold/70">/</span>
                    <span
                        class="font-code text-xs font-medium text-institucional-gold uppercase tracking-wider">&lt;/&gt;
                        contactos, redes y ubicación</span>
                </div>
                <h1 class="font-display text-3xl sm:text-5xl font-bold tracking-tight leading-[1.13] text-white">
                    Canales de Contacto y <span class="text-institucional-gold">Ubicación</span>
                </h1>
                <p class="mt-4 text-base text-slate-200 font-light leading-relaxed">
                    Contacta directamente con Dirección de Carrera, Coordinación Académica o Secretaría de Ingeniería
                    Informática para consultas académicas y trámites administrativos.
                </p>
            </div>
            <div class="relative group self-start lg:self-center w-full sm:w-auto">
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-institucional-gold/50 to-blue-400/50 rounded-2xl blur-md opacity-60 group-hover:opacity-100 transition duration-300">
                </div>
                <a href="mailto:informatica@unsxx.bo"
                    class="relative bg-[#0f1f4d]/95 hover:bg-[#132760] border border-institucional-gold/40 rounded-xl p-5 flex items-center gap-4 backdrop-blur-xl shadow-2xl transition-all">
                    <div
                        class="w-13 h-13 p-3.5 rounded-xl bg-institucional-gold text-slate-900 flex items-center justify-center text-xl flex-shrink-0 shadow-[0_0_20px_rgba(234,179,8,0.45)] group-hover:scale-105 transition-transform">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <div class="pr-2">
                        <div class="flex items-center gap-2">
                            <span class="font-code text-[11px] uppercase tracking-wider text-institucional-gold">Buzón
                                Institucional</span>
                            <i
                                class="fas fa-arrow-up-right-from-square text-[10px] text-institucional-gold opacity-80"></i>
                        </div>
                        <span
                            class="font-display block font-bold text-lg sm:text-xl text-white tracking-tight mt-0.5">informatica@unsxx.bo</span>
                        <span class="block text-xs text-slate-300 font-light mt-0.5">Respuesta en horario
                            de oficina</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- CONTENIDO PRINCIPAL -->
<section class="relative py-12 sm:py-16 overflow-hidden">
    <div
        class="absolute top-8 left-1/4 -translate-x-1/2 w-[34rem] h-[22rem] bg-blue-600/10 rounded-full blur-3xl pointer-events-none">
    </div>
    <div
        class="absolute top-16 right-10 w-[28rem] h-[20rem] bg-institucional-gold/15 rounded-full blur-3xl pointer-events-none">
    </div>
    <div
        class="absolute bottom-1/3 left-1/3 w-[36rem] h-[24rem] bg-institucional-navy/10 rounded-full blur-3xl pointer-events-none">
    </div>

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-14">
        <!-- Tarjetas de Información Base -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="tech-card p-6 flex flex-col justify-between relative overflow-hidden">
                <div
                    class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-institucional-navy via-blue-600 to-institucional-gold">
                </div>
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <div
                            class="w-11 h-11 rounded-xl bg-institucional-navy text-institucional-gold flex items-center justify-center text-lg shadow-md">
                            <i class="fas fa-clock"></i></div>
                        <span
                            class="font-code px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-institucional-navy border border-blue-200/80">Presencial</span>
                    </div>
                    <h2 class="font-display text-xl font-bold text-slate-900 mb-2">Horario de Atención</h2>
                    <p class="text-sm text-slate-600 mb-6 leading-relaxed">Atención administrativa continua en Dirección
                        de Carrera y Secretaría.</p>
                </div>
                <div class="bg-slate-900/[0.03] rounded-xl p-4 border border-slate-200/90 space-y-2.5 text-sm">
                    <div class="flex justify-between items-center pb-2 border-b border-slate-200/80">
                        <span class="text-slate-600 font-medium flex items-center gap-2"><i
                                class="fas fa-calendar-check text-institucional-navy"></i> Días:</span>
                        <span class="font-semibold text-institucional-navy">Lunes a Viernes</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span
                            class="flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-slate-500"><i
                                class="fas fa-sun text-institucional-gold"></i> Mañana</span>
                        <span
                            class="font-code text-[13px] font-medium text-slate-900 bg-white px-2.5 py-0.5 rounded border border-slate-200">08:00
                            - 12:00</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span
                            class="flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-slate-500"><i
                                class="fas fa-cloud-sun text-institucional-navy"></i> Tarde</span>
                        <span
                            class="font-code text-[13px] font-medium text-slate-900 bg-white px-2.5 py-0.5 rounded border border-slate-200">14:00
                            - 18:00</span>
                    </div>
                </div>
            </div>

            <div class="tech-card-spotlight p-6 flex flex-col justify-between relative overflow-hidden">
                <div
                    class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-institucional-gold via-yellow-400 to-institucional-navy">
                </div>
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <div
                            class="w-11 h-11 rounded-xl bg-institucional-navy text-institucional-gold flex items-center justify-center text-lg shadow-md">
                            <i class="fas fa-laptop-code"></i></div>
                        <span
                            class="font-code px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-900 border border-amber-300/80">Área
                            Tecnología</span>
                    </div>
                    <h2 class="font-display text-xl font-bold text-slate-900 mb-2">Sede Académica</h2>
                    <p class="text-sm text-slate-600 mb-6 leading-relaxed">Dirección de Carrera, Secretaría, Aulas y
                        Laboratorios de Computación.</p>
                </div>
                <div class="bg-white/80 rounded-xl p-4 border border-blue-200/60 space-y-3 text-sm">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-6 h-6 rounded-md bg-institucional-navy/10 text-institucional-navy flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-map-marker-alt text-xs"></i></div>
                        <div>
                            <span class="block font-semibold text-slate-900">Campus María Barzola</span>
                            <span class="text-xs text-slate-500">Carretera a Catavi · Llallagua, Potosí</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 pt-2.5 border-t border-slate-200/80">
                        <div
                            class="w-6 h-6 rounded-md bg-institucional-gold/25 text-slate-900 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-building text-xs"></i></div>
                        <div>
                            <span class="block font-semibold text-slate-900">Edificio Bloque A (1er Piso)</span>
                            <span class="text-xs text-slate-500">Oficinas de Dirección y Secretaría</span>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="tech-card p-6 flex flex-col justify-between relative overflow-hidden md:col-span-2 lg:col-span-1">
                <div
                    class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-institucional-navy via-blue-600 to-institucional-gold">
                </div>
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <div
                            class="w-11 h-11 rounded-xl bg-institucional-navy text-institucional-gold flex items-center justify-center text-lg shadow-md">
                            <i class="fas fa-landmark"></i></div>
                        <span
                            class="font-code px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-300/80">Paraninfo
                            Universitario - UNSXX</span>
                    </div>
                    <h2 class="font-display text-xl font-bold text-slate-900 mb-2">Administración Central</h2>
                    <p class="text-sm text-slate-600 mb-6 leading-relaxed">Oficinas centrales de la Universidad Nacional
                        "Siglo XX", registros e inscripciones.</p>
                </div>
                <div class="bg-slate-900/[0.03] rounded-xl p-4 border border-slate-200/90 space-y-3 text-sm">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-6 h-6 rounded-md bg-institucional-navy/10 text-institucional-navy flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-location-dot text-xs"></i></div>
                        <div>
                            <span class="block font-semibold text-slate-900">Calle Campero N°36</span>
                            <span class="text-xs text-slate-500">Edificio de la Transparencia · Llallagua</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-2.5 border-t border-slate-200/80">
                        <a href="tel:025820222"
                            class="font-code text-[13px] inline-flex items-center gap-2 font-semibold text-institucional-navy hover:text-yellow-600 transition-colors"><i
                                class="fas fa-phone-volume text-xs text-institucional-gold"></i> 02-5820222</a>
                        <a href="https://unsxx.bo" target="_blank" rel="noopener noreferrer"
                            class="text-xs font-semibold text-institucional-navy hover:underline flex items-center gap-1">unsxx.bo
                            <i class="fas fa-external-link-alt text-[10px]"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Directorio Telefónico -->
        <div class="code-window-panel overflow-hidden relative">
            <div
                class="bg-gradient-to-r from-institucional-navy via-[#162d6e] to-institucional-navy px-6 py-3.5 border-b border-institucional-gold/40 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-institucional-gold inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 inline-block"></span>
                    </div>
                    <span
                        class="font-code text-[13px] text-institucional-gold font-medium tracking-normal pl-2 border-l border-white/20">&lt;/&gt;
                        Directorio Institucional</span>
                </div>
                <span class="font-code text-xs text-slate-200 flex items-center gap-2"><span
                        class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>Líneas habilitadas en horario
                    de oficina</span>
            </div>

            <div class="p-6 sm:p-8 lg:p-10 relative z-10">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                    <div>
                        <span
                            class="text-xs font-medium uppercase tracking-wider text-institucional-navy bg-blue-50 px-3 py-1 rounded-full border border-blue-200/80">Comunicación
                            Directa</span>
                        <h2 class="font-display text-2xl sm:text-3xl font-bold text-slate-900 mt-2.5">Números de
                            Contacto</h2>
                    </div>
                    <p class="text-sm text-slate-600 max-w-md">Selecciona el área correspondiente para iniciar una
                        conversación por WhatsApp o realizar una llamada telefónica.</p>
                </div>

                <?php if (!empty($telefonosInstitucionales)): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <?php foreach ($telefonosInstitucionales as $tel): ?>
                            <?php $accion = generarEnlaceContacto($tel['num_cel'], $tel['icono']); ?>
                            <a href="<?= htmlspecialchars($accion['href']) ?>" target="<?= $accion['target'] ?>"
                                rel="noopener noreferrer"
                                class="tech-card group p-5 flex flex-col justify-between relative overflow-hidden">
                                <div
                                    class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-institucional-navy via-institucional-gold to-institucional-navy opacity-0 group-hover:opacity-100 transition-opacity">
                                </div>
                                <div>
                                    <div class="flex items-center justify-between mb-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-institucional-navy/10 text-institucional-navy group-hover:bg-institucional-navy group-hover:text-institucional-gold flex items-center justify-center text-base transition-colors">
                                            <i class="<?= htmlspecialchars($tel['icono'] ?: 'fas fa-phone-alt') ?>"></i></div>
                                        <?php if ($accion['tipo'] === 'whatsapp'): ?>
                                            <span
                                                class="inline-flex items-center gap-1.5 text-[11px] font-medium text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-0.5 rounded-full"><span
                                                    class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> WhatsApp</span>
                                        <?php else: ?>
                                            <span
                                                class="inline-flex items-center gap-1.5 text-[11px] font-medium text-institucional-navy bg-blue-50 border border-blue-200 px-3 py-0.5 rounded-full">Línea
                                                Fija/Móvil</span>
                                        <?php endif; ?>
                                    </div>
                                    <h3
                                        class="font-semibold text-slate-500 text-xs uppercase tracking-wider mb-1 group-hover:text-institucional-navy transition-colors">
                                        <?= htmlspecialchars($tel['nom_cel']) ?></h3>
                                    <p class="font-display text-2xl font-bold text-slate-900 tracking-tight">
                                        <?= htmlspecialchars($tel['num_cel']) ?></p>
                                </div>
                                <div
                                    class="mt-5 pt-3 border-t border-slate-200/70 flex items-center justify-between text-xs font-semibold text-institucional-navy group-hover:text-yellow-600 transition-colors">
                                    <span><?= htmlspecialchars($accion['texto']) ?></span><i
                                        class="fas fa-arrow-right text-[10px] transform group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div
                        class="text-center py-8 bg-white/60 rounded-xl border border-dashed border-slate-300 text-sm text-slate-500">
                        No hay líneas telefónicas públicas registradas actualmente.</div>
                <?php endif; ?>

                <?php if (!empty($enlacesRedesSociales)): ?>
                    <div
                        class="mt-8 pt-6 border-t border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-lg bg-institucional-navy/10 text-institucional-navy flex items-center justify-center">
                                <i class="fas fa-hashtag text-sm"></i></div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Redes Sociales
                                    Oficiales</h4>
                                <p class="text-xs text-slate-500">Síguenos en nuestras redes para estar informado sobre las actividades de la Carrera</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2.5">
                            <?php foreach ($enlacesRedesSociales as $red): ?>
                                <a href="<?= htmlspecialchars($red['url']) ?>" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white hover:bg-institucional-navy text-slate-800 hover:text-white border border-slate-200 hover:border-institucional-navy text-xs font-medium transition-all shadow-sm">
                                    <i
                                        class="<?= htmlspecialchars($red['icono'] ?: 'fas fa-share-alt') ?> text-institucional-navy group-hover:text-institucional-gold"></i>
                                    <span><?= htmlspecialchars($red['nombre']) ?></span>
                                    <i class="fas fa-arrow-up-right-from-square text-[10px] opacity-50"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- BLOQUE 3: Geolocalización y Mapa Satelital -->
        <div class="code-window-panel overflow-hidden">
            <div
                class="p-5 sm:px-8 bg-white/90 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div
                        class="w-11 h-11 rounded-xl bg-institucional-navy text-institucional-gold flex items-center justify-center text-lg shadow-sm flex-shrink-0">
                        <i class="fas fa-map-location-dot"></i>
                    </div>
                    <div>
                        <h2 class="font-display text-lg sm:text-xl font-bold text-slate-900">
                            Ubicación · Ingeniería Informática UNSXX
                        </h2>
                        <p class="font-code text-xs text-slate-500">
                            Campus Universitario María Barzola - Carretera a Catavi · Área Tecnología · Ingeniería Informática · Edificio Bloque A (1er Piso) - Llallagua, Potosí
                        </p>
                    </div>
                </div>

                <!-- Botón píldora (radius 9999px como en DESIGN.md) -->
                <a href="https://maps.app.goo.gl/UAhZ6sthUT7aCAcW9" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-institucional-navy hover:bg-institucional-navy-dark text-white text-xs font-medium transition-all shadow-md border border-institucional-gold/40">
                    <i class="fas fa-diamond-turn-right text-institucional-gold"></i> Cómo llegar en Google Maps
                </a>
            </div>

            <div class="w-full h-80 sm:h-96 bg-slate-200 relative">
                <iframe title="Ubicación Carrera Ingeniería Informática UNSXX"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3785.3775208071775!2d-66.57654762268066!3d-18.421160519669943!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x93fce07f92535649%3A0x6266f35d3b55ed0b!2sCarrera%20Ing.%20Informatica%20UNSXX!5e0!3m2!1ses-419!2sbo!4v1792984152253!5m2!1ses-419!2sbo"
                    class="w-full h-full border-0" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

    </div>
</section>