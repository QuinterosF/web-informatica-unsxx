<?php
$currentUri = $currentUri ?? (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$currentUri = rtrim($currentUri, '/') ?: '/';
$currentUri = $currentUri === '/carrera' ? '/nosotros' : $currentUri;
$currentUri = $currentUri === '/comunidad' ? '/comunicados' : $currentUri;
$serviceLinks = $serviceLinks ?? [];

$carreraRoutes = ['/nosotros', '/admision', '/titulacion', '/becas', '/contacto'];
$academicoRoutes = ['/plan-estudios', '/menciones', '/plantel', '/documentos'];
$comunidadRoutes = ['/comunicados', '/eventos', '/logros'];

$isCurrent = static fn(string $path): bool => $currentUri === $path;
$isGroupCurrent = static fn(array $paths): bool => in_array($currentUri, $paths, true);
?>
<header class="aql-header">
    <div class="aql-header-inner">
        <a href="/" class="aql-brand" aria-label="Ingeniería Informática UNSXX, ir al inicio">
            <img src="/assets/img/logo-informatica.png" alt="Escudo de Ingeniería Informática UNSXX" class="aql-brand-mark" width="32" height="32">
            <span>
                <span class="aql-brand-name">Ingeniería Informática</span>
                <span class="aql-brand-sub">UNSXX · Llallagua</span>
            </span>
        </a>

        <nav class="aql-nav" aria-label="Menú principal">
            <a href="/" class="aql-nav-link" <?= $isCurrent('/') ? 'aria-current="page"' : '' ?>>Inicio</a>

            <div class="aql-nav-item" data-dropdown>
                <button type="button" class="aql-nav-trigger" data-dropdown-trigger aria-haspopup="true" aria-expanded="false" aria-controls="nav-carrera" <?= $isGroupCurrent($carreraRoutes) ? 'aria-current="page"' : '' ?>>
                    <span>Carrera</span>
                    <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="aql-dropdown-panel" id="nav-carrera" data-dropdown-panel aria-hidden="true">
                    <a href="/nosotros" class="aql-dropdown-link">Nosotros y Dirección</a>
                    <a href="/admision" class="aql-dropdown-link">Modalidades de Admisión</a>
                    <a href="/titulacion" class="aql-dropdown-link">Titulación y Reglamentos</a>
                    <a href="/becas" class="aql-dropdown-link">Becas y Beneficios</a>
                    <a href="/contacto" class="aql-dropdown-link">Ubicación y Contacto</a>
                </div>
            </div>

            <div class="aql-nav-item" data-dropdown>
                <button type="button" class="aql-nav-trigger" data-dropdown-trigger aria-haspopup="true" aria-expanded="false" aria-controls="nav-academico" <?= $isGroupCurrent($academicoRoutes) ? 'aria-current="page"' : '' ?>>
                    <span>Académico</span>
                    <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="aql-dropdown-panel" id="nav-academico" data-dropdown-panel aria-hidden="true">
                    <a href="/plan-estudios" class="aql-dropdown-link">Plan de Estudios</a>
                    <a href="/menciones" class="aql-dropdown-link">Menciones e IIDAI</a>
                    <a href="/plantel" class="aql-dropdown-link">Plantel Docente</a>
                    <a href="/documentos" class="aql-dropdown-link">Documentos Oficiales</a>
                </div>
            </div>

            <a href="/laboratorios" class="aql-nav-link" <?= $isCurrent('/laboratorios') ? 'aria-current="page"' : '' ?>>Laboratorios</a>

            <div class="aql-nav-item" data-dropdown>
                <button type="button" class="aql-nav-trigger" data-dropdown-trigger aria-haspopup="true" aria-expanded="false" aria-controls="nav-comunidad" <?= $isGroupCurrent($comunidadRoutes) ? 'aria-current="page"' : '' ?>>
                    <span>Comunidad</span>
                    <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="aql-dropdown-panel" id="nav-comunidad" data-dropdown-panel aria-hidden="true">
                    <a href="/comunicados" class="aql-dropdown-link">Comunicados Oficiales</a>
                    <a href="/eventos" class="aql-dropdown-link">Eventos y Extensión</a>
                    <a href="/logros" class="aql-dropdown-link">Salón de Logros</a>
                </div>
            </div>

            <?php if (!empty($serviceLinks)): ?>
                <div class="aql-nav-item" data-dropdown>
                    <button type="button" class="aql-nav-trigger" data-dropdown-trigger aria-haspopup="true" aria-expanded="false" aria-controls="nav-servicios">
                        <span>Servicios</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="aql-dropdown-panel" id="nav-servicios" data-dropdown-panel aria-hidden="true">
                        <?php foreach ($serviceLinks as $serviceLink): ?>
                            <?php if (empty($serviceLink['nombre']) || empty($serviceLink['url'])) continue; ?>
                            <a href="<?= htmlspecialchars($serviceLink['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" class="aql-dropdown-link">
                                <span><?= htmlspecialchars($serviceLink['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
                                <svg class="w-3.5 h-3.5 text-[#2862d7]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M17 7H9M17 7v8"/>
                                </svg>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </nav>

        <div class="aql-nav-actions">
            <a href="/login" target="_blank" rel="noopener noreferrer" class="aql-btn-icon aql-btn-quiet" aria-label="Acceso administrativo" title="Acceso administrativo">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </a>

            <button type="button" id="mobileNavToggle" class="aql-mobile-toggle" aria-expanded="false" aria-controls="mobileNavPanel" aria-label="Abrir menú de navegación">
                <svg data-mobile-icon="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg data-mobile-icon="close" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true" hidden>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <div id="mobileNavPanel" class="aql-mobile-panel" aria-label="Navegación móvil">
        <div class="aql-mobile-grid">
            <a href="/" class="aql-mobile-link" <?= $isCurrent('/') ? 'aria-current="page"' : '' ?>>Inicio</a>
            <a href="/nosotros" class="aql-mobile-link" <?= $isCurrent('/nosotros') ? 'aria-current="page"' : '' ?>>Nosotros</a>
            <a href="/admision" class="aql-mobile-link" <?= $isCurrent('/admision') ? 'aria-current="page"' : '' ?>>Admisión</a>
            <a href="/titulacion" class="aql-mobile-link" <?= $isCurrent('/titulacion') ? 'aria-current="page"' : '' ?>>Titulación</a>
            <a href="/becas" class="aql-mobile-link" <?= $isCurrent('/becas') ? 'aria-current="page"' : '' ?>>Becas</a>
            <a href="/contacto" class="aql-mobile-link" <?= $isCurrent('/contacto') ? 'aria-current="page"' : '' ?>>Contacto</a>
            <a href="/plan-estudios" class="aql-mobile-link" <?= $isCurrent('/plan-estudios') ? 'aria-current="page"' : '' ?>>Plan de Estudios</a>
            <a href="/menciones" class="aql-mobile-link" <?= $isCurrent('/menciones') ? 'aria-current="page"' : '' ?>>Menciones e IIDAI</a>
            <a href="/plantel" class="aql-mobile-link" <?= $isCurrent('/plantel') ? 'aria-current="page"' : '' ?>>Plantel Docente</a>
            <a href="/documentos" class="aql-mobile-link" <?= $isCurrent('/documentos') ? 'aria-current="page"' : '' ?>>Documentos</a>
            <a href="/laboratorios" class="aql-mobile-link" <?= $isCurrent('/laboratorios') ? 'aria-current="page"' : '' ?>>Laboratorios</a>
            <a href="/comunicados" class="aql-mobile-link" <?= $isCurrent('/comunicados') ? 'aria-current="page"' : '' ?>>Comunicados</a>
            <a href="/eventos" class="aql-mobile-link" <?= $isCurrent('/eventos') ? 'aria-current="page"' : '' ?>>Eventos</a>
            <a href="/logros" class="aql-mobile-link" <?= $isCurrent('/logros') ? 'aria-current="page"' : '' ?>>Logros</a>
        </div>

        <?php if (!empty($serviceLinks)): ?>
            <div class="mt-4 border-t border-[#e2e8f0] pt-4">
                <p class="aql-footer-heading">Servicios</p>
                <div class="grid gap-1">
                    <?php foreach ($serviceLinks as $serviceLink): ?>
                        <?php if (empty($serviceLink['nombre']) || empty($serviceLink['url'])) continue; ?>
                        <a href="<?= htmlspecialchars($serviceLink['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" class="aql-footer-link">
                            <?= htmlspecialchars($serviceLink['nombre'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</header>
