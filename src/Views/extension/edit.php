<?php
/** @var array $extension */
$title = 'Editar Actividad de Extensión - Informática UNSXX';
$pageTitle = 'Editar Actividad de Extensión';
ob_start();
?>

<!-- Controles Superiores -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Modificar Actividad</h2>
    <a href="/extensions"
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

        <!-- Importante: Asegúrate de que la variable coincida con la que envía tu controlador. Usualmente es $extension -->
        <form action="/extensions/<?= $extension['id_ext'] ?>/edit" method="POST" class="space-y-6">

            <div class="grid grid-cols-1 gap-6">

                <!-- Nombre de la Actividad -->
                <div>
                    <label for="nom_ext" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Actividad
                        <span class="text-red-500">*</span></label>
                    <input type="text" id="nom_ext" name="nom_ext" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all"
                        value="<?= htmlspecialchars($old['nom_ext'] ?? $extension['nom_ext'] ?? '') ?>">
                </div>

                <!-- Fecha -->
                <div>
                    <label for="fech_ext" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Ejecución <span
                            class="text-red-500">*</span></label>
                    <input type="date" id="fech_ext" name="fech_ext" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all text-gray-700"
                        value="<?= htmlspecialchars($old['fech_ext'] ?? $extension['fech_ext'] ?? '') ?>">
                </div>

                <!-- Descripción -->
                <div>
                    <label for="desc_ext" class="block text-sm font-medium text-gray-700 mb-1">Descripción Detallada
                        <span class="text-red-500">*</span></label>
                    <textarea id="desc_ext" name="desc_ext" required rows="5"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all resize-y"><?= htmlspecialchars($old['desc_ext'] ?? $extension['desc_ext'] ?? '') ?></textarea>
                </div>

            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100 mt-6">
                <a href="/extensions"
                    class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm transition-colors">
                    Actualizar Actividad
                </button>
            </div>

        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>