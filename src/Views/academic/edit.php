<?php
$title = 'Editar Docente y Administrativo - Informática UNSXX';
$pageTitle = 'Editar Docente y Administrativo';
ob_start();
?>

<!-- Controles Superiores -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Editar Registro Docente y Administrativo</h2>
    <a href="/academics"
        class="inline-flex items-center gap-2 text-unsxx-blue-600 hover:text-unsxx-blue-800 font-medium ml-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Volver al listado
    </a>
</div>

<!-- Contenedor del Formulario -->
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
    <div class="p-6 sm:p-8">

        <form action="/academics/<?= $academic['id_acad'] ?>/edit" method="POST" enctype="multipart/form-data"
            class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div>
                    <label for="nom_acad" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="nom_acad" name="nom_acad" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all"
                        value="<?= htmlspecialchars($old['nom_acad'] ?? $academic['nom_acad'] ?? '') ?>">
                </div>

                <!-- Email -->
                <div>
                    <label for="email_acad" class="block text-sm font-medium text-gray-700 mb-1">Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" id="email_acad" name="email_acad" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all"
                        value="<?= htmlspecialchars($old['email_acad'] ?? $academic['email_acad'] ?? '') ?>">

                    <!-- Toggle Privacidad Email -->
                    <label class="inline-flex items-center cursor-pointer mt-2">
                        <input type="checkbox" name="mostrar_email" value="1" class="sr-only peer"
                            <?= (isset($old['mostrar_email']) ? $old['mostrar_email'] : ($academic['mostrar_email'] ?? 1)) ? 'checked' : '' ?>>
                        <div
                            class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-unsxx-blue-600">
                        </div>
                        <span class="ms-2 text-xs font-medium text-gray-600">Mostrar públicamente en el portal</span>
                    </label>
                </div>

                <!-- Celular -->
                <div>
                    <label for="cel_acad" class="block text-sm font-medium text-gray-700 mb-1">Celular <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="cel_acad" name="cel_acad"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all"
                        value="<?= htmlspecialchars($old['cel_acad'] ?? $academic['cel_acad'] ?? '') ?>">

                    <!-- Toggle Privacidad Celular -->
                    <label class="inline-flex items-center cursor-pointer mt-2">
                        <input type="checkbox" name="mostrar_cel" value="1" class="sr-only peer"
                            <?= (isset($old['mostrar_cel']) ? $old['mostrar_cel'] : ($academic['mostrar_cel'] ?? 1)) ? 'checked' : '' ?>>
                        <div
                            class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-unsxx-blue-600">
                        </div>
                        <span class="ms-2 text-xs font-medium text-gray-600">Mostrar públicamente en el portal</span>
                    </label>
                </div>

                <!-- Categoría -->
                <div>
                    <label for="cat_acad" class="block text-sm font-medium text-gray-700 mb-1">Categoría <span
                            class="text-red-500">*</span></label>
                    <select id="cat_acad" name="cat_acad" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all bg-white">
                        <option value="">Seleccione una categoría</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
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
                <label class="block text-sm font-bold text-gray-800 mb-3">
                    <i class="fas fa-image text-blue-500 mr-2"></i> Foto de Perfil <span class="text-red-500">*</span>
                </label>

                <?php
                // Asegúrate de usar la variable correcta de tu vista (ej. $academic, $docente o $item)
                $fotoAcad = !empty($academic['foto_acad']) ? $academic['foto_acad'] : 'default.png';
                ?>

                <div class="mb-4 bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">FOTO ACTUAL:</p>
                    <div class="flex items-start gap-4">
                        <img src="/uploads/img/academic/<?= htmlspecialchars($fotoAcad) ?>" alt="Foto"
                            class="w-24 h-24 object-cover rounded shadow-sm border border-gray-200 bg-gray-100">
                        <div class="flex flex-col justify-center py-1">
                            <a href="/uploads/img/academic/<?= htmlspecialchars($fotoAcad) ?>" target="_blank"
                                class="text-sm text-blue-600 hover:underline font-medium break-all line-clamp-2">
                                Ver foto original
                            </a>
                        </div>
                    </div>
                </div>

                <label for="foto_acad" class="block text-xs font-medium text-gray-600 mb-1">Subir nueva foto para
                    reemplazar (JPG, PNG):</label>
                <input type="file" id="foto_acad" name="foto_acad" accept="image/*" <?= empty($academic['foto_acad']) ? 'required' : '' ?>
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg bg-white transition-all cursor-pointer">
            </div>

            <!-- Descripción -->
            <div>
                <label for="desc_acad" class="block text-sm font-medium text-gray-700 mb-1">Descripción /
                    Currículum <span class="text-red-500">*</span></label>
                <textarea id="desc_acad" name="desc_acad" required rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all resize-y"><?= htmlspecialchars($old['desc_acad'] ?? $academic['desc_acad'] ?? '') ?></textarea>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                <a href="/academics"
                    class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm transition-colors">
                    Actualizar
                </button>
            </div>

        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>