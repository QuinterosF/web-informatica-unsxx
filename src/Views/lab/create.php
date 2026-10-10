<?php
$title = 'Nuevo Laboratorio - Informática UNSXX';
$pageTitle = 'Nuevo Laboratorio';
ob_start();
?>

<!-- Controles Superiores -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Registrar Laboratorio</h2>
    <a href="/labs"
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

        <form action="/labs" method="POST" enctype="multipart/form-data" class="space-y-6">

            <div class="grid grid-cols-1 gap-6">

                <!-- Nombre del Laboratorio -->
                <div>
                    <label for="nombre_lab" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Laboratorio
                        <span class="text-red-500">*</span></label>
                    <input type="text" id="nombre_lab" name="nombre_lab" required
                        placeholder="Ej: Seguridad Informática"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all"
                        value="<?= htmlspecialchars($old['nombre_lab'] ?? '') ?>">
                </div>

                <!-- Docente Responsable -->
                <div>
                    <label for="resp_acad_id" class="block text-sm font-medium text-gray-700 mb-1">Docente
                        Responsable</label>
                    <select id="resp_acad_id" name="resp_acad_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all bg-white">
                        <option value="">-- Sin asignar --</option>
                        <?php if (!empty($academics)): ?>
                            <?php foreach ($academics as $academic): ?>
                                <?php $selected = ($old['resp_acad_id'] ?? '') == $academic['id_acad'] ? 'selected' : ''; ?>
                                <option value="<?= $academic['id_acad'] ?>" <?= $selected ?>>
                                    <?= htmlspecialchars($academic['nom_acad']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Opcional. Seleccione al docente a cargo del laboratorio.</p>
                </div>

                <!-- Archivo Horario (PDF) -->
                <div>
                    <label for="horario_lab" class="block text-sm font-medium text-gray-700 mb-1">Horario Oficial
                        (PDF)</label>
                    <div class="flex items-center gap-4">
                        <input type="file" id="horario_lab" name="horario_lab" accept="application/pdf"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-unsxx-blue-50 file:text-unsxx-blue-700 hover:file:bg-unsxx-blue-100 transition-all border border-gray-300 rounded-lg">
                        <span class="text-xs text-gray-500 w-full max-w-[200px]">Solo formato PDF (Máx. 5MB)</span>
                    </div>
                </div>

                <!-- Estado Activo -->
                <div class="flex items-center mt-2">
                    <input id="activo" name="activo" type="checkbox" value="1" checked
                        class="w-4 h-4 text-unsxx-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-unsxx-blue-500 focus:ring-2">
                    <label for="activo" class="ml-2 text-sm font-medium text-gray-900">Laboratorio Activo</label>
                </div>

            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100 mt-6">
                <a href="/labs"
                    class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm transition-colors">
                    Guardar Laboratorio
                </button>
            </div>

        </form>
    </div>
</div>

<!-- Librería Choices.js (CSS y JS) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<!-- Ajustes de estilo para fusionarlo con Tailwind y colores UNSXX -->
<style>
    .choices { margin-bottom: 0; }
    .choices__inner {
        background-color: #ffffff;
        border: 1px solid #d1d5db; /* border-gray-300 */
        border-radius: 0.5rem; /* rounded-lg */
        padding: 0.25rem 1rem;
        min-height: 42px;
        display: flex;
        align-items: center;
        font-size: 0.875rem; /* text-sm */
    }
    .choices[data-type*="select-one"] .choices__inner {
        padding-bottom: 0.25rem;
    }
    .choices.is-focused .choices__inner {
        border-color: #3b82f6; 
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
    .choices__list--dropdown .choices__item--selectable.is-highlighted {
        background-color: #eff6ff; /* hover background azul claro */
        color: #1e3a8a; /* texto azul oscuro UNSXX */
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectDocente = document.getElementById('resp_acad_id');
    
    if (selectDocente) {
        new Choices(selectDocente, {
            searchEnabled: true,
            searchPlaceholderValue: 'Escriba el nombre del docente...',
            itemSelectText: '', // Oculta el texto "Press to select" por defecto
            noResultsText: 'No se encontraron docentes',
            noChoicesText: 'No hay opciones disponibles',
            shouldSort: false, // Mantiene el orden alfabético que viene de tu consulta SQL
            position: 'bottom'
        });
    }
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>