<?php
/** @var array $document */
$title = 'Editar Documento - Informática UNSXX';
$pageTitle = 'Modificar Documento Oficial';
ob_start();
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Editar Registro</h2>
    <a href="/documents"
        class="inline-flex items-center gap-2 text-unsxx-blue-600 hover:text-unsxx-blue-800 font-medium ml-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Volver al listado
    </a>
</div>

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
    <div class="p-6 sm:p-8">

        <form action="/documents/<?= $document['id_doc'] ?>/edit" method="POST" enctype="multipart/form-data"
            class="space-y-6">

            <!-- Título -->
            <div>
                <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título del Documento <span
                        class="text-red-500">*</span></label>
                <input type="text" id="titulo" name="titulo" required
                    value="<?= htmlspecialchars($old['titulo'] ?? $document['titulo'] ?? '') ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Categoría -->
                <div>
                    <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">Categoría <span
                            class="text-red-500">*</span></label>
                    <?php $catActual = $old['categoria'] ?? $document['categoria'] ?? ''; ?>
                    <select id="categoria" name="categoria" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all bg-white">
                        <option value="">Seleccione una categoría</option>
                        <?php $catActual = $old['categoria'] ?? $document['categoria'] ?? ''; ?>
                        <option value="plan_estudios" <?= $catActual === 'plan_estudios' ? 'selected' : '' ?>>Plan de
                            Estudios / Malla Curricular</option>
                        <option value="horario_clases" <?= $catActual === 'horario_clases' ? 'selected' : '' ?>>Horario de
                            Clases</option>
                        <option value="reglamento_titulacion" <?= $catActual === 'reglamento_titulacion' ? 'selected' : '' ?>>Reglamento de Titulación</option>
                        <option value="reglamento_admision" <?= $catActual === 'reglamento_admision' ? 'selected' : '' ?>>Reglamento de Admisión</option>
                        <option value="reglamento_interno" <?= $catActual === 'reglamento_interno' ? 'selected' : '' ?>>
                            Reglamentos Internos</option>
                        <option value="reglamento_macro" <?= $catActual === 'reglamento_macro' ? 'selected' : '' ?>>
                            Reglamentos Macro</option>
                        <option value="otro" <?= $catActual === 'otro' ? 'selected' : '' ?>>Otro Documento</option>
                    </select>
                </div>

                <!-- Gestión -->
                <div>
                    <label for="gestion" class="block text-sm font-medium text-gray-700 mb-1">Vigente desde la gestión <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="gestion" name="gestion" required
                        value="<?= htmlspecialchars($old['gestion'] ?? $document['gestion'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all">
                </div>
            </div>

            <!-- Archivo PDF -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <label for="archivo_pdf" class="block text-sm font-medium text-gray-700 mb-2">Reemplazar Archivo PDF
                    (Opcional)</label>

                <?php if (!empty($document['archivo_pdf'])): ?>
                    <div class="mb-3 p-2 bg-white rounded border border-gray-200 flex items-center justify-between">
                        <span class="text-xs text-red-700 font-medium truncate max-w-xs flex items-center gap-1">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            <?= htmlspecialchars($document['archivo_pdf']) ?>
                        </span>
                        <a href="/uploads/pdf/documents/<?= urlencode($document['archivo_pdf']) ?>" target="_blank"
                            class="text-xs text-blue-600 hover:underline">Ver actual</a>
                    </div>
                <?php endif; ?>

                <input type="file" id="archivo_pdf" name="archivo_pdf" accept="application/pdf"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-unsxx-blue-50 file:text-unsxx-blue-700 hover:file:bg-unsxx-blue-100 transition-all border border-gray-300 rounded-lg bg-white">
                <p class="text-xs text-gray-500 mt-1">Sube un nuevo PDF únicamente si deseas reemplazar el actual.</p>
            </div>

            <!-- Estado -->
            <div class="flex items-center gap-2">
                <input type="checkbox" id="estado" name="estado" value="1" <?= (!isset($document['estado']) || $document['estado']) ? 'checked' : '' ?>
                    class="w-4 h-4 text-unsxx-blue-600 rounded border-gray-300 focus:ring-unsxx-blue-500">
                <label for="estado" class="text-sm font-medium text-gray-700">Documento activo y accesible en el
                    portal</label>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100 mt-6">
                <a href="/documents"
                    class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm transition-colors">
                    Actualizar Documento
                </button>
            </div>

        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>