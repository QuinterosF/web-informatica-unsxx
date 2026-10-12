<?php
$title = 'Nuevo Teléfono - Informática UNSXX';
$pageTitle = 'Nuevo Teléfono de Contacto';
ob_start();

$phoneIcons = [
    'fab fa-whatsapp', 'fas fa-phone-alt', 'fas fa-mobile-alt', 
    'fab fa-telegram', 'fas fa-tty'
];
?>
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Crear Teléfono</h2>
    <a href="/directory" class="text-unsxx-blue-600 hover:underline text-sm ml-auto font-medium">Volver al Directorio</a>
</div>

<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-unsxx-blue-100 p-6">
    <form action="/directory/phones" method="POST">
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Referencia (Ej: Kardex Académico) *</label>
            <input type="text" name="nom_cel" required class="w-full rounded-md border-gray-300 border py-2 px-3 focus:border-blue-500 focus:ring-blue-500">
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Número de Celular / Teléfono *</label>
            <input type="text" name="num_cel" required class="w-full rounded-md border-gray-300 border py-2 px-3 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <label class="block text-sm font-semibold text-gray-700 mb-3">Seleccionar Ícono *</label>
            <div class="flex flex-wrap gap-3">
                <?php foreach($phoneIcons as $icon): 
                    $safeId = str_replace(' ', '-', $icon);
                    $isSelected = ($icon === 'fab fa-whatsapp');
                ?>
                    <button type="button" onclick="selectIcon('<?= $icon ?>')" id="btn-<?= $safeId ?>"
                            class="icon-btn w-12 h-12 rounded-lg border-2 flex items-center justify-center text-2xl transition-all <?= $isSelected ? 'border-unsxx-blue-600 bg-blue-50 shadow-sm' : 'border-transparent bg-white hover:bg-gray-100 shadow-sm' ?>">
                        <i class="<?= $icon ?> brand-icon"></i>
                    </button>
                <?php endforeach; ?>
            </div>
            <input type="hidden" name="icono" id="icono_input" value="fab fa-whatsapp">
        </div>

        <div class="mb-6">
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" name="estado" value="1" class="sr-only peer" checked>
                <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                <span class="ms-3 text-sm font-medium text-gray-700 peer-checked:text-green-600">Teléfono Activo (Visible)</span>
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
    .brand-icon.fa-telegram { color: #0088cc; }
    .brand-icon:not(.fa-whatsapp):not(.fa-telegram) { color: #1e3a8a; }
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