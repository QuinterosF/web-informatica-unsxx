<?php
/** @var array $announcement */
$title = 'Editar Comunicado - Informática UNSXX';
$pageTitle = 'Editar Comunicado';
ob_start();
?>

<!-- Controles Superiores -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Modificar Comunicado</h2>
    <a href="/announcements"
        class="inline-flex items-center gap-2 text-unsxx-blue-600 hover:text-unsxx-blue-800 font-medium ml-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Volver al listado
    </a>
</div>

<!-- Contenedor del Formulario -->
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
    <div class="p-6 sm:p-8">

        <form action="/announcements/<?= $announcement['id_com'] ?>/edit" method="POST" enctype="multipart/form-data"
            class="space-y-6">

            <div class="grid grid-cols-1 gap-6">

                <!-- Título del Comunicado -->
                <div>
                    <label for="nom_com" class="block text-sm font-medium text-gray-700 mb-1">Título del Comunicado
                        <span class="text-red-500">*</span></label>
                    <input type="text" id="nom_com" name="nom_com" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all"
                        value="<?= htmlspecialchars($old['nom_com'] ?? $announcement['nom_com'] ?? '') ?>">
                </div>

                <!-- Fecha de Publicación -->
                <div>
                    <label for="fech_com" class="block text-sm font-medium text-gray-700 mb-1">Fecha del Comunicado
                        <span class="text-red-500">*</span></label>
                    <input type="date" id="fech_com" name="fech_com" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all text-gray-700"
                        value="<?= htmlspecialchars($old['fech_com'] ?? $announcement['fech_com'] ?? '') ?>">
                </div>

                <!-- Descripción / Contenido (AHORA OPCIONAL) -->
                <div>
                    <label for="desc_com" class="block text-sm font-medium text-gray-700 mb-1">Contenido del Comunicado
                        <span class="text-xs text-gray-400 font-normal">(Opcional)</span></label>
                    <textarea id="desc_com" name="desc_com" rows="5"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all resize-y"><?= htmlspecialchars($old['desc_com'] ?? $announcement['desc_com'] ?? '') ?></textarea>
                </div>

                <!-- SECCIÓN DE ARCHIVOS ADJUNTOS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-xl border border-gray-200">

                    <!-- Bloque de Imagen de Portada -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-3">
                            <i class="fas fa-image text-blue-500 mr-2"></i> Imagen de Portada
                        </label>

                        <?php
                        $fotoCom = !empty($announcement['foto_com']) ? $announcement['foto_com'] : 'default.png';
                        $isDefault = empty($announcement['foto_com']);
                        ?>
                        <div class="mb-4 bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                            <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">Imagen Actual
                                <?= $isDefault ? '(Por Defecto)' : '' ?>:</p>
                            <div class="flex items-start gap-4">
                                <img src="/uploads/img/comunicados/<?= htmlspecialchars($fotoCom) ?>" alt="Portada"
                                    class="w-24 h-24 object-cover rounded shadow-sm border border-gray-200 bg-gray-100">
                                <div class="flex flex-col justify-between py-1">
                                    <a href="/uploads/img/comunicados/<?= htmlspecialchars($fotoCom) ?>" target="_blank"
                                        class="text-sm text-blue-600 hover:underline font-medium break-all line-clamp-2">
                                        Ver imagen <?= $isDefault ? 'por defecto' : 'original' ?>
                                    </a>

                                    <?php if (!$isDefault): // Solo mostramos el botón de borrar si NO es la imagen por defecto ?>
                                        <label class="inline-flex items-center mt-3 cursor-pointer group">
                                            <input type="checkbox" name="remove_foto_com" value="1"
                                                class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 cursor-pointer">
                                            <span
                                                class="ml-2 text-xs font-semibold text-red-600 group-hover:text-red-800 transition-colors">Eliminar
                                                imagen actual</span>
                                        </label>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <label for="foto_com" class="block text-xs font-medium text-gray-600 mb-1">Subir nueva imagen
                            (JPG, PNG, WebP):</label>
                        <input type="file" id="foto_com" name="foto_com" accept="image/jpeg, image/png, image/webp"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg bg-white transition-all cursor-pointer">
                    </div>

                    <!-- Bloque de Documento PDF -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-3">
                            <i class="fas fa-file-pdf text-red-500 mr-2"></i> Documento Adjunto (PDF)
                        </label>

                        <?php if (!empty($announcement['doc_com'])): ?>
                            <div class="mb-4 bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">Documento
                                    Actual:</p>
                                <div class="flex items-center gap-3 bg-red-50 p-3 rounded border border-red-100">
                                    <i class="fas fa-file-pdf text-2xl text-red-500"></i>
                                    <div class="flex-1 min-w-0">
                                        <a href="/uploads/pdf/comunicados/<?= htmlspecialchars($announcement['doc_com']) ?>"
                                            target="_blank"
                                            class="text-sm font-semibold text-red-700 hover:underline truncate block">
                                            <?= htmlspecialchars($announcement['doc_com']) ?>
                                        </a>
                                    </div>
                                </div>
                                <label class="inline-flex items-center mt-3 cursor-pointer group px-1">
                                    <input type="checkbox" name="remove_doc_com" value="1"
                                        class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 cursor-pointer">
                                    <span
                                        class="ml-2 text-xs font-semibold text-red-600 group-hover:text-red-800 transition-colors">Eliminar
                                        PDF actual</span>
                                </label>
                            </div>
                        <?php endif; ?>

                        <label for="doc_com" class="block text-xs font-medium text-gray-600 mb-1">Subir nuevo PDF para
                            reemplazar:</label>
                        <input type="file" id="doc_com" name="doc_com" accept="application/pdf"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 border border-gray-300 rounded-lg bg-white transition-all cursor-pointer">
                    </div>
                </div>

                <!-- Estado del Comunicado (Checkbox) -->
                <div class="flex items-center mt-2 p-3 bg-blue-50 rounded-lg border border-blue-100">
                    <?php $isChecked = ($old['estado'] ?? $announcement['estado'] ?? 0) == 1 ? 'checked' : ''; ?>
                    <input id="estado" name="estado" type="checkbox" value="1" <?= $isChecked ?>
                        class="w-5 h-5 text-unsxx-blue-600 bg-white border-gray-300 rounded focus:ring-unsxx-blue-500 focus:ring-2 cursor-pointer">
                    <label for="estado" class="ml-3 flex flex-col cursor-pointer">
                        <span class="text-sm font-bold text-unsxx-blue-900">Publicar Inmediatamente</span>
                        <span class="text-xs text-unsxx-blue-700">Si desmarca esta opción, se guardará como borrador
                            (amarillo) y no será visible para los estudiantes.</span>
                    </label>
                </div>

            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100 mt-6">
                <a href="/announcements"
                    class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm transition-colors">
                    Actualizar Comunicado
                </button>
            </div>

        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>