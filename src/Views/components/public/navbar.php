<?php
/**
 * Variables globales opcionales inyectadas para menús dinámicos:
 * @var array $enlacesInvestigacion (ubicacion = 'menu_investigacion')
 * @var array $enlacesServicios     (ubicacion = 'menu_servicios')
 */
$enlacesInvestigacion = $enlacesInvestigacion ?? [];
$enlacesServicios     = $enlacesServicios ?? [];
?>
<nav class="fixed top-0 left-0 w-full z-50 bg-institucional-navy text-white shadow-md border-b border-institucional-gold/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logos e Identidad (100% Responsive) -->
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="flex items-center gap-2 sm:gap-3 cursor-pointer">
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <img src="/assets/img/logo-unsxx.png" alt="Escudo UNSXX" class="h-8 sm:h-11 w-auto drop-shadow-[0_0_5px_rgba(255,255,255,0.8)] hover:drop-shadow-[0_0_10px_rgba(234,179,8,0.7)] transition-all">
                        <img src="/assets/img/logo-informatica.png" alt="Escudo Informática" class="h-8 sm:h-11 w-auto drop-shadow-[0_0_5px_rgba(255,255,255,0.8)] hover:drop-shadow-[0_0_10px_rgba(234,179,8,0.7)] transition-all">
                    </div>
                    <div class="flex flex-col justify-center">
                        <span class="font-bold text-[0.85rem] sm:text-lg leading-none tracking-tight block">Ingeniería Informática</span>
                        <span class="font-cursive text-institucional-gold text-[0.75rem] sm:text-[0.95rem] leading-none mt-1 sm:mt-1.5">Universidad Nacional "Siglo XX"</span>
                    </div>
                </a>
            </div>

            <!-- Menú Escritorio -->
            <div class="hidden lg:flex items-center space-x-1 xl:space-x-2">
                <a href="/" class="px-3 py-2 rounded-md text-sm font-medium hover:text-institucional-gold transition-colors">Inicio</a>
                
                <!-- Dropdown: Institución -->
                <div class="relative group">
                    <button class="px-3 py-2 rounded-md text-sm font-medium hover:text-institucional-gold transition-colors flex items-center gap-1">
                        Institución <i class="fas fa-chevron-down text-xs opacity-70"></i>
                    </button>
                    <div class="absolute left-0 mt-0 w-52 bg-white text-slate-800 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border-t-4 border-institucional-gold overflow-hidden">
                        <a href="/carrera/autoridades" class="block px-4 py-2.5 text-sm hover:bg-slate-50 hover:text-institucional-navy border-b border-slate-100">Autoridades Académicas</a>
                        <a href="/docentes" class="block px-4 py-2.5 text-sm hover:bg-slate-50 hover:text-institucional-navy border-b border-slate-100">Plantel Docente</a>
                        <a href="/carrera/logros" class="block px-4 py-2.5 text-sm hover:bg-slate-50 hover:text-institucional-navy border-b border-slate-100">Logros</a>
                        <a href="/documentos" class="block px-4 py-2.5 text-sm hover:bg-slate-50 hover:text-institucional-navy">Reglamentos y Documentos</a>
                    </div>
                </div>

                <!-- Dropdown: Académico -->
                <div class="relative group">
                    <button class="px-3 py-2 rounded-md text-sm font-medium hover:text-institucional-gold transition-colors flex items-center gap-1">
                        Académico <i class="fas fa-chevron-down text-xs opacity-70"></i>
                    </button>


                    <div class="absolute left-0 mt-0 w-60 bg-white text-slate-800 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border-t-4 border-institucional-gold overflow-hidden">
                        <a href="/academico/plan-estudios" class="block px-4 py-2.5 text-sm hover:bg-slate-50 hover:text-institucional-navy border-b border-slate-100">Plan de Estudios</a>
                        <a href="/academico/admision" class="block px-4 py-2.5 text-sm hover:bg-slate-50 hover:text-institucional-navy">Admisión</a>
                        <a href="/academico/titulacion" class="block px-4 py-2.5 text-sm hover:bg-slate-50 hover:text-institucional-navy">Titulación</a>
                        <a href="/laboratorios" class="block px-4 py-2.5 text-sm hover:bg-slate-50 hover:text-institucional-navy border-b border-slate-100">Laboratorios</a>
                        <a href="/horarios" class="block px-4 py-2.5 text-sm hover:bg-slate-50 hover:text-institucional-navy border-b border-slate-100">Horarios de Clase</a>

                        <div class="px-4 py-1.5 bg-slate-50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">Menciones</div>
                        <a href="/mencion/seguridad-informatica" class="block px-4 py-2.5 text-sm hover:bg-slate-50 hover:text-institucional-navy border-b border-slate-100">Seguridad Informática</a>
                        <a href="/mencion/computacion-movil" class="block px-4 py-2.5 text-sm hover:bg-slate-50 hover:text-institucional-navy border-b border-slate-100">Computación Móvil</a>
                    </div>
                </div>

                <!-- Dropdown: Investigación (Incluye enlaces de 'menu_investigacion') -->
                <div class="relative group">
                    <button class="px-3 py-2 rounded-md text-sm font-medium hover:text-institucional-gold transition-colors flex items-center gap-1">
                        Investigación <i class="fas fa-chevron-down text-xs opacity-70"></i>
                    </button>
                    <div class="absolute left-0 mt-0 w-60 bg-white text-slate-800 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border-t-4 border-institucional-gold overflow-hidden">
                        <a href="/investigacion/iidai" class="block px-4 py-2.5 text-sm hover:bg-slate-50 hover:text-institucional-navy border-b border-slate-100">Instituto IIDAI</a>
                        <?php foreach ($enlacesInvestigacion as $enl): ?>
                            <a href="<?= htmlspecialchars($enl['url']) ?>" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between px-4 py-2.5 text-sm hover:bg-slate-50 hover:text-institucional-navy border-b border-slate-100 last:border-b-0">
                                <span class="flex items-center gap-2">
                                    <i class="<?= htmlspecialchars($enl['icono'] ?: 'fas fa-book-open') ?> text-xs text-institucional-navy"></i>
                                    <?= htmlspecialchars($enl['nombre']) ?>
                                </span>
                                <i class="fas fa-external-link-alt text-[10px] text-slate-400"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Dropdown: Comunidad -->
                <div class="relative group">
                    <button class="px-3 py-2 rounded-md text-sm font-medium hover:text-institucional-gold transition-colors flex items-center gap-1">
                        Comunidad <i class="fas fa-chevron-down text-xs opacity-70"></i>
                    </button>
                    <div class="absolute left-0 mt-0 w-48 bg-white text-slate-800 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border-t-4 border-institucional-gold overflow-hidden">
                        <a href="/comunicados" class="block px-4 py-2.5 text-sm hover:bg-slate-50 hover:text-institucional-navy border-b border-slate-100">Comunicados</a>
                        <a href="/extension" class="block px-4 py-2.5 text-sm hover:bg-slate-50 hover:text-institucional-navy">Extensión Universitaria</a>
                    </div>
                </div>

                <!-- Dropdown: Servicios Principales ('menu_servicios') -->
                <?php if (!empty($enlacesServicios)): ?>
                <div class="relative group">
                    <button class="px-3 py-2 rounded-md text-sm font-medium hover:text-institucional-gold transition-colors flex items-center gap-1">
                        Servicios <i class="fas fa-chevron-down text-xs opacity-70"></i>
                    </button>
                    <div class="absolute left-0 mt-0 w-56 bg-white text-slate-800 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 border-t-4 border-institucional-gold overflow-hidden">
                        <?php foreach ($enlacesServicios as $serv): ?>
                            <a href="<?= htmlspecialchars($serv['url']) ?>" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between px-4 py-2.5 text-sm hover:bg-slate-50 hover:text-institucional-navy border-b border-slate-100 last:border-b-0">
                                <span class="flex items-center gap-2">
                                    <i class="<?= htmlspecialchars($serv['icono'] ?: 'fas fa-globe') ?> text-xs text-institucional-navy"></i>
                                    <?= htmlspecialchars($serv['nombre']) ?>
                                </span>
                                <i class="fas fa-external-link-alt text-[10px] text-slate-400"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <a href="/admision" class="px-3 py-2 rounded-md text-sm font-medium hover:text-institucional-gold transition-colors">Admisión</a>
                <a href="/contacto" class="px-3 py-2 rounded-md text-sm font-medium hover:text-institucional-gold transition-colors">Contacto</a>
                
                <!-- Icono discreto de Login -->
                <div class="pl-3 border-l border-institucional-gold/30 ml-1 flex items-center">
                    <a href="/login" class="text-slate-300 hover:text-institucional-gold transition-colors" title="Acceso Administrativo">
                        <i class="fas fa-lock text-sm"></i>
                    </a>
                </div>
            </div>

            <!-- Botón Menú Móvil -->
            <div class="lg:hidden flex items-center gap-4">
                <a href="/login" class="text-slate-300 hover:text-institucional-gold"><i class="fas fa-lock text-sm"></i></a>
                <button id="mobile-menu-btn" class="text-slate-200 hover:text-institucional-gold focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Menú Móvil Desplegable -->
    <div id="mobile-menu" class="hidden lg:hidden bg-[#172e6e] border-t border-institucional-gold/30 max-h-[80vh] overflow-y-auto">
        <div class="px-3 pt-2 pb-4 flex flex-col">
            <a href="/" class="px-3 py-2.5 text-sm font-semibold hover:bg-[#1e3a8a] border-b border-institucional-gold/20">Inicio</a>
            <a href="/carrera/autoridades" class="px-3 py-2.5 text-sm font-medium hover:bg-[#1e3a8a] border-b border-institucional-gold/20">Autoridades Académicas</a>
            <a href="/docentes" class="px-3 py-2.5 text-sm font-medium hover:bg-[#1e3a8a] border-b border-institucional-gold/20">Plantel Docente</a>
            <a href="/academico/plan-estudios" class="px-3 py-2.5 text-sm font-medium hover:bg-[#1e3a8a] border-b border-institucional-gold/20">Plan de Estudios</a>
            <a href="/laboratorios" class="px-3 py-2.5 text-sm font-medium hover:bg-[#1e3a8a] border-b border-institucional-gold/20">Laboratorios y Horarios</a>
            <a href="/comunicados" class="px-3 py-2.5 text-sm font-medium hover:bg-[#1e3a8a] border-b border-institucional-gold/20">Comunicados</a>
            <a href="/admision" class="px-3 py-2.5 text-sm font-bold text-institucional-gold hover:bg-[#1e3a8a] border-b border-institucional-gold/20">Admisión</a>
            <a href="/contacto" class="px-3 py-2.5 text-sm font-medium hover:bg-[#1e3a8a]">Contacto</a>
        </div>
    </div>
</nav>