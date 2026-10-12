<?php
$title = 'Galería del laboratorio de ' . ($lab['nombre_lab'] ?? 'Laboratorio') . ' - Informática UNSXX';
$pageTitle = 'Galería de fotos de laboratorio';
ob_start();
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-unsxx-blue-900">Galería del laboratorio de <?= htmlspecialchars($lab['nombre_lab'] ?? 'Laboratorio') ?></h1>
        <p class="text-xs text-gray-500 mt-1 hidden sm:block">Gestiona las fotos del laboratorio. La primera foto marcada como portada se usará como imagen principal.</p>
    </div>
    <a href="/labs" class="inline-flex items-center gap-2 text-unsxx-blue-600 hover:text-unsxx-blue-800 font-medium ml-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Volver al listado
    </a>
</div>

<!-- Zona de Subida -->
<div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100 mb-6">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-unsxx-blue-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-unsxx-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Subir nuevas fotos
        </h2>
        <form action="/labs/<?= $lab['id_lab'] ?>/gallery" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="fotos" class="block text-sm font-medium text-gray-700 mb-1">Seleccionar fotos (múltiples)</label>
                <input type="file" id="fotos" name="fotos[]" multiple accept="image/*" required
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-unsxx-blue-50 file:text-unsxx-blue-700 hover:file:bg-unsxx-blue-100 transition-all border border-gray-300 rounded-lg cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">Puede seleccionar múltiples imágenes (JPG, PNG, WebP).</p>
            </div>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Subir Fotos
            </button>
        </form>
    </div>
</div>

<!-- Cuadrícula de Fotos -->
<?php if (!empty($photos)): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="galleryGrid">
        <?php foreach ($photos as $photo): ?>
            <?php
            $isPortada = !empty($photo['es_portada']);
            $borderClass = $isPortada ? 'border-2 border-yellow-400 shadow-lg shadow-yellow-200' : 'border border-gray-200';
            $imageUrl = '/uploads/img/laboratorios/' . htmlspecialchars($photo['archivo_url']);
            ?>
            <div class="bg-white rounded-xl shadow-sm <?= $borderClass ?> overflow-hidden transition-all duration-300 hover:shadow-md group">
                <!-- Imagen -->
                <a href="<?= '/uploads/img/laboratorios/' . htmlspecialchars($photo['archivo_url']) ?>" target="_blank" class="block relative overflow-hidden">
                    <img src="/uploads/img/laboratorios/<?= htmlspecialchars($photo['archivo_url']) ?>" 
                        alt="Foto de laboratorio" 
                        class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                    
                    <!-- Badge PORTADA -->
                    <?php if (!empty($photo['es_portada'])): ?>
                        <span class="absolute top-2 left-2 inline-flex items-center gap-1 px-2 py-1 text-xs font-bold text-yellow-800 bg-yellow-100 rounded-full shadow-sm">
                            PORTADA
                        </span>
                    <?php endif; ?>
                </a>

                <!-- Acciones -->
                <div class="p-4 pt-2 space-y-2">
                    <?php if (!empty($photo['es_portada'])): ?>
                        <span class="inline-flex items-center justify-center w-full px-3 py-2 text-xs font-semibold text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-lg cursor-default">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292l-2.8-2.034a1 1 0 00-1.176 0z"/>
                            </svg>
                            PORTADA ACTUAL
                        </span>
                    <?php else: ?>
                        <form action="/labs/<?= $lab['id_lab'] ?>/gallery/<?= $photo['id_foto'] ?>/cover" method="POST" class="w-full">
                            <button type="submit" title="Establecer como portada" class="inline-flex items-center justify-center w-full px-3 py-2 text-xs font-semibold text-yellow-600 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 hover:border-yellow-300 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292l-2.8-2.034a1 1 0 00-1.176 0z"/>
                                </svg>
                                Establecer como portada
                            </button>
                        </form>
                    <?php endif; ?>

                    <!-- Eliminar -->
                    <form action="/labs/<?= $lab['id_lab'] ?>/gallery/<?= $photo['id_foto'] ?>/delete" method="POST" class="w-full form-delete-photo" onsubmit="return confirmDeletePhoto(event, this)">
                        <button type="submit" title="Eliminar" class="inline-flex items-center justify-center w-full px-3 py-2 text-xs font-semibold text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:border-red-300 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <!-- Estado vacío -->
    <div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100 p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay fotos en la galería</h3>
        <p class="text-gray-500 mb-6">Sube las primeras fotos usando el formulario de arriba.</p>
    </div>
<?php endif; ?>

<script>
    function confirmDeletePhoto(event, form) {
        event.preventDefault();
        Swal.fire({
            title: '¿Eliminar foto?',
            text: "Esta acción eliminará la foto permanentemente del servidor y de la base de datos.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#3b82f6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>