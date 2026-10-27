<?php
require_once __DIR__ . '/../layouts/rich_text.php';

$info = $info ?? [];
$portadas = $portadas ?? [];
$mentions = $mentions ?? [];
$labs = $labs ?? [];
$announcements = $announcements ?? [];
$extensions = $extensions ?? [];
$logros = $logros ?? [];
$admissions = $admissions ?? [];

$plainExcerpt = static function (?string $html, int $limit = 180): string {
    $text = html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = trim(preg_replace('/\s+/u', ' ', $text));

    if ($text === '') {
        return '';
    }

    if (mb_strlen($text, 'UTF-8') <= $limit) {
        return $text;
    }

    $cut = mb_substr($text, 0, $limit - 1, 'UTF-8');
    $lastSpace = mb_strrpos($cut, ' ', 0, 'UTF-8');
    if ($lastSpace !== false && $lastSpace > 0) {
        $cut = mb_substr($cut, 0, $lastSpace, 'UTF-8');
    }

    return rtrim($cut, " \t\n\r\0\x0B.,;:") . '…';
};

$jsString = static function ($value): string {
    $encoded = json_encode(
        (string) $value,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE
    );

    return htmlspecialchars($encoded ?: '""', ENT_QUOTES, 'UTF-8');
};

$presentationExcerpt = '';
if (!empty($info['presentacion'])) {
    if (preg_match_all('/<p[^>]*>(.*?)<\/p>/is', (string) $info['presentacion'], $paragraphs)) {
        foreach ($paragraphs[1] as $paragraph) {
            $candidate = $plainExcerpt($paragraph, 300);
            if (mb_strlen($candidate, 'UTF-8') >= 80 && !str_ends_with($candidate, ':')) {
                $presentationExcerpt = $candidate;
                break;
            }
        }
    }

    if ($presentationExcerpt === '') {
        $presentationExcerpt = $plainExcerpt((string) $info['presentacion'], 260);
    }
}

if ($presentationExcerpt === '') {
    $presentationExcerpt = 'Formación en Ciencias de la Computación con menciones en Seguridad Informática y Computación Móvil.';
}

$metaDescription = $plainExcerpt($presentationExcerpt, 155);
$title = 'Ingeniería Informática — Universidad Nacional "Siglo XX"';
$pageTitle = 'Inicio';

$heroGallery = [];
foreach ($portadas as $portada) {
    if (empty($portada['archivo_url'])) {
        continue;
    }

    $coverTitle = trim((string) ($portada['titulo'] ?? ''));
    if ($coverTitle === '') {
        $coverTitle = 'Campus María Barzola — Llallagua';
    }

    $heroGallery[] = [
        'url' => '/uploads/img/portadas/' . $portada['archivo_url'],
        'title' => $coverTitle,
        'subtitle' => 'Fotografía institucional',
        'alt' => $coverTitle,
    ];
}

$heroGalleryJson = json_encode(
    $heroGallery,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE
) ?: '[]';

$mentionMap = [];
foreach ($mentions as $mention) {
    $mentionKey = mb_strtolower(trim((string) ($mention['nombre'] ?? '')), 'UTF-8');
    if ($mentionKey !== '') {
        $mentionMap[$mentionKey] = $mention;
    }
}

$heroMentions = [];
foreach (['seguridad informática', 'computación móvil', 'iidai'] as $wantedMention) {
    if (isset($mentionMap[$wantedMention])) {
        $heroMentions[] = $mentionMap[$wantedMention];
    }
}

foreach ($mentions as $mention) {
    if (count($heroMentions) >= 3) {
        break;
    }
    if (!in_array($mention, $heroMentions, true)) {
        $heroMentions[] = $mention;
    }
}

$heroMentions = array_slice($heroMentions, 0, 3);
$mentionCount = count($mentions);
$labCount = count($labs);
$labNames = array_values(array_filter(array_map(static fn(array $lab): string => trim((string) ($lab['nombre_lab'] ?? '')), $labs)));
$mentionNames = array_values(array_filter(array_map(static fn(array $mention): string => trim((string) ($mention['nombre'] ?? '')), $mentions)));

$latestAdmission = $admissions[0] ?? null;
$admissionGestion = $latestAdmission['gestion'] ?? date('Y');
$admissionProcedure = $plainExcerpt($latestAdmission['proc_adm'] ?? '', 80);

$announcement = $announcements[0] ?? null;
$event = $extensions[0] ?? null;
$achievement = $logros[0] ?? null;

$announcementDocs = [];
foreach ($announcements as $announcementItem) {
    if (empty($announcementItem['doc_com'])) {
        continue;
    }
    if ($announcement && (string) ($announcementItem['id_com'] ?? '') === (string) ($announcement['id_com'] ?? '')) {
        continue;
    }
    $announcementDocs[] = $announcementItem;
    if (count($announcementDocs) >= 2) {
        break;
    }
}

$announcementTitle = trim((string) ($announcement['nom_com'] ?? ''));
$announcementExcerpt = $plainExcerpt($announcement['desc_com'] ?? '', 220);
$announcementDate = !empty($announcement['fech_com']) ? date('d/m/Y', strtotime((string) $announcement['fech_com'])) : '';
$announcementPhotoUrl = !empty($announcement['foto_com']) ? '/uploads/img/comunicados/' . $announcement['foto_com'] : '';
$announcementDocUrl = !empty($announcement['doc_com']) ? '/uploads/pdf/comunicados/' . $announcement['doc_com'] : '';

$eventTitle = trim((string) ($event['nom_ext'] ?? ''));
$eventExcerpt = $plainExcerpt($event['desc_ext'] ?? '', 150);
$eventPlace = trim((string) ($event['lugar_ext'] ?? ''));
$eventCategory = trim((string) ($event['nom_cat'] ?? ''));
$eventDay = '';
$eventMonth = '';
if (!empty($event['fech_ext'])) {
    $eventTimestamp = strtotime((string) $event['fech_ext']);
    $eventDay = date('d', $eventTimestamp);
    $spanishMonths = [1 => 'ENE', 2 => 'FEB', 3 => 'MAR', 4 => 'ABR', 5 => 'MAY', 6 => 'JUN', 7 => 'JUL', 8 => 'AGO', 9 => 'SEP', 10 => 'OCT', 11 => 'NOV', 12 => 'DIC'];
    $eventMonth = $spanishMonths[(int) date('n', $eventTimestamp)] ?? '';
}

$achievementPosition = trim((string) ($achievement['posicion'] ?? ''));
$achievementTitle = trim((string) ($achievement['nom_log'] ?? ''));
$achievementPlace = trim((string) ($achievement['lugar_log'] ?? ''));
$achievementYear = !empty($achievement['fech_log']) ? date('Y', strtotime((string) $achievement['fech_log'])) : '';

ob_start();
?>

<section class="aql-hero">
    <div class="aql-container">
        <div class="aql-hero-grid">
            <div class="aql-hero-copy">
                <span class="aql-eyebrow">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#eab308]" aria-hidden="true"></span>
                    Rumbo a la Acreditación
                </span>

                <div class="aql-hero-badges">
                    <span class="aql-badge">
                        <svg class="w-3.5 h-3.5 text-[#ca8a04]" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Carrera Acreditada CEUB
                    </span>
                    <span class="aql-badge aql-badge-neutral">25 Años de Vida Institucional</span>
                </div>

                <h1 class="aql-display aql-hero-title">Ingeniería Informática</h1>

                <p class="aql-body-lg aql-hero-lead">
                    <?= htmlspecialchars($presentationExcerpt, ENT_QUOTES, 'UTF-8') ?>
                </p>

                <div class="aql-hero-actions">
                    <a href="/admision" class="aql-btn-primary">Admisión <?= date('Y') ?></a>
                    <a href="/plan-estudios" class="aql-btn-ghost">Plan de Estudios</a>
                </div>

                <p class="aql-mono-sm text-[#64748b]">Universidad Nacional “Siglo XX” · Llallagua, Potosí</p>
            </div>

            <div class="aql-hero-visual">
                <?php if (!empty($heroMentions)): ?>
                    <div class="aql-tabs" role="tablist" aria-label="Menciones e instituto de investigación">
                        <?php foreach ($heroMentions as $index => $mention): ?>
                            <button
                                type="button"
                                id="hero-tab-<?= $index ?>"
                                class="aql-tab"
                                role="tab"
                                aria-selected="<?= $index === 0 ? 'true' : 'false' ?>"
                                aria-controls="hero-panel-<?= $index ?>"
                                tabindex="<?= $index === 0 ? '0' : '-1' ?>"
                                data-hero-tab
                            >
                                <?= htmlspecialchars($mention['nombre'] ?? 'Mención', ENT_QUOTES, 'UTF-8') ?>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <div class="aql-hero-showcase">
                        <?php foreach ($heroMentions as $index => $mention): ?>
                            <?php
                            $mentionName = trim((string) ($mention['nombre'] ?? 'Mención'));
                            $mentionKey = mb_strtolower($mentionName, 'UTF-8');
                            $isIidai = $mentionKey === 'iidai';
                            $mentionShield = !empty($mention['escudo_url']) ? '/uploads/img/menciones/' . $mention['escudo_url'] : '';
                            $coverIndex = $heroGallery !== [] ? min($index, count($heroGallery) - 1) : 0;
                            $cover = $heroGallery[$coverIndex] ?? null;
                            $coverUrl = $cover['url'] ?? '/assets/img/logo-informatica.png';
                            $coverTitle = $cover['title'] ?? 'Campus María Barzola — Llallagua';
                            $coverAlt = $cover['alt'] ?? 'Campus María Barzola, Ingeniería Informática UNSXX';
                            $profileLines = [];

                            if ($isIidai) {
                                $profileLines = [
                                    'Centro de investigación y desarrollo de aplicaciones informáticas con técnicas avanzadas de la informática.',
                                    'Promover y apoyar el buen uso de la última tecnología informática.',
                                    'Servir como puente entre lo académico y los distintos sectores sociales de nuestra región y país.',
                                ];
                            } else {
                                foreach (preg_split('/\r\n|\r|\n/', (string) ($mention['perfil_profesional'] ?? '')) as $profileLine) {
                                    $profileLine = trim(preg_replace('/^[\-\x{2022}\s]+/u', '', $profileLine));
                                    $profileLine = trim(preg_replace('/\s+/u', ' ', $profileLine));
                                    if ($profileLine === '') {
                                        continue;
                                    }
                                    $profileLines[] = $profileLine;
                                    if (count($profileLines) >= 2) {
                                        break;
                                    }
                                }
                            }
                            ?>
                            <div
                                id="hero-panel-<?= $index ?>"
                                class="aql-tab-panel aql-hero-pane<?= $index === 0 ? ' is-active' : '' ?>"
                                role="tabpanel"
                                aria-labelledby="hero-tab-<?= $index ?>"
                            >
                                <div class="aql-hero-layout">
                                    <div class="aql-hero-photo-card">
                                        <div class="aql-hero-photo-head">
                                            <span class="truncate"><?= htmlspecialchars($mentionName, ENT_QUOTES, 'UTF-8') ?></span>
                                            <?php if ($mentionShield !== ''): ?>
                                                <img src="<?= htmlspecialchars($mentionShield, ENT_QUOTES, 'UTF-8') ?>" alt="" class="w-5 h-5 object-contain" width="20" height="20" loading="lazy">
                                            <?php else: ?>
                                                <span class="w-2 h-2 rounded-full bg-emerald-500" aria-hidden="true"></span>
                                            <?php endif; ?>
                                        </div>

                                        <div
                                            class="aql-hero-photo skeleton-shimmer"
                                            <?php if ($heroGallery !== []): ?>
                                                role="button"
                                                tabindex="0"
                                                aria-label="Abrir galería de fotografías institucionales"
                                                onclick='openLightbox(<?= htmlspecialchars($heroGalleryJson, ENT_QUOTES, 'UTF-8') ?>, <?= $coverIndex ?>)'
                                                onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); this.click(); }"
                                            <?php endif; ?>
                                        >
                                            <img
                                                src="<?= htmlspecialchars($coverUrl, ENT_QUOTES, 'UTF-8') ?>"
                                                alt="<?= htmlspecialchars($coverAlt, ENT_QUOTES, 'UTF-8') ?>"
                                                class="lazy-skeleton"
                                                width="640"
                                                height="400"
                                                <?= $index === 0 ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"' ?>
                                                decoding="async"
                                            >
                                        </div>

                                        <div class="aql-hero-photo-caption">
                                            <span class="truncate"><?= htmlspecialchars($coverTitle, ENT_QUOTES, 'UTF-8') ?></span>
                                            <?php if ($heroGallery !== []): ?>
                                                <a
                                                    href="<?= htmlspecialchars($coverUrl, ENT_QUOTES, 'UTF-8') ?>"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="aql-mono-sm text-[#2862d7] hover:underline"
                                                    onclick='openLightbox(<?= htmlspecialchars($heroGalleryJson, ENT_QUOTES, 'UTF-8') ?>, <?= $coverIndex ?>); return false;'
                                                >
                                                    Ver galería
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="aql-hero-terminal-stack">
                                        <div class="aql-code-window">
                                            <div class="aql-code-header">
                                                <span class="font-medium text-white">Perfil profesional</span>
                                                <span class="aql-code-accent">Datos oficiales</span>
                                            </div>
                                            <div class="aql-code-body">
                                                <code><span class="aql-code-key">mencion</span>(<span class="aql-code-value">"<?= htmlspecialchars($mentionName, ENT_QUOTES, 'UTF-8') ?>"</span>) {<br>
<?php if (!empty($profileLines)): ?>
<?php foreach ($profileLines as $profileLine): ?>
&nbsp;&nbsp;<span class="aql-code-accent"><?= htmlspecialchars($profileLine, ENT_QUOTES, 'UTF-8') ?></span><br>
<?php endforeach; ?>
<?php else: ?>
&nbsp;&nbsp;<span class="aql-code-comment">// Perfil disponible en la página de menciones.</span><br>
<?php endif; ?>
}</code>
                                            </div>
                                        </div>

                                        <div class="aql-code-window">
                                            <div class="aql-code-header">
                                                <span>Formación y salidas</span>
                                                <span class="aql-code-success">● Carrera UNSXX</span>
                                            </div>
                                            <div class="aql-code-body">
                                                <code>{
<?php if ($isIidai): ?>
  <span class="aql-code-key">"instituto"</span>: <span class="aql-code-value">"IIDAI"</span>,<br>
  <span class="aql-code-key">"funcion"</span>: <span class="aql-code-value">"Investigación, desarrollo e innovación"</span>,<br>
  <span class="aql-code-key">"vinculo"</span>: <span class="aql-code-value">"Académico y sector social"</span><br>
<?php else: ?>
  <span class="aql-code-key">"duracion"</span>: <span class="aql-code-value">"5 años"</span>,<br>
  <span class="aql-code-key">"salidas_laterales"</span>: [<span class="aql-code-value">"Técnico Medio (2 años)"</span>, <span class="aql-code-value">"Técnico Superior (3 años)"</span>],<br>
  <span class="aql-code-key">"mencion"</span>: <span class="aql-code-value">"<?= htmlspecialchars($mentionName, ENT_QUOTES, 'UTF-8') ?>"</span><br>
<?php endif; ?>
}</code>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="aql-card aql-card-spotlight">
                        <p class="aql-heading-sm">Menciones e IIDAI</p>
                        <p class="aql-body mt-2">Consulta las menciones de especialidad y el Instituto de Investigación y Desarrollo de Aplicaciones Informáticas.</p>
                        <a href="/menciones" class="aql-btn-primary mt-4">Conocer menciones e IIDAI</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="aql-proof-bar" aria-label="Datos institucionales">
    <div class="aql-container">
        <div class="aql-proof-grid">
            <div class="aql-proof-item">
                <span class="aql-proof-value">CEUB</span>
                <span class="aql-proof-label">Carrera acreditada</span>
            </div>
            <div class="aql-proof-item">
                <span class="aql-proof-value">25</span>
                <span class="aql-proof-label">Años de vida institucional</span>
            </div>
            <div class="aql-proof-item">
                <span class="aql-proof-value"><?= $mentionCount ?></span>
                <span class="aql-proof-label">Menciones de especialidad</span>
            </div>
            <div class="aql-proof-item">
                <span class="aql-proof-value"><?= $labCount ?></span>
                <span class="aql-proof-label">Laboratorios activos</span>
            </div>
        </div>
    </div>
</section>

<section class="aql-section">
    <div class="aql-container">
        <div class="max-w-2xl aql-reveal">
            <span class="aql-eyebrow">Explora la carrera</span>
            <h2 class="aql-heading mt-2">Del ingreso a la titulación, con una ruta clara</h2>
            <p class="aql-body mt-3">
                Consulta las modalidades de admisión, el plan de estudios, las menciones de especialidad y los laboratorios de la Carrera.
            </p>
        </div>

        <div class="aql-bento mt-10">
            <a href="/admision" class="aql-card aql-card-interactive aql-bento-7 aql-bento-card aql-bento-dominant aql-reveal" aria-label="Ver modalidades de admisión">
                <div>
                    <div class="aql-card-kicker">
                        <span class="aql-badge">Admisión <?= htmlspecialchars((string) $admissionGestion, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="aql-mono-sm text-[#64748b]">Ingreso</span>
                    </div>
                    <h3 class="aql-bento-title">Postula a Ingeniería Informática</h3>
                    <p class="aql-bento-copy">
                        <?php if ($admissionProcedure !== ''): ?>
                            <?= htmlspecialchars($admissionProcedure, ENT_QUOTES, 'UTF-8') ?>. Consulta requisitos, fechas y convocatorias vigentes.
                        <?php else: ?>
                            Consulta requisitos, fechas y convocatorias vigentes para nuevos postulantes.
                        <?php endif; ?>
                    </p>
                </div>
                <span class="aql-bento-link">Ver modalidades de admisión →</span>
            </a>

            <div class="aql-card aql-card-spotlight aql-bento-5 aql-bento-card aql-reveal">
                <div>
                    <div class="aql-card-kicker">
                        <span class="aql-badge">Formación</span>
                        <span class="aql-mono-sm text-[#475569]">2 · 3 · 5 años</span>
                    </div>
                    <h3 class="aql-bento-title">Nivel de formación piramidal</h3>
                    <p class="aql-bento-copy">Salidas laterales hacia Técnico Medio y Técnico Superior, con la Licenciatura como grado principal.</p>
                </div>

                <div class="aql-pyramid" aria-label="Niveles de formación">
                    <a href="/plan-estudios" class="aql-pyramid-tier" data-level="2">
                        <span class="aql-pyramid-years">2 años</span>
                        <span class="aql-pyramid-label">Técnico Medio</span>
                    </a>
                    <a href="/titulacion" class="aql-pyramid-tier" data-level="3">
                        <span class="aql-pyramid-years">3 años</span>
                        <span class="aql-pyramid-label">Técnico Superior</span>
                    </a>
                    <a href="/plan-estudios" class="aql-pyramid-tier" data-level="5">
                        <span class="aql-pyramid-years">5 años</span>
                        <span class="aql-pyramid-label">Licenciatura</span>
                    </a>
                </div>
            </div>

            <a href="/plan-estudios" class="aql-card aql-card-interactive aql-bento-4 aql-bento-card aql-reveal" aria-label="Ver plan de estudios">
                <div>
                    <span class="aql-mono-sm text-[#2862d7]">Plan de estudios</span>
                    <h3 class="aql-bento-title mt-2">5 años · Sistema anual</h3>
                    <p class="aql-bento-copy">Organización curricular por año, con formación en Ciencias de la Computación y asignaturas de mención.</p>
                </div>
                <span class="aql-bento-link">Explorar plan →</span>
            </a>

            <a href="/menciones" class="aql-card aql-card-interactive aql-bento-4 aql-bento-card aql-reveal" aria-label="Ver menciones e IIDAI">
                <div>
                    <span class="aql-mono-sm text-[#2862d7]">Especialidad</span>
                    <h3 class="aql-bento-title mt-2">Menciones e IIDAI</h3>
                    <div class="aql-chip-row mt-3">
                        <?php foreach ($mentionNames as $mentionName): ?>
                            <span class="aql-chip"><?= htmlspecialchars($mentionName, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <span class="aql-bento-link">Conocer menciones →</span>
            </a>

            <a href="/laboratorios" class="aql-card aql-card-interactive aql-bento-4 aql-bento-card aql-reveal" aria-label="Ver laboratorios">
                <div>
                    <span class="aql-mono-sm text-[#2862d7]">Infraestructura</span>
                    <h3 class="aql-bento-title mt-2"><?= $labCount ?> laboratorios activos</h3>
                    <div class="aql-chip-row mt-3">
                        <?php foreach ($labNames as $labName): ?>
                            <span class="aql-chip"><?= htmlspecialchars($labName, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <span class="aql-bento-link">Ver laboratorios →</span>
            </a>
        </div>
    </div>
</section>

<section class="aql-section aql-section-alt">
    <div class="aql-container">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between aql-reveal">
            <div class="max-w-2xl">
                <span class="aql-eyebrow">Actualidad</span>
                <h2 class="aql-heading mt-2">Comunicados, eventos y logros</h2>
                <p class="aql-body mt-3">Consulta las últimas publicaciones oficiales de la Carrera.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="/comunicados" class="aql-btn-ghost aql-btn-sm">Comunicados</a>
                <a href="/eventos" class="aql-btn-ghost aql-btn-sm">Eventos</a>
                <a href="/logros" class="aql-btn-ghost aql-btn-sm">Logros</a>
            </div>
        </div>

        <div class="aql-news-grid mt-10">
            <article class="aql-news-feature aql-reveal">
                <?php if ($announcement): ?>
                    <div>
                        <div class="aql-card-kicker">
                            <span class="aql-badge">Comunicado</span>
                            <?php if ($announcementDate !== ''): ?>
                                <span class="aql-news-meta"><?= htmlspecialchars($announcementDate, ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if ($announcementPhotoUrl !== ''): ?>
                            <a
                                href="<?= htmlspecialchars($announcementPhotoUrl, ENT_QUOTES, 'UTF-8') ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="aql-card-media block w-full p-0 text-left skeleton-shimmer"
                                aria-label="Abrir imagen del comunicado"
                                onclick='openSingleImage(<?= $jsString($announcementPhotoUrl) ?>, <?= $jsString($announcementTitle) ?>, <?= $jsString('Comunicado oficial') ?>); return false;'
                            >
                                <img
                                    src="<?= htmlspecialchars($announcementPhotoUrl, ENT_QUOTES, 'UTF-8') ?>"
                                    alt="<?= htmlspecialchars($announcementTitle !== '' ? $announcementTitle : 'Comunicado oficial de la Carrera', ENT_QUOTES, 'UTF-8') ?>"
                                    class="lazy-skeleton"
                                    width="640"
                                    height="400"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </a>
                        <?php endif; ?>

                        <h3 class="aql-news-title mt-5"><?= htmlspecialchars($announcementTitle !== '' ? $announcementTitle : 'Comunicado oficial', ENT_QUOTES, 'UTF-8') ?></h3>
                        <?php if ($announcementExcerpt !== ''): ?>
                            <p class="aql-news-copy mt-2"><?= htmlspecialchars($announcementExcerpt, ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <?php if ($announcementDocUrl !== ''): ?>
                            <a
                                href="<?= htmlspecialchars($announcementDocUrl, ENT_QUOTES, 'UTF-8') ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="aql-btn-primary aql-btn-sm"
                                onclick='openPdfModal(<?= $jsString($announcementDocUrl) ?>, <?= $jsString($announcementTitle) ?>, <?= $jsString('Comunicado oficial') ?>, <?= $jsString($announcementDate) ?>); return false;'
                            >
                                Abrir comunicado PDF
                            </a>
                        <?php else: ?>
                            <a href="/comunicados" class="aql-btn-ghost aql-btn-sm">Leer comunicado</a>
                        <?php endif; ?>
                        <a href="/comunicados" class="aql-bento-link">Ver todos los comunicados →</a>
                    </div>
                <?php else: ?>
                    <div>
                        <span class="aql-badge">Comunicado</span>
                        <h3 class="aql-news-title mt-4">Aún no hay comunicados publicados</h3>
                        <p class="aql-news-copy mt-2">La cartelera oficial de la Carrera se actualizará con los próximos avisos.</p>
                    </div>
                    <a href="/comunicados" class="aql-btn-ghost aql-btn-sm mt-6">Ir a comunicados</a>
                <?php endif; ?>
            </article>

            <div class="aql-news-stack">
                <article class="aql-news-card aql-reveal">
                    <?php if ($event): ?>
                        <div class="aql-news-row">
                            <div class="aql-news-date" aria-hidden="true">
                                <div>
                                    <strong><?= htmlspecialchars($eventDay, ENT_QUOTES, 'UTF-8') ?></strong>
                                    <span><?= htmlspecialchars($eventMonth, ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </div>
                            <div class="min-w-0">
                                <div class="aql-news-meta">
                                    <span><?= htmlspecialchars($eventCategory !== '' ? $eventCategory : 'Evento', ENT_QUOTES, 'UTF-8') ?></span>
                                    <?php if ($eventPlace !== ''): ?>
                                        <span>· <?= htmlspecialchars($eventPlace, ENT_QUOTES, 'UTF-8') ?></span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="aql-news-title mt-1"><?= htmlspecialchars($eventTitle, ENT_QUOTES, 'UTF-8') ?></h3>
                                <?php if ($eventExcerpt !== ''): ?>
                                    <p class="aql-news-copy mt-1"><?= htmlspecialchars($eventExcerpt, ENT_QUOTES, 'UTF-8') ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <a href="/eventos" class="aql-bento-link">Ver eventos y galerías →</a>
                    <?php else: ?>
                        <span class="aql-badge">Evento</span>
                        <h3 class="aql-news-title mt-2">Aún no hay eventos publicados</h3>
                        <p class="aql-news-copy">Consulta próximamente las actividades de extensión de la Carrera.</p>
                        <a href="/eventos" class="aql-bento-link">Ir a eventos →</a>
                    <?php endif; ?>
                </article>

                <article class="aql-news-card aql-reveal">
                    <?php if ($achievement): ?>
                        <div class="aql-card-kicker">
                            <span class="aql-badge aql-badge-gold">Logro</span>
                            <?php if ($achievementYear !== ''): ?>
                                <span class="aql-news-meta"><?= htmlspecialchars($achievementYear, ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 class="aql-news-title"><?= htmlspecialchars($achievementTitle, ENT_QUOTES, 'UTF-8') ?></h3>
                        <p class="aql-news-copy">
                            <?= htmlspecialchars($achievementPosition, ENT_QUOTES, 'UTF-8') ?>
                            <?php if ($achievementPlace !== ''): ?>
                                · <?= htmlspecialchars($achievementPlace, ENT_QUOTES, 'UTF-8') ?>
                            <?php endif; ?>
                        </p>
                        <a href="/logros" class="aql-bento-link">Ver línea de logros →</a>
                    <?php else: ?>
                        <span class="aql-badge aql-badge-gold">Logro</span>
                        <h3 class="aql-news-title mt-2">Aún no hay logros publicados</h3>
                        <p class="aql-news-copy">Los reconocimientos de la comunidad universitaria se publicarán aquí.</p>
                        <a href="/logros" class="aql-bento-link">Ir a logros →</a>
                    <?php endif; ?>
                </article>

                <?php if (!empty($announcementDocs)): ?>
                    <article class="aql-news-card aql-reveal">
                        <div class="aql-card-kicker">
                            <span class="aql-badge aql-badge-neutral">Documentos</span>
                            <span class="aql-news-meta">PDF</span>
                        </div>
                        <h3 class="aql-news-title">Comunicados recientes</h3>
                        <div class="grid gap-2">
                            <?php foreach ($announcementDocs as $doc): ?>
                                <?php
                                $docTitle = trim((string) ($doc['nom_com'] ?? 'Comunicado'));
                                $docDate = !empty($doc['fech_com']) ? date('d/m/Y', strtotime((string) $doc['fech_com'])) : '';
                                $docUrl = '/uploads/pdf/comunicados/' . $doc['doc_com'];
                                ?>
                                <a
                                    href="<?= htmlspecialchars($docUrl, ENT_QUOTES, 'UTF-8') ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="aql-doc-link"
                                    onclick='openPdfModal(<?= $jsString($docUrl) ?>, <?= $jsString($docTitle) ?>, <?= $jsString('Comunicado oficial') ?>, <?= $jsString($docDate) ?>); return false;'
                                >
                                    <span class="aql-doc-badge" aria-hidden="true">PDF</span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-[0.8125rem] font-medium text-[#0b132b]"><?= htmlspecialchars($docTitle, ENT_QUOTES, 'UTF-8') ?></span>
                                        <?php if ($docDate !== ''): ?>
                                            <span class="aql-news-meta"><?= htmlspecialchars($docDate, ENT_QUOTES, 'UTF-8') ?></span>
                                        <?php endif; ?>
                                    </span>
                                    <span class="aql-mono-sm text-[#2862d7]" aria-hidden="true">↗</span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <a href="/comunicados" class="aql-bento-link">Ver todos los comunicados →</a>
                    </article>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="aql-section">
    <div class="aql-container">
        <div class="aql-cta-panel aql-reveal">
            <div class="max-w-xl">
                <span class="aql-eyebrow">Admisión <?= htmlspecialchars((string) $admissionGestion, ENT_QUOTES, 'UTF-8') ?></span>
                <h2 class="aql-heading mt-2">Inicia tu formación en Ingeniería Informática</h2>
                <p class="aql-body mt-3">Consulta la convocatoria vigente, los requisitos y las modalidades de ingreso a la Carrera.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="/admision" class="aql-btn-primary">Ver admisión</a>
                <a href="/contacto" class="aql-btn-ghost bg-white">Contactar a la Carrera</a>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/public.php';
?>
