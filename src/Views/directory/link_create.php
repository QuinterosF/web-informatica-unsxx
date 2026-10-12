<?php
$title = 'Nuevo Enlace - Informática UNSXX';
$pageTitle = 'Nuevo Enlace / Red Social';
ob_start();

$linkIcons = [
    'fab fa-facebook', 'fab fa-whatsapp', 'fab fa-telegram', 'fab fa-tiktok',
    'fab fa-youtube', 'fab fa-instagram',
    'fas fa-link', 'fas fa-globe', 'fas fa-graduation-cap', 'fas fa-book-reader',
    'fas fa-laptop-code', 'fas fa-gamepad', 'fas fa-flag', 'fas fa-chalkboard-teacher',
    'fas fa-envelope', 'fas fa-server', 'fas fa-user-secret'
];
?>
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Crear Enlace</h2>
    <a href="/directory" class="text-unsxx-blue-600 hover:underline text-sm ml-auto font-medium">Volver al Directorio</a>
</div>

<div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-unsxx-blue-100 p-6">
    <form action="/directory/links" method="POST">
        
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre *</label>
            <input type="text" name="nombre" required placeholder="Ej: Sistema SIAA o IIDAI" class="w-full rounded-md border-gray-300 border py-2 px-3 focus:border-blue-500">
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">URL del Enlace *</label>
            <input type="url" name="url" required placeholder="https://..." class="w-full rounded-md border-gray-300 border py-2 px-3 focus:border-blue-500">
        </div>

        <!-- Grupo de Checkboxes para Ubicaciones -->
        <div class="mb-6 p-4 rounded-lg border border-blue-100 bg-blue-50/30">
            <label class="block text-sm font-semibold text-gray-700 mb-3">Ubicaciones (¿Dónde aparecerá este enlace?) *</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="ubicacion[]" value="footer_redes" class="w-4 h-4 rounded border-gray-300 text-unsxx-blue-600 focus:ring-unsxx-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Redes Sociales (Pie de Página)</span>
                </label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="ubicacion[]" value="footer_enlaces" class="w-4 h-4 rounded border-gray-300 text-unsxx-blue-600 focus:ring-unsxx-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Enlaces Institucionales (Pie de Página)</span>
                </label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="ubicacion[]" value="menu_servicios" class="w-4 h-4 rounded border-gray-300 text-unsxx-blue-600 focus:ring-unsxx-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Menú de Servicios Principales</span>
                </label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="ubicacion[]" value="menu_investigacion" class="w-4 h-4 rounded border-gray-300 text-unsxx-blue-600 focus:ring-unsxx-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Menú de Investigación (IIDAI, Revistas)</span>
                </label>
            </div>
        </div>

        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <label class="block text-sm font-semibold text-gray-700 mb-3">Seleccionar Ícono *</label>
            <div class="flex flex-wrap gap-3">
                <?php foreach($linkIcons as $icon): 
                    $safeId = str_replace(' ', '-', $icon);
                    $isSelected = ($icon === 'fas fa-link');
                ?>
                    <button type="button" onclick="selectIcon('<?= $icon ?>')" id="btn-<?= $safeId ?>"
                            class="icon-btn w-12 h-12 rounded-lg border-2 flex items-center justify-center text-2xl transition-all <?= $isSelected ? 'border-unsxx-blue-600 bg-blue-50 shadow-sm' : 'border-transparent bg-white hover:bg-gray-100 shadow-sm' ?>"
                            title="<?= htmlspecialchars($icon) ?>">
                        <i class="<?= $icon ?> brand-icon"></i>
                    </button>
                <?php endforeach; ?>
            </div>
            <input type="hidden" name="icono" id="icono_input" value="fas fa-link">
        </div>

        <div class="mb-6">
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" name="estado" value="1" class="sr-only peer" checked>
                <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                <span class="ms-3 text-sm font-medium text-gray-700 peer-checked:text-green-600">Enlace Activo (Visible)</span>
            </label>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="/directory" class="px-6 py-2 border rounded-lg hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="px-6 py-2 bg-unsxx-yellow-400 text-unsxx-blue-900 font-bold rounded-lg hover:bg-unsxx-yellow-500">Guardar</button>
        </div>
    </form>
</div>

<!-- Estilos de Color Brand (Igual al index) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .brand-icon.fa-whatsapp { color: #25D366; }
    .brand-icon.fa-facebook { color: #1877F2; }
    .brand-icon.fa-telegram { color: #0088cc; }
    .brand-icon.fa-tiktok { color: #000000; }
    .brand-icon.fa-youtube { color: #FF0000; }
    .brand-icon.fa-instagram { color: #E4405F; }
    .brand-icon:not(.fa-whatsapp):not(.fa-facebook):not(.fa-telegram):not(.fa-tiktok):not(.fa-youtube):not(.fa-instagram) {
        color: #1e3a8a;
    }
</style>
<script>
    function selectIcon(iconClass) {
        document.getElementById('icono_input').value = iconClass;
        document.querySelectorAll('.icon-btn').forEach(btn => {
            btn.classList.remove('border-unsxx-blue-600', 'bg-blue-50');
            btn.classList.add('border-transparent', 'bg-white');
        });
        const safeId = iconClass.replace(/ /g, '-');
        document.getElementById('btn-' + safeId).classList.remove('border-transparent', 'bg-white');
        document.getElementById('btn-' + safeId).classList.add('border-unsxx-blue-600', 'bg-blue-50');
    }
</script>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>