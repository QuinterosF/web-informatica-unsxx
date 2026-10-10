<?php
$title = 'Editar Docente - Informática UNSXX';
$pageTitle = 'Editar Docente';
ob_start();
?>

<!-- Controles Superiores -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Editar Registro Docente</h2>
    <a href="/academics" class="inline-flex items-center gap-2 text-unsxx-blue-600 hover:text-unsxx-blue-800 font-medium ml-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver al listado
    </a>
</div>

<!-- Contenedor del Formulario -->
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
    <div class="p-6 sm:p-8">
        
        <form action="/academics/<?= $academic['id_acad'] ?>/edit" method="POST" enctype="multipart/form-data" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div>
                    <label for="nom_acad" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo <span class="text-red-500">*</span></label>
                    <input type="text" id="nom_acad" name="nom_acad" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all" 
                           value="<?= htmlspecialchars($old['nom_acad'] ?? $academic['nom_acad'] ?? '') ?>">
                </div>

                <!-- Email -->
                <div>
                    <label for="email_acad" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email_acad" name="email_acad" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all" 
                           value="<?= htmlspecialchars($old['email_acad'] ?? $academic['email_acad'] ?? '') ?>">
                </div>

                <!-- Celular -->
                <div>
                    <label for="cel_acad" class="block text-sm font-medium text-gray-700 mb-1">Celular</label>
                    <input type="text" id="cel_acad" name="cel_acad" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all" 
                           value="<?= htmlspecialchars($old['cel_acad'] ?? $academic['cel_acad'] ?? '') ?>">
                </div>

                <!-- Categoría -->
                <div>
                    <label for="cat_acad" class="block text-sm font-medium text-gray-700 mb-1">Categoría <span class="text-red-500">*</span></label>
                    <select id="cat_acad" name="cat_acad" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all bg-white">
                        <option value="">Seleccione una categoría</option>
                        <?php if(!empty($categories)): ?>
                            <?php foreach($categories as $cat): ?>
                                <?php $selected = ($old['cat_acad'] ?? $academic['cat_acad'] ?? '') == $cat['cat_id'] ? 'selected' : ''; ?>
                                <option value="<?= $cat['cat_id'] ?>" <?= $selected ?>>
                                    <?= htmlspecialchars($cat['cat_nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <!-- Foto -->
            <div>
                <label for="foto_acad" class="block text-sm font-medium text-gray-700 mb-1">Foto de perfil (Dejar en blanco para mantener la actual)</label>
                <div class="flex items-center gap-4">
                    <input type="file" id="foto_acad" name="foto_acad" accept="image/jpeg, image/png" 
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-unsxx-blue-50 file:text-unsxx-blue-700 hover:file:bg-unsxx-blue-100 transition-all border border-gray-300 rounded-lg">
                    <span class="text-xs text-gray-500 w-full max-w-[200px]">JPG, PNG máx. 2MB</span>
                </div>
            </div>

            <!-- Descripción -->
            <div>
                <label for="desc_acad" class="block text-sm font-medium text-gray-700 mb-1">Descripción / Currículum</label>
                <textarea id="desc_acad" name="desc_acad" rows="4" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all resize-y"><?= htmlspecialchars($old['desc_acad'] ?? $academic['desc_acad'] ?? '') ?></textarea>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                <a href="/academics" class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm transition-colors">
                    Actualizar Docente
                </button>
            </div>
            
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>