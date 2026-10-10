<?php
$title = 'Nuevo Documento Oficial - Informática UNSXX';
$pageTitle = 'Publicar Documento Oficial';
ob_start();
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Registrar Documento</h2>
    <a href="/documents" class="inline-flex items-center gap-2 text-unsxx-blue-600 hover:text-unsxx-blue-800 font-medium ml-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver al listado
    </a>
</div>

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
    <div class="p-6 sm:p-8">
        
        <form action="/documents" method="POST" enctype="multipart/form-data" class="space-y-6">
            
            <!-- Título -->
            <div>
                <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título del Documento <span class="text-red-500">*</span></label>
                <input type="text" id="titulo" name="titulo" required placeholder="Ej: Horario de Clases Semestre I/2027"
                       value="<?= htmlspecialchars($old['titulo'] ?? '') ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Categoría -->
                <div>
                    <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">Categoría <span class="text-red-500">*</span></label>
                    <select id="categoria" name="categoria" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all bg-white">
                        <option value="">Seleccione una categoría</option>
                        <option value="plan_estudios">Plan de Estudios / Malla Curricular</option>
                        <option value="horario_clases">Horario de Clases</option>
                        <option value="horario_labs">Horario de Laboratorios</option>
                        <option value="reglamento_titulacion">Reglamento de Titulación</option>
                        <option value="convocatoria">Convocatoria Oficial</option>
                        <option value="otro">Otro Documento</option>
                    </select>
                </div>

                <!-- Gestión (Año/Periodo) -->
                <div>
                    <label for="gestion" class="block text-sm font-medium text-gray-700 mb-1">Gestión / Año <span class="text-red-500">*</span></label>
                    <input type="text" id="gestion" name="gestion" required placeholder="Ej: I/2027 o 2027"
                           value="<?= htmlspecialchars($old['gestion'] ?? date('Y')) ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all">
                </div>
            </div>

            <!-- Selector de Archivo PDF -->
            <div class="bg-red-50/50 p-4 rounded-lg border border-red-100">
                <label for="archivo_pdf" class="block text-sm font-medium text-red-900 mb-2">
                    Archivo PDF <span class="text-red-500">*</span>
                </label>
                <input type="file" id="archivo_pdf" name="archivo_pdf" accept="application/pdf" required 
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-100 file:text-red-700 hover:file:bg-red-200 transition-all border border-gray-300 rounded-lg bg-white">
                <p class="text-xs text-gray-500 mt-1">Solo se permiten archivos en formato PDF (Max 10MB).</p>
            </div>

            <!-- Estado -->
            <div class="flex items-center gap-2">
                <input type="checkbox" id="estado" name="estado" value="1" checked class="w-4 h-4 text-unsxx-blue-600 rounded border-gray-300 focus:ring-unsxx-blue-500">
                <label for="estado" class="text-sm font-medium text-gray-700">Publicar inmediatamente en el portal público</label>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100 mt-6">
                <a href="/documents" class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm transition-colors">
                    Guardar Documento
                </button>
            </div>
            
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>