<?php
$title = 'Identidad y Textos Institucionales - Informática UNSXX';
$pageTitle = 'Identidad y Textos Institucionales';
ob_start();

// Aseguramos que $info exista y no de errores de "undefined offset"
$info = $info ?? [];
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <p class="text-xs text-gray-500 mt-1 hidden sm:block">Gestione la información de la Carrera, misión, visión, objetivo general y el mensaje de dirección.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
    
    <!-- Pestañas de Navegación -->
    <div class="border-b border-gray-200">
        <nav class="flex overflow-x-auto" aria-label="Tabs">
            <button onclick="changeTab(event, 'tab-general')" class="tab-btn active border-unsxx-blue-600 text-unsxx-blue-600 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                Información General
            </button>
            <button onclick="changeTab(event, 'tab-filosofia')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                Misión, Visión y Objetivos
            </button>
            <button onclick="changeTab(event, 'tab-historia')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                Reseña Histórica
            </button>
            <button onclick="changeTab(event, 'tab-director')" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                Mensaje del Director/a
            </button>
        </nav>
    </div>

    <!-- Formulario Principal -->
    <form action="/institutional-info" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8">
        
        <!-- CONTENIDO: Pestaña 1 (Información General) -->
        <div id="tab-general" class="tab-content block">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Información General de la Carrera</h3>
            
            <div class="mb-6">
                <label for="presentacion" class="block text-sm font-semibold text-gray-700 mb-2">Descripción General (Presentación) <span class="text-gray-400 font-normal ml-1">(Aparecerá en la portada)</span></label>
                <textarea id="presentacion" name="presentacion" rows="6"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-white"><?= htmlspecialchars($info['presentacion'] ?? '') ?></textarea>
            </div>

            <div class="mb-6">
                <label for="perfil_general" class="block text-sm font-semibold text-gray-700 mb-2">Perfil Profesional</label>
                <textarea id="perfil_general" name="perfil_general" rows="6"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-white"><?= htmlspecialchars($info['perfil_general'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- CONTENIDO: Pestaña 2 (Filosofía Institucional) -->
        <div id="tab-filosofia" class="tab-content hidden">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Misión, Visión y Objetivos</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="mision" class="block text-sm font-semibold text-gray-700 mb-2">Misión</label>
                    <textarea id="mision" name="mision" rows="5"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-white"><?= htmlspecialchars($info['mision'] ?? '') ?></textarea>
                </div>
                <div>
                    <label for="vision" class="block text-sm font-semibold text-gray-700 mb-2">Visión</label>
                    <textarea id="vision" name="vision" rows="5"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-white"><?= htmlspecialchars($info['vision'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="mb-6">
                <label for="objetivos" class="block text-sm font-semibold text-gray-700 mb-2">Objetivo General</label>
                <textarea id="objetivos" name="objetivos" rows="4"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-white"><?= htmlspecialchars($info['objetivos'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- CONTENIDO: Pestaña 3 (Historia) -->
        <div id="tab-historia" class="tab-content hidden">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Reseña Histórica de Informática UNSXX</h3>
            
            <div class="mb-6">
                <label for="resena_historica" class="block text-sm font-semibold text-gray-700 mb-2">Texto de la Reseña Histórica</label>
                <textarea id="resena_historica" name="resena_historica" rows="12"
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-white"><?= htmlspecialchars($info['resena_historica'] ?? '') ?></textarea>
                <p class="text-xs text-gray-500 mt-2">Puede separar los párrafos usando saltos de línea normales (Enter).</p>
            </div>
        </div>

        <!-- CONTENIDO: Pestaña 4 (Mensaje del Director) -->
        <div id="tab-director" class="tab-content hidden">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Mensaje de Dirección de Carrera</h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2">
                    <label for="mensaje_director" class="block text-sm font-semibold text-gray-700 mb-2">Mensaje de Bienvenida del Director/a</label>
                    <textarea id="mensaje_director" name="mensaje_director" rows="10"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-white"><?= htmlspecialchars($info['mensaje_director'] ?? '') ?></textarea>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 h-fit">
                    <label class="block text-sm font-semibold text-gray-700 mb-4">Foto del Director/a</label>
                    
                    <?php if (!empty($info['foto_director'])): ?>
                        <div class="mb-4 text-center">
                            <img src="/uploads/img/institucion/<?= htmlspecialchars($info['foto_director']) ?>" alt="Foto Director" class="w-32 h-32 object-cover rounded-full mx-auto border-4 border-white shadow-sm mb-3">
                            <div class="flex items-center justify-center p-2 rounded bg-red-50 border border-red-100 text-left">
                                <input type="checkbox" id="remove_foto_director" name="remove_foto_director" value="1" 
                                       class="w-4 h-4 text-red-600 bg-white border-gray-300 rounded focus:ring-red-500 focus:ring-2 cursor-pointer">
                                <label for="remove_foto_director" class="ml-2 text-xs font-medium text-red-700 cursor-pointer hover:text-red-900 leading-tight">
                                    Eliminar foto actual
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div>
                        <label for="foto_director" class="block text-xs font-medium text-gray-600 mb-1">Subir o reemplazar foto (Opcional)</label>
                        <input type="file" id="foto_director" name="foto_director" accept="image/jpeg, image/png, image/webp" 
                               class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-unsxx-blue-50 file:text-unsxx-blue-700 hover:file:bg-unsxx-blue-100 transition-all border border-gray-300 rounded-md bg-white">
                        <p class="text-[10px] text-gray-500 mt-1">Recomendado: Formato cuadrado (1:1). JPG o PNG.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón Guardar Flotante / Inferior -->
        <div class="flex justify-end pt-6 border-t border-gray-100 mt-4">
            <button type="submit" class="px-8 py-3 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm transition-all text-base flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

<!-- Script para el funcionamiento de las Pestañas -->
<script>
    function changeTab(event, tabId) {
        event.preventDefault(); // Evita que se envíe el form si se pulsa por error
        
        // 1. Ocultar todos los contenidos de las pestañas
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });
        
        // 2. Quitar estilos activos de todos los botones
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('border-unsxx-blue-600', 'text-unsxx-blue-600', 'active');
            el.classList.add('border-transparent', 'text-gray-500');
        });
        
        // 3. Mostrar el contenido seleccionado
        document.getElementById(tabId).classList.remove('hidden');
        document.getElementById(tabId).classList.add('block');
        
        // 4. Activar el botón seleccionado
        event.currentTarget.classList.remove('border-transparent', 'text-gray-500');
        event.currentTarget.classList.add('border-unsxx-blue-600', 'text-unsxx-blue-600', 'active');
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>