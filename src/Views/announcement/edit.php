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
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
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

                <!-- Descripción / Contenido -->
                <div>
                    <label for="desc_com" class="block text-sm font-medium text-gray-700 mb-1">Contenido del Comunicado
                        <span class="text-red-500">*</span></label>
                    <textarea id="desc_com" name="desc_com" required rows="5"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all resize-y"><?= htmlspecialchars($old['desc_com'] ?? $announcement['desc_com'] ?? '') ?></textarea>
                </div>

                <!-- Archivos Adjuntos (Grid interno 2 columnas) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 rounded-lg border border-gray-100">

                    <!-- Imagen de Portada -->
                    <div>
                        <label for="foto_com" class="block text-sm font-medium text-gray-700 mb-2">Imagen de
                            Portada</label>

                        <?php if (!empty($announcement['foto_com'])): ?>
                            <div class="mb-3 p-2 bg-white rounded border border-gray-200 flex items-center justify-between">
                                <span class="text-xs text-unsxx-blue-800 truncate max-w-[150px]"
                                    title="<?= htmlspecialchars($announcement['foto_com']) ?>">
                                    <svg class="w-4 h-4 inline mr-1 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <?= htmlspecialchars($announcement['foto_com']) ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <input type="file" id="foto_com" name="foto_com" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-unsxx-blue-50 file:text-unsxx-blue-700 hover:file:bg-unsxx-blue-100 transition-all border border-gray-300 rounded-lg bg-white">
                        <p class="text-xs text-gray-500 mt-1">Subir nueva para reemplazar</p>
                    </div>

                    <!-- Documento PDF -->
                    <div>
                        <label for="doc_com" class="block text-sm font-medium text-gray-700 mb-2">Documento Adjunto
                            (PDF)</label>

                        <?php if (!empty($announcement['doc_com'])): ?>
                            <div class="mb-3 p-2 bg-white rounded border border-gray-200 flex items-center justify-between">
                                <span class="text-xs text-unsxx-blue-800 truncate max-w-[150px]"
                                    title="<?= htmlspecialchars($announcement['doc_com']) ?>">
                                    <svg class="w-4 h-4 inline mr-1 text-red-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <?= htmlspecialchars($announcement['doc_com']) ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <input type="file" id="doc_com" name="doc_com" accept="application/pdf"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-unsxx-blue-50 file:text-unsxx-blue-700 hover:file:bg-unsxx-blue-100 transition-all border border-gray-300 rounded-lg bg-white">
                        <p class="text-xs text-gray-500 mt-1">Subir nuevo para reemplazar</p>
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

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>