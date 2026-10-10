<?php
$title = 'Nuevo Laboratorio - Informática UNSXX';
$pageTitle = 'Nuevo Laboratorio';
ob_start();
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Registrar Nuevo Laboratorio</h2>
    <a href="/labs" class="inline-flex items-center gap-2 text-unsxx-blue-600 hover:text-unsxx-blue-800 font-medium ml-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver al listado
    </a>
</div>

<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
    <div class="p-6 sm:p-8">
        <form action="/labs" method="POST" enctype="multipart/form-data">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nombre Laboratorio -->
                <div>
                    <label for="nombre_lab" class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Laboratorio <span class="text-red-500">*</span></label>
                    <input type="text" id="nombre_lab" name="nombre_lab" required 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-white" 
                           value="<?= htmlspecialchars($old['nombre_lab'] ?? '') ?>">
                </div>

                <!-- Docente Responsable -->
                <div>
                    <label for="resp_acad_id" class="block text-sm font-semibold text-gray-700 mb-2">Responsable (Docente) <span class="text-red-500">*</span></label>
                    <select id="resp_acad_id" name="resp_acad_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-white">
                        <option value="">Seleccione un docente...</option>
                        <?php if (!empty($academics)): ?>
                            <?php foreach ($academics as $acad): ?>
                                <option value="<?= $acad['id_acad'] ?>" <?= (isset($old['resp_acad_id']) && $old['resp_acad_id'] == $acad['id_acad']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($acad['nom_acad']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <!-- PDF Horario y Estado -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="horario_lab" class="block text-sm font-semibold text-gray-700 mb-2">Horario Oficial (PDF Opcional)</label>
                        <input type="file" id="horario_lab" name="horario_lab" accept="application/pdf" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-unsxx-blue-50 file:text-unsxx-blue-700 hover:file:bg-unsxx-blue-100 transition-all border border-gray-300 rounded-lg bg-white">
                        <p class="text-xs text-gray-500 mt-2">Solo formato PDF. Máx. 2MB.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Estado del Laboratorio</label>
                        <label class="inline-flex items-center cursor-pointer mt-2">
                            <input type="checkbox" name="activo" value="1" class="sr-only peer" <?= !isset($old) || (isset($old['activo']) && $old['activo']) ? 'checked' : '' ?>>
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                            <span class="ms-3 text-sm font-medium text-gray-700 peer-checked:text-green-600">Activo (En uso)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                <a href="/labs" class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm transition-colors">
                    Guardar Laboratorio
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tom Select CDN y Configuración de Búsqueda -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<style>
    /* Ajustes para que el buscador coincida con el estilo Tailwind de la UNSXX */
    .ts-control {
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        border-color: #d1d5db;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        font-size: 0.875rem;
        background-color: #ffffff;
    }
    .ts-control.focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
    .ts-dropdown {
        border-radius: 0.375rem;
        border-color: #d1d5db;
        font-size: 0.875rem;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const selectDocente = document.getElementById("resp_acad_id");
        if (selectDocente) {
            new TomSelect(selectDocente, {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: "Buscar por nombre del docente..."
            });
        }
    });
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>