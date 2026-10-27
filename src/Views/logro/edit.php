<?php
$title = 'Editar Logro - Informática UNSXX';
$pageTitle = 'Editar Registro de Logro';
ob_start();

// Extraer solo el año de la fecha almacenada (ej: '2013-01-01' -> '2013')
$anioActual = date('Y', strtotime($logro['fech_log']));
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900">Editar: <?= htmlspecialchars($logro['nom_log']) ?></h2>
    <a href="/admin/logros" class="inline-flex items-center gap-2 text-unsxx-blue-600 hover:text-unsxx-blue-800 font-medium">Volver</a>
</div>

<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden p-6 sm:p-8">
    <form action="/admin/logros/<?= $logro['id_log'] ?>/edit" method="POST" enctype="multipart/form-data" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Posición Alcanzada <span class="text-red-500">*</span></label>
                <select name="posicion" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none">
                    <?php 
                    $opciones = ['1er Lugar', '2do Lugar', '3er Lugar', 'Reconocimiento', 'Mención Honorífica', 'Otro'];
                    foreach($opciones as $op) {
                        $sel = ($logro['posicion'] == $op) ? 'selected' : '';
                        echo "<option value=\"$op\" $sel>$op</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Evento <span class="text-red-500">*</span></label>
                <input type="text" name="nom_log" required value="<?= htmlspecialchars($logro['nom_log']) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoría <span class="text-gray-400">(Opcional)</span></label>
                <input type="text" name="categoria_logro" value="<?= htmlspecialchars($logro['categoria_logro'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Año de Obtención <span class="text-red-500">*</span></label>
                <input type="number" name="fech_log" required min="1990" max="<?= date('Y') ?>" value="<?= htmlspecialchars($anioActual) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none font-mono">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Lugar (Ciudad, País) <span class="text-gray-400">(Opcional)</span></label>
                <input type="text" name="lugar_log" value="<?= htmlspecialchars($logro['lugar_log'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Fotografía del Logro</label>
                
                <?php if (!empty($logro['foto_logro'])): ?>
                    <div class="mb-4 flex flex-col items-start gap-3 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <div class="flex items-center gap-4">
                            <img src="/uploads/img/logros/<?= htmlspecialchars($logro['foto_logro']) ?>" class="w-20 h-20 object-cover rounded shadow-sm border bg-white">
                            <div class="text-sm text-gray-600">
                                <p class="font-semibold text-gray-800">Foto actual en servidor.</p>
                                <p class="text-xs">Si subes una nueva foto abajo, esta se reemplazará automáticamente.</p>
                            </div>
                        </div>
                        
                        <!-- Checkbox para eliminar la foto actual -->
                        <label class="inline-flex items-center cursor-pointer bg-red-50 px-3 py-2 rounded-md border border-red-200 hover:bg-red-100 transition-colors w-full sm:w-auto">
                            <input type="checkbox" name="eliminar_foto" value="1" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 focus:ring-2 cursor-pointer">
                            <span class="ml-2 text-sm font-bold text-red-700">Eliminar foto permanentemente (dejar el logro sin imagen)</span>
                        </label>
                    </div>
                <?php endif; ?>
                
                <input type="file" name="foto_logro" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-unsxx-blue-50 file:text-unsxx-blue-700 border border-gray-300 rounded-lg bg-white">
            </div>
        </div>
        
        <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
            <a href="/admin/logros" class="px-6 py-2 border text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">Cancelar</a>
            <button type="submit" class="px-6 py-2 bg-unsxx-yellow-400 text-unsxx-blue-900 font-bold rounded-lg shadow-sm hover:bg-unsxx-yellow-500 transition-colors">Actualizar Logro</button>
        </div>
    </form>
</div>

<!-- Script para deshabilitar el input file si se marca "eliminar" -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxEliminar = document.querySelector('input[name="eliminar_foto"]');
        const inputFoto = document.querySelector('input[name="foto_logro"]');
        
        if (checkboxEliminar && inputFoto) {
            checkboxEliminar.addEventListener('change', function() {
                if (this.checked) {
                    inputFoto.value = ''; // Limpiar archivo seleccionado si lo hubiera
                    inputFoto.disabled = true;
                    inputFoto.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    inputFoto.disabled = false;
                    inputFoto.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            });
        }
    });
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>