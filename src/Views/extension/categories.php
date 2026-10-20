<?php
$title = 'Categorías de Eventos - Informática UNSXX';
$pageTitle = 'Categorías de Extensión y Eventos';

function getCategoryPreviewBadge(string $color): string {
    $colors = [
        'blue'   => 'bg-blue-100 text-blue-800 border-blue-200',
        'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
        'red'    => 'bg-red-100 text-red-800 border-red-200',
        'green'  => 'bg-green-100 text-green-800 border-green-200',
        'purple' => 'bg-purple-100 text-purple-800 border-purple-200',
        'teal'   => 'bg-teal-100 text-teal-800 border-teal-200',
        'orange' => 'bg-orange-100 text-orange-800 border-orange-200',
    ];
    return $colors[$color] ?? 'bg-gray-100 text-gray-800 border-gray-200';
}

ob_start();
?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-xl font-bold text-unsxx-blue-900">Taxonomía de Eventos Recurrentes</h2>
        <p class="text-xs text-gray-500 mt-0.5">Crea y edita etiquetas dinámicas (ICPC, FLISoL, CTF, Congresos) para agrupar las actividades en el portal público.</p>
    </div>
    <a href="/extensions" class="inline-flex items-center gap-2 text-unsxx-blue-600 hover:text-unsxx-blue-800 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Volver a Eventos
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Formulario Nueva Categoría -->
    <div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100 p-6 h-max">
        <h3 class="text-base font-bold text-unsxx-blue-900 mb-4">Nueva Categoría</h3>
        <form action="/extensions/categories" method="POST" class="space-y-4">
            <div>
                <label for="nom_cat" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Categoría <span class="text-red-500">*</span></label>
                <input type="text" id="nom_cat" name="nom_cat" required placeholder="Ej: Hackathon Interna"
                    class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none text-sm">
            </div>
            <div>
                <label for="color_badge" class="block text-sm font-medium text-gray-700 mb-1">Color de Etiqueta</label>
                <select id="color_badge" name="color_badge"
                    class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none text-sm bg-white">
                    <option value="blue">Azul Institucional</option>
                    <option value="yellow">Amarillo / Dorado</option>
                    <option value="red">Rojo (Seguridad / Urgente)</option>
                    <option value="green">Verde (Software Libre / Ambiental)</option>
                    <option value="purple">Morado (Investigación / Ciencia)</option>
                    <option value="teal">Verde Azulado (Extensión)</option>
                    <option value="orange">Naranja (Competencias / Cultura)</option>
                </select>
            </div>
            <button type="submit" class="w-full py-2.5 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm transition-colors text-sm">
                + Guardar Categoría
            </button>
        </form>
    </div>

    <!-- Tabla de Categorías Existentes -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-white uppercase bg-unsxx-blue-900">
                <tr>
                    <th class="px-4 py-3">Vista Previa</th>
                    <th class="px-4 py-3">Slug SEO</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border <?= getCategoryPreviewBadge($cat['color_badge'] ?? 'blue') ?>">
                                    <?= htmlspecialchars($cat['nom_cat']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-500">
                                /<?= htmlspecialchars($cat['slug_cat']) ?>
                            </td>
                            <td class="px-4 py-3">
                                <form action="/extensions/categories/<?= $cat['id_cat_ext'] ?>/toggle" method="POST" class="m-0">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" onChange="this.form.submit()" class="sr-only peer" <?= !empty($cat['activo']) ? 'checked' : '' ?>>
                                        <div class="relative w-9 h-5 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                                        <span class="ms-2 text-xs font-medium <?= !empty($cat['activo']) ? 'text-green-700' : 'text-gray-400' ?>">
                                            <?= !empty($cat['activo']) ? 'Activa' : 'Oculta' ?>
                                        </span>
                                    </label>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Botón Editar Categoría (Abre Modal) -->
                                    <button type="button"
                                        onclick="openEditCategoryModal(<?= (int)$cat['id_cat_ext'] ?>, '<?= htmlspecialchars(addslashes($cat['nom_cat'])) ?>', '<?= htmlspecialchars($cat['color_badge'] ?? 'blue') ?>')"
                                        class="p-1.5 text-unsxx-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                        title="Editar categoría">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>

                                    <!-- Botón Eliminar Categoría -->
                                    <form action="/extensions/categories/<?= $cat['id_cat_ext'] ?>/delete" method="POST" class="inline form-delete-cat">
                                        <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar categoría">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de Edición de Categoría -->
<div id="editCategoryModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-xs p-4">
    <div class="bg-white rounded-xl shadow-xl border border-unsxx-blue-100 w-full max-w-md overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 bg-unsxx-blue-900 text-white">
            <h3 class="font-bold text-base">Editar Categoría</h3>
            <button type="button" onclick="closeEditCategoryModal()" class="text-gray-300 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="editCategoryForm" method="POST" class="p-6 space-y-4">
            <div>
                <label for="edit_nom_cat" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Categoría <span class="text-red-500">*</span></label>
                <input type="text" id="edit_nom_cat" name="nom_cat" required
                    class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none text-sm">
            </div>
            <div>
                <label for="edit_color_badge" class="block text-sm font-medium text-gray-700 mb-1">Color de Etiqueta</label>
                <select id="edit_color_badge" name="color_badge"
                    class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none text-sm bg-white">
                    <option value="blue">Azul Institucional</option>
                    <option value="yellow">Amarillo / Dorado</option>
                    <option value="red">Rojo (Seguridad / Urgente)</option>
                    <option value="green">Verde (Software Libre / Ambiental)</option>
                    <option value="purple">Morado (Investigación / Ciencia)</option>
                    <option value="teal">Verde Azulado (Extensión)</option>
                    <option value="orange">Naranja (Competencias / Cultura)</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                <button type="button" onclick="closeEditCategoryModal()"
                    class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 text-sm">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm text-sm">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditCategoryModal(id, name, color) {
        const modal = document.getElementById('editCategoryModal');
        const form = document.getElementById('editCategoryForm');
        document.getElementById('edit_nom_cat').value = name;
        document.getElementById('edit_color_badge').value = color;
        form.action = '/extensions/categories/' + id + '/update';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditCategoryModal() {
        const modal = document.getElementById('editCategoryModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.form-delete-cat').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar categoría?',
                    text: "Los eventos que tengan esta categoría no se borrarán, solo pasarán a ser 'General'.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#3b82f6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) this.submit();
                });
            });
        });
    });
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>