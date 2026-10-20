<?php
$title = 'Registrar Logro - Informática UNSXX';
$pageTitle = 'Añadir Nuevo Logro a la Historia';
ob_start();
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900">Detalles de la Competencia o Premio</h2>
    <a href="/logros" class="inline-flex items-center gap-2 text-unsxx-blue-600 hover:text-unsxx-blue-800 font-medium">Volver</a>
</div>

<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden p-6 sm:p-8">
    <form action="/logros" method="POST" enctype="multipart/form-data" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Posición Alcanzada <span class="text-red-500">*</span></label>
                <select name="posicion" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none">
                    <option value="1er Lugar">🥇 1er Lugar</option>
                    <option value="2do Lugar">🥈 2do Lugar</option>
                    <option value="3er Lugar">🥉 3er Lugar</option>
                    <option value="Reconocimiento">🏅 Reconocimiento</option>
                    <option value="Mención Honorífica">🏅 Mención Honorífica</option>
                    <option value="Otro">🏆 Otro</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Evento <span class="text-red-500">*</span></label>
                <input type="text" name="nom_log" required placeholder="Ej: Congreso Nacional CCBOL" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoría <span class="text-gray-400">(Opcional)</span></label>
                <input type="text" name="categoria_logro" placeholder="Ej: Categoría Profesional, Categoría Estudiantes" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Año de Obtención <span class="text-red-500">*</span></label>
                <input type="number" name="fech_log" required min="1990" max="<?= date('Y') ?>" placeholder="Ej: 2013" value="<?= date('Y') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none font-mono">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Lugar (Ciudad, País) <span class="text-gray-400">(Opcional)</span></label>
                <input type="text" name="lugar_log" placeholder="Ej: Huatusco - Veracruz - México" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Fotografía del Logro <span class="text-gray-400">(Opcional)</span></label>
                <input type="file" name="foto_logro" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-unsxx-blue-50 file:text-unsxx-blue-700 border border-gray-300 rounded-lg">
            </div>
        </div>
        <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
            <a href="/logros" class="px-6 py-2 border text-gray-700 rounded-lg hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="px-6 py-2 bg-unsxx-yellow-400 text-unsxx-blue-900 font-bold rounded-lg shadow-sm">Guardar Logro</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>