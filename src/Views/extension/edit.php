<?php
$title = 'Editar Evento - Informática UNSXX';
$pageTitle = 'Editar Evento o Actividad';
ob_start();
?>

<!-- Controles Superiores -->
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-unsxx-blue-900">Editar: <?= htmlspecialchars($extension['nom_ext'] ?? 'Evento') ?></h2>
        <?php if (!empty($extension['slug_ext'])): ?>
            <p class="text-xs text-gray-400 font-mono mt-0.5">Slug SEO: /eventos/<?= htmlspecialchars($extension['slug_ext']) ?></p>
        <?php endif; ?>
    </div>
    <div class="flex items-center gap-4 ml-auto">
        <a href="/extensions/<?= $extension['id_ext'] ?>/gallery"
            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-bold text-teal-700 bg-teal-50 border border-teal-200 rounded-lg hover:bg-teal-100 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Ir a la Galería del Evento
        </a>
        <a href="/extensions"
            class="inline-flex items-center gap-2 text-unsxx-blue-600 hover:text-unsxx-blue-800 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver al listado
        </a>
    </div>
</div>

<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
    <div class="p-6 sm:p-8">

        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="/extensions/<?= $extension['id_ext'] ?>/edit" method="POST" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Título -->
                <div class="md:col-span-2">
                    <label for="nom_ext" class="block text-sm font-medium text-gray-700 mb-1">
                        Título del Evento o Actividad <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nom_ext" name="nom_ext" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all"
                        value="<?= htmlspecialchars($old['nom_ext'] ?? $extension['nom_ext'] ?? '') ?>">
                </div>

                <!-- Categoría -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="id_cat_ext" class="block text-sm font-medium text-gray-700">Categoría del Evento</label>
                        <a href="/extensions/categories" class="text-xs text-unsxx-blue-600 hover:underline font-medium">Gestionar categorías</a>
                    </div>
                    <select id="id_cat_ext" name="id_cat_ext"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all bg-white">
                        <option value="">-- Evento General (Sin categoría) --</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <?php $selected = ($old['id_cat_ext'] ?? $extension['id_cat_ext'] ?? '') == $cat['id_cat_ext'] ? 'selected' : ''; ?>
                                <option value="<?= $cat['id_cat_ext'] ?>" <?= $selected ?>>
                                    <?= htmlspecialchars($cat['nom_cat']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Fecha -->
                <div>
                    <label for="fech_ext" class="block text-sm font-medium text-gray-700 mb-1">
                        Fecha de Realización <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="fech_ext" name="fech_ext" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all"
                        value="<?= htmlspecialchars($old['fech_ext'] ?? $extension['fech_ext'] ?? '') ?>">
                </div>

                <!-- Lugar -->
                <div>
                    <label for="lugar_ext" class="block text-sm font-medium text-gray-700 mb-1">
                        Lugar / Ubicación <span class="text-gray-400 font-normal">(Opcional)</span>
                    </label>
                    <input type="text" id="lugar_ext" name="lugar_ext"
                        placeholder="Ej: Paraninfo Universitario / Modalidad Virtual"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all"
                        value="<?= htmlspecialchars($old['lugar_ext'] ?? $extension['lugar_ext'] ?? '') ?>">
                </div>

                <!-- Enlace Externo -->
                <div>
                    <label for="enlace_externo" class="block text-sm font-medium text-gray-700 mb-1">
                        Enlace Externo <span class="text-gray-400 font-normal">(Opcional)</span>
                    </label>
                    <input type="url" id="enlace_externo" name="enlace_externo"
                        placeholder="https://..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all"
                        value="<?= htmlspecialchars($old['enlace_externo'] ?? $extension['enlace_externo'] ?? '') ?>">
                </div>
            </div>

            <!-- Descripción -->
            <div>
                <label for="desc_ext" class="block text-sm font-medium text-gray-700 mb-1">
                    Descripción del Evento <span class="text-red-500">*</span>
                </label>
                <textarea id="desc_ext" name="desc_ext" required rows="6"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 outline-none transition-all resize-y"><?= htmlspecialchars($old['desc_ext'] ?? $extension['desc_ext'] ?? '') ?></textarea>
            </div>

            <!-- Opciones de Visibilidad -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <!-- Toggle Publicado -->
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="activo" value="1" class="sr-only peer"
                        <?= (isset($old['activo']) ? $old['activo'] : ($extension['activo'] ?? 1)) ? 'checked' : '' ?>>
                    <div class="relative w-10 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600"></div>
                    <div class="ms-3">
                        <span class="block text-sm font-semibold text-gray-800">Publicado en el portal</span>
                        <span class="block text-xs text-gray-500">Si se desmarca, se ocultará como borrador.</span>
                    </div>
                </label>

                <!-- Toggle Destacado -->
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="destacado" value="1" class="sr-only peer"
                        <?= (isset($old['destacado']) ? $old['destacado'] : ($extension['destacado'] ?? 0)) ? 'checked' : '' ?>>
                    <div class="relative w-10 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-amber-500"></div>
                    <div class="ms-3">
                        <span class="block text-sm font-semibold text-gray-800">📌 Fijar como Evento Destacado</span>
                        <span class="block text-xs text-gray-500">Aparecerá primero en la sección de noticias.</span>
                    </div>
                </label>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                <a href="/extensions"
                    class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm transition-colors">
                    Actualizar Evento
                </button>
            </div>

        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>