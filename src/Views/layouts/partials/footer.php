<?php
$phonesLayout = $phonesLayout ?? [];
$serviceLinks = $serviceLinks ?? [];
$supportWaUrl = $supportWaUrl ?? '';
?>
<footer class="aql-footer">
    <div class="aql-container">
        <div class="aql-footer-grid">
            <div>
                <div class="aql-footer-title">
                    <img src="/assets/img/logo-informatica.png" alt="Escudo de Ingeniería Informática UNSXX" class="w-8 h-8 object-contain" width="32" height="32">
                    <span>Ingeniería Informática — UNSXX</span>
                </div>
                <p class="aql-footer-text mt-4">
                    Área Tecnología — Campus María Barzola (Carretera a Catavi). Dirección de Carrera — Edificio Bloque A, Primer Piso. Llallagua, Potosí, Bolivia.
                </p>
                <p class="aql-footer-text mt-2">
                    Calle Campero N°36, Edificio de la Transparencia. Teléfono institucional: 02-5820222.
                </p>
                <a href="https://unsxx.bo" target="_blank" rel="noopener noreferrer" class="aql-mono text-[#2862d7] mt-3 inline-flex hover:underline">
                    unsxx.bo ↗
                </a>
            </div>

            <div>
                <p class="aql-footer-heading">Secciones académicas</p>
                <div class="aql-footer-links">
                    <a href="/nosotros" class="aql-footer-link">Acerca de la Carrera</a>
                    <a href="/plan-estudios" class="aql-footer-link">Plan de Estudios</a>
                    <a href="/menciones" class="aql-footer-link">Menciones e IIDAI</a>
                    <a href="/laboratorios" class="aql-footer-link">Laboratorios</a>
                    <a href="/plantel" class="aql-footer-link">Plantel Docente</a>
                    <a href="/documentos" class="aql-footer-link">Documentos Oficiales</a>
                </div>
            </div>

            <div>
                <p class="aql-footer-heading">Contacto directo</p>
                <?php if (!empty($phonesLayout)): ?>
                    <div class="grid gap-2">
                        <?php foreach (array_slice($phonesLayout, 0, 3) as $phone): ?>
                            <?php
                            $phoneNumber = (string) ($phone['num_cel'] ?? '');
                            $cleanPhone = preg_replace('/\D+/', '', $phoneNumber);
                            if (strlen($cleanPhone) === 8) {
                                $cleanPhone = '591' . $cleanPhone;
                            }
                            ?>
                            <?php if ($cleanPhone !== ''): ?>
                                <a href="https://wa.me/<?= htmlspecialchars($cleanPhone, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" class="aql-phone-link">
                                    <span class="font-medium text-[#0b132b]"><?= htmlspecialchars($phone['nom_cel'] ?? 'Contacto', ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="aql-mono text-[#2862d7]"><?= htmlspecialchars($phoneNumber, ENT_QUOTES, 'UTF-8') ?> ↗</span>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($serviceLinks)): ?>
                    <p class="aql-footer-heading mt-5">Enlaces de interés</p>
                    <div class="aql-footer-links">
                        <?php foreach ($serviceLinks as $serviceLink): ?>
                            <?php if (empty($serviceLink['nombre']) || empty($serviceLink['url'])) continue; ?>
                            <a href="<?= htmlspecialchars($serviceLink['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" class="aql-footer-link">
                                <?= htmlspecialchars($serviceLink['nombre'], ENT_QUOTES, 'UTF-8') ?> ↗
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="aql-footer-bottom">
            <p>&copy; <?= date('Y') ?> Carrera de Ingeniería Informática — Universidad Nacional "Siglo XX".</p>
            <div class="flex items-center gap-4">
                <?php if ($supportWaUrl !== ''): ?>
                    <a href="<?= htmlspecialchars($supportWaUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" class="hover:text-[#0b132b]">Soporte técnico</a>
                    <span aria-hidden="true">·</span>
                <?php endif; ?>
                <a href="/login" target="_blank" rel="noopener noreferrer" class="hover:text-[#0b132b]">Intranet</a>
            </div>
        </div>
    </div>
</footer>

<?php if ($supportWaUrl !== ''): ?>
    <a href="<?= htmlspecialchars($supportWaUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" class="aql-floating-wa" aria-label="Escribir a soporte técnico por WhatsApp" title="Soporte técnico por WhatsApp">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>
    </a>
<?php endif; ?>
