<?php
/** @var array $admission */
$title = 'Editar Admisión - Informática UNSXX';
$pageTitle = 'Editar Proceso de Admisión';
ob_start();
?>

<!-- Controles Superiores -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Modificar Proceso</h2>
    <a href="/admissions" class="inline-flex items-center gap-2 text-unsxx-blue-600 hover:text-unsxx-blue-800 font-medium ml-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver al listado
    </a>
</div>

<!-- Contenedor del Formulario -->
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
    <div class="p-6 sm:p-8">
        
        <form action="/admissions/<?= $admission['id_adm'] ?>/edit" method="POST" enctype="multipart/form-data" class="space-y-6">
            
            <div class="grid grid-cols-1 gap-6">
                
                <!-- Modalidad (Tipo) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Modalidad de Admisión <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php $tipoActual = $old['tipo'] ?? $admission['tipo'] ?? ''; ?>
                        
                        <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-purple-50 hover:border-purple-200 transition-all">
                            <input type="radio" name="tipo" value="prueba" required <?= $tipoActual === 'prueba' ? 'checked' : '' ?> 
                                class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500">
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-gray-900">Prueba de Admisión</span>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-indigo-50 hover:border-indigo-200 transition-all">
                            <input type="radio" name="tipo" value="preuni" required <?= $tipoActual === 'preuni' ? 'checked' : '' ?> 
                                   class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-gray-900">Curso Preuniversitario</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Detalles del Proceso -->
                <div>
                    <label for="proc_adm" class="block text-sm font-medium text-gray-700 mb-1">Descripción del Proceso <span class="text-red-500">*</span></label>
                    <textarea id="proc_adm" name="proc_adm" required rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all resize-y"><?= htmlspecialchars($old['proc_adm'] ?? $admission['proc_adm'] ?? '') ?></textarea>
                </div>

                <!-- Requisitos -->
                <div>
                    <label for="req_adm" class="block text-sm font-medium text-gray-700 mb-1">Requisitos de Inscripción <span class="text-red-500">*</span></label>
                    <textarea id="req_adm" name="req_adm" required rows="4" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all resize-y"><?= htmlspecialchars($old['req_adm'] ?? $admission['req_adm'] ?? '') ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Gestión Académica -->
                <div>
                    <label for="gestion" class="block text-sm font-semibold text-gray-700 mb-2">Gestión Académica <span class="text-red-500">*</span></label>
                    <input type="number" id="gestion" name="gestion" required min="2020" max="2100"
                        value="<?= htmlspecialchars($old['gestion'] ?? $admission['gestion'] ?? date('Y')) ?>"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-white">
                </div>

                <!-- Estado (Activo/Oculto) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Visibilidad en el Portal</label>
                    <label class="inline-flex items-center cursor-pointer mt-1">
                        <?php $isChecked = isset($old['estado']) ? $old['estado'] : ($admission['estado'] ?? 1); ?>
                        <input type="checkbox" name="estado" value="1" class="sr-only peer" <?= $isChecked ? 'checked' : '' ?>>
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        <span class="ms-3 text-sm font-medium text-gray-700 peer-checked:text-green-600">Activo / Visible</span>
                    </label>
                </div>
            </div>

                <!-- Imagen / Afiche Oficial -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                    <label for="img_adm" class="block text-sm font-semibold text-gray-700 mb-2">Imagen / Afiche Oficial</label>
                    
                    <!-- Mostrar archivo actual y checkbox de borrado si no es default -->
                    <?php if (!empty($admission['img_adm']) && $admission['img_adm'] !== 'default.png'): ?>
                        <div class="mb-4 flex items-center justify-between bg-white p-3 rounded border border-gray-200">
                            <div class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-sm text-gray-600 truncate"><?= htmlspecialchars($admission['img_adm']) ?></span>
                            </div>
                            <a href="/uploads/img/admission/<?= htmlspecialchars($admission['img_adm']) ?>" target="_blank" class="text-sm text-blue-600 hover:underline">Ver actual</a>
                        </div>
                        
                        <div class="mb-4 flex items-center p-2 rounded bg-red-50 border border-red-100">
                            <input type="checkbox" id="remove_image" name="remove_image" value="1" 
                                class="w-4 h-4 text-red-600 bg-white border-gray-300 rounded focus:ring-red-500 focus:ring-2 cursor-pointer">
                            <label for="remove_image" class="ml-2 text-sm font-medium text-red-700 cursor-pointer hover:text-red-900">
                                Eliminar afiche actual (volver a imagen por defecto)
                            </label>
                        </div>
                    <?php endif; ?>

                    <div class="flex items-center gap-4">
                        <input type="file" id="img_adm" name="img_adm" accept="image/jpeg, image/png, image/webp" 
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-unsxx-blue-50 file:text-unsxx-blue-700 hover:file:bg-unsxx-blue-100 transition-all border border-gray-300 rounded-lg bg-white">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Sube un archivo solo si deseas agregar o reemplazar el afiche (Opcional).</p>
                </div>

            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100 mt-6">
                <a href="/admissions" class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm transition-colors">
                    Actualizar Proceso
                </button>
            </div>
            
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>