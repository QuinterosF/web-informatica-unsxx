<?php
$title = 'Portadas de Inicio - Informática UNSXX';
$pageTitle = 'Fotos Institucionales (Carrusel de Inicio)';
ob_start();
?>

<!-- Cabecera + Zona de Subida Compacta -->
<div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100 p-5 mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-unsxx-blue-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-unsxx-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Agregar nuevas imágenes al carrusel
            </h2>
            <p class="text-xs text-gray-500 mt-0.5">Formato horizontal recomendado (1920x1080px). Puedes seleccionar varias a la vez.</p>
        </div>

        <form action="/portadas" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <input type="file" id="fotos" name="fotos[]" multiple accept="image/jpeg, image/png, image/webp" required
                class="block w-full sm:w-72 text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-unsxx-blue-50 file:text-unsxx-blue-700 hover:file:bg-unsxx-blue-100 transition-all border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
            <button type="submit"
                class="px-4 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold text-xs rounded-lg shadow-sm transition-colors whitespace-nowrap flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Subir Fotos
            </button>
        </form>
    </div>
</div>

<?php if (!empty($portadas)): ?>

    <!-- Barra Flotante de Cambios Pendientes (Oculta por defecto, aparece al mover, editar o cambiar visibilidad) -->
    <div id="unsavedBanner" class="hidden sticky top-16 z-30 mb-4 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-xl shadow-md items-center justify-between gap-4 transition-all">
        <div class="flex items-center gap-3">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600 font-bold">!</span>
            <div>
                <p class="text-sm font-bold text-amber-900">Tienes cambios pendientes sin guardar</p>
                <p class="text-xs text-amber-700">Modificaste el orden, los títulos o la visibilidad. Haz clic en "Guardar Cambios" para conservarlos.</p>
            </div>
        </div>
        <button type="button" onclick="submitBatchForm()"
            class="px-5 py-2 bg-unsxx-blue-900 hover:bg-unsxx-blue-800 text-white font-bold text-xs rounded-lg shadow-sm transition-colors shrink-0 flex items-center gap-2 cursor-pointer">
            <svg class="w-4 h-4 text-unsxx-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Guardar Cambios
        </button>
    </div>

    <!-- Contenedor Principal de la Lista -->
    <div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
        
        <!-- Cabecera de la Lista + Botón Permanente de Guardado -->
        <div class="px-5 py-3.5 bg-unsxx-blue-900 text-white flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider">Secuencia del Carrusel (<?= count($portadas) ?> imágenes)</span>
                <span class="hidden sm:inline-block text-[11px] text-blue-200 font-normal">• Ordena, edita títulos o cambia visibilidad y guarda todo junto</span>
            </div>
            <button type="button" id="saveTopBtn" onclick="submitBatchForm()"
                class="px-4 py-1.5 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold text-xs rounded-lg shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                Guardar Cambios
            </button>
        </div>

        <!-- Formulario Único en Lote (Envía Orden, Títulos y Visibilidad) -->
        <form id="batchForm" action="/portadas/batch-update" method="POST" class="m-0">
            <div id="portadasList" class="divide-y divide-gray-100">
                <?php foreach ($portadas as $index => $portada): ?>
                    <?php 
                    $id = (int)$portada['id_portada'];
                    $esActivo = $portada['activo'] == 1; 
                    $imgUrl = '/uploads/img/portadas/' . htmlspecialchars($portada['archivo_url']);
                    ?>
                    <div class="portada-row flex flex-col sm:flex-row items-start sm:items-center gap-4 px-5 py-3.5 hover:bg-blue-50/40 transition-colors <?= !$esActivo ? 'bg-gray-50/80 opacity-65' : '' ?>" data-id="<?= $id ?>">
                        
                        <!-- 1. Controles de Orden (Drag Handle + Posición + Flechas Subir/Bajar) -->
                        <div class="flex items-center gap-2 shrink-0">
                            <div class="drag-handle cursor-grab active:cursor-grabbing text-gray-400 hover:text-unsxx-blue-700 p-1" title="Arrastrar para reordenar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                </svg>
                            </div>

                            <span class="order-badge w-7 h-7 rounded-lg bg-unsxx-blue-50 border border-unsxx-blue-200 text-unsxx-blue-900 font-bold text-xs flex items-center justify-center">
                                <?= $index + 1 ?>
                            </span>

                            <!-- Input oculto de Orden -->
                            <input type="hidden" name="orden[<?= $id ?>]" value="<?= $index + 1 ?>" class="order-input">

                            <!-- Botones Subir / Bajar -->
                            <div class="flex flex-col border border-gray-200 rounded-md overflow-hidden bg-white shadow-2xs">
                                <button type="button" onclick="moveRow(this, -1)" title="Subir posición"
                                    class="btn-up px-1.5 py-0.5 text-gray-500 hover:bg-unsxx-blue-50 hover:text-unsxx-blue-700 disabled:opacity-30 disabled:pointer-events-none transition-colors border-b border-gray-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path></svg>
                                </button>
                                <button type="button" onclick="moveRow(this, 1)" title="Bajar posición"
                                    class="btn-down px-1.5 py-0.5 text-gray-500 hover:bg-unsxx-blue-50 hover:text-unsxx-blue-700 disabled:opacity-30 disabled:pointer-events-none transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- 2. Miniatura Panorámica Compacta (16:9) -->
                        <div class="relative w-28 h-16 rounded-lg overflow-hidden border border-gray-200 bg-gray-100 shrink-0 group cursor-pointer"
                             onclick="openPreviewModal('<?= $imgUrl ?>')">
                            <img src="<?= $imgUrl ?>" alt="Miniatura" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- 3. Campo de Título Limpio (Edición en línea) -->
                        <div class="flex-1 w-full">
                            <div class="relative">
                                <input type="text" name="titulo[<?= $id ?>]" 
                                    value="<?= htmlspecialchars($portada['titulo'] ?? '') ?>" 
                                    placeholder="Sin título (Haz clic aquí para añadir un texto descriptivo opcional...)"
                                    oninput="markAsDirty()"
                                    class="w-full text-sm text-gray-800 placeholder-gray-400 bg-transparent hover:bg-gray-50 focus:bg-white border border-transparent hover:border-gray-200 focus:border-unsxx-blue-500 rounded-lg px-3 py-1.5 outline-none transition-all">
                            </div>
                            <span class="block text-[11px] text-gray-400 px-3 mt-0.5 font-mono">
                                Archivo: <?= htmlspecialchars($portada['archivo_url']) ?>
                            </span>
                        </div>

                        <!-- 4. Acciones (Visibilidad en Lote + Eliminar) -->
                        <div class="flex items-center gap-4 self-end sm:self-center shrink-0">
                            <!-- Input oculto de Visibilidad (se envía en el lote) -->
                            <input type="hidden" name="activo[<?= $id ?>]" value="<?= $esActivo ? 1 : 0 ?>" class="active-input">

                            <!-- Botón Toggle en Cliente (No recarga la página) -->
                            <button type="button" onclick="toggleVisibilityClient(this)"
                                title="Clic para alternar visibilidad (Recuerda guardar cambios)"
                                class="visibility-btn inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border transition-colors cursor-pointer <?= $esActivo ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100' : 'bg-gray-100 text-gray-500 border-gray-300 hover:bg-gray-200' ?>">
                                <span class="visibility-dot w-2 h-2 rounded-full <?= $esActivo ? 'bg-green-500' : 'bg-gray-400' ?>"></span>
                                <span class="visibility-text"><?= $esActivo ? 'Visible' : 'Oculto' ?></span>
                            </button>

                            <!-- Botón Eliminar -->
                            <button type="button" onclick="confirmDeletePortada('/portadas/<?= $id ?>/delete')"
                                title="Eliminar imagen permanentemente"
                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </form>
    </div>

    <!-- Formulario auxiliar oculto solo para Eliminar -->
    <form id="singleActionForm" method="POST" class="hidden"></form>

<?php else: ?>
    <div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100 p-14 text-center">
        <h3 class="text-lg font-bold text-gray-900 mb-1">Aún no hay portadas en el carrusel</h3>
        <p class="text-sm text-gray-500">Sube imágenes en el panel superior para comenzar a armar la secuencia de inicio.</p>
    </div>
<?php endif; ?>

<!-- Modal Ligero para Previsualizar Imagen en Grande -->
<div id="previewModal" onclick="closePreviewModal()" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-xs p-4 cursor-zoom-out">
    <img id="previewModalImg" src="" alt="Vista previa" class="max-w-full max-h-[85vh] rounded-xl shadow-2xl border border-white/20">
</div>

<!-- SortableJS para permitir arrastrar filas suavemente -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    let isDirty = false;

    function markAsDirty() {
        if (!isDirty) {
            isDirty = true;
            const banner = document.getElementById('unsavedBanner');
            if (banner) {
                banner.classList.remove('hidden');
                banner.classList.add('flex');
            }
        }
    }

    function submitBatchForm() {
        isDirty = false; // Evitar alerta de salida al guardar
        document.getElementById('batchForm').submit();
    }

    // Cambia el estado Visible / Oculto instantáneamente en el DOM sin recargar
    function toggleVisibilityClient(btn) {
        const row = btn.closest('.portada-row');
        const input = row.querySelector('.active-input');
        const dot = btn.querySelector('.visibility-dot');
        const text = btn.querySelector('.visibility-text');

        const currentlyActive = input.value === '1';
        const nextActive = !currentlyActive;

        input.value = nextActive ? '1' : '0';

        if (nextActive) {
            row.classList.remove('bg-gray-50/80', 'opacity-65');
            btn.className = 'visibility-btn inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border transition-colors cursor-pointer bg-green-50 text-green-700 border-green-200 hover:bg-green-100';
            dot.className = 'visibility-dot w-2 h-2 rounded-full bg-green-500';
            text.textContent = 'Visible';
        } else {
            row.classList.add('bg-gray-50/80', 'opacity-65');
            btn.className = 'visibility-btn inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border transition-colors cursor-pointer bg-gray-100 text-gray-500 border-gray-300 hover:bg-gray-200';
            dot.className = 'visibility-dot w-2 h-2 rounded-full bg-gray-400';
            text.textContent = 'Oculto';
        }

        markAsDirty();
    }

    // Recalcula los números 1, 2, 3... y actualiza los inputs ocultos
    function refreshOrderNumbers() {
        const rows = document.querySelectorAll('#portadasList .portada-row');
        rows.forEach((row, idx) => {
            const position = idx + 1;
            row.querySelector('.order-badge').textContent = position;
            row.querySelector('.order-input').value = position;
            row.querySelector('.btn-up').disabled = (idx === 0);
            row.querySelector('.btn-down').disabled = (idx === rows.length - 1);
        });
    }

    // Mover fila con botones ▲ / ▼
    function moveRow(btn, direction) {
        const row = btn.closest('.portada-row');
        const list = document.getElementById('portadasList');
        if (direction === -1 && row.previousElementSibling) {
            list.insertBefore(row, row.previousElementSibling);
        } else if (direction === 1 && row.nextElementSibling) {
            list.insertBefore(row.nextElementSibling, row);
        }
        refreshOrderNumbers();
        markAsDirty();
    }

    // Eliminar imagen (advierte si hay cambios sin guardar en el lote)
    function confirmDeletePortada(url) {
        const warningExtra = isDirty 
            ? "\n\n⚠️ Nota: Tienes cambios de orden/títulos sin guardar que se perderán al recargar." 
            : "";

        Swal.fire({
            title: '¿Eliminar imagen?',
            text: "Se borrará físicamente del servidor." + warningExtra,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#3b82f6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                isDirty = false;
                const form = document.getElementById('singleActionForm');
                form.action = url;
                form.submit();
            }
        });
    }

    // Modal de vista previa
    function openPreviewModal(src) {
        document.getElementById('previewModalImg').src = src;
        const modal = document.getElementById('previewModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePreviewModal() {
        const modal = document.getElementById('previewModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Prevención de pérdida accidental si intenta cerrar la pestaña o navegar a otro módulo sin guardar
    window.addEventListener('beforeunload', function (e) {
        if (isDirty) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        refreshOrderNumbers();

        const list = document.getElementById('portadasList');
        if (list) {
            new Sortable(list, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'bg-yellow-50',
                onEnd: function () {
                    refreshOrderNumbers();
                    markAsDirty();
                }
            });
        }
    });
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>