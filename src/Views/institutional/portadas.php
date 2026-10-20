<?php
$title = 'Portadas de Inicio - Informática UNSXX';
$pageTitle = 'Portadas del Portal Web';
ob_start();
?>

<!-- Cabecera -->
<div class="mb-6">
    <p class="text-sm text-gray-500 mt-1">Gestiona las imágenes principales que aparecerán rotando en el carrusel de inicio al público.</p>
</div>

<!-- Zona de Subida -->
<div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100 mb-8 p-6">
    <form action="/portadas" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-end gap-4">
        <div class="flex-1 w-full">
            <label for="fotos" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                <i class="fas fa-images text-unsxx-blue-600"></i> Subir nuevas portadas
            </label>
            <input type="file" id="fotos" name="fotos[]" multiple accept="image/jpeg, image/png, image/webp" required
                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-unsxx-blue-50 file:text-unsxx-blue-700 hover:file:bg-unsxx-blue-100 transition-all border border-gray-300 rounded-lg cursor-pointer">
            <p class="text-xs text-gray-400 mt-1">Se recomiendan imágenes horizontales (ej. 1920x1080px). Formatos: JPG, PNG, WebP.</p>
        </div>
        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm transition-colors whitespace-nowrap flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            Subir Archivos
        </button>
    </form>
</div>

<!-- Cuadrícula de Portadas -->
<?php if (!empty($portadas)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php foreach ($portadas as $portada): ?>
            <?php $esActivo = $portada['activo'] == 1; ?>
            <div class="bg-white rounded-xl shadow-sm border <?= $esActivo ? 'border-green-200' : 'border-gray-200 opacity-80 grayscale-[20%]' ?> overflow-hidden transition-all duration-300 group hover:shadow-md flex flex-col">
                
                <!-- Imagen -->
                <div class="relative h-56 overflow-hidden bg-gray-100 shrink-0">
                    <img src="/uploads/img/portadas/<?= htmlspecialchars($portada['archivo_url']) ?>" alt="Portada" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    
                    <!-- Badge de Estado -->
                    <?php if ($esActivo): ?>
                        <span class="absolute top-3 right-3 bg-green-500/90 backdrop-blur text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase shadow-sm tracking-wider">
                            Público
                        </span>
                    <?php else: ?>
                        <span class="absolute top-3 right-3 bg-gray-600/90 backdrop-blur text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase shadow-sm tracking-wider">
                            Oculto
                        </span>
                    <?php endif; ?>
                </div>
                
                <!-- Formulario de Datos (Título y Orden) -->
                <div class="p-4 bg-white flex-1 border-t border-gray-100">
                    <form action="/portadas/<?= $portada['id_portada'] ?>/update" method="POST" class="m-0 space-y-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Título Opcional</label>
                            <input type="text" name="titulo" value="<?= htmlspecialchars($portada['titulo'] ?? '') ?>" placeholder="Ej: Bienvenidos a Informática" 
                                   class="w-full text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 px-3 py-1.5 outline-none transition-all">
                        </div>
                        <div class="flex gap-3">
                            <div class="w-1/3">
                                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Orden</label>
                                <input type="number" name="orden" value="<?= htmlspecialchars($portada['orden'] ?? '0') ?>" min="0" 
                                       class="w-full text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-unsxx-blue-500 focus:border-unsxx-blue-500 px-3 py-1.5 outline-none transition-all text-center">
                            </div>
                            <div class="w-2/3 flex items-end">
                                <button type="submit" class="w-full bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 font-semibold text-xs px-3 py-1.5 rounded-md transition-colors h-[34px]">
                                    Guardar datos
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Controles (Footer) -->
                <div class="px-4 py-3 flex items-center justify-between bg-gray-50 border-t border-gray-100 shrink-0">
                    
                    <!-- Toggle Activar/Desactivar -->
                    <form action="/portadas/<?= $portada['id_portada'] ?>/toggle" method="POST" class="m-0">
                        <label class="inline-flex items-center cursor-pointer" title="<?= $esActivo ? 'Ocultar del portal' : 'Mostrar en el portal' ?>">
                            <input type="checkbox" onChange="this.form.submit()" class="sr-only peer" <?= $esActivo ? 'checked' : '' ?>>
                            <div class="relative w-10 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                            <span class="ms-3 text-xs font-bold <?= $esActivo ? 'text-green-700' : 'text-gray-500' ?>">
                                <?= $esActivo ? 'Activo' : 'Pausado' ?>
                            </span>
                        </label>
                    </form>

                    <!-- Botón Eliminar -->
                    <form action="/portadas/<?= $portada['id_portada'] ?>/delete" method="POST" class="m-0 form-delete-portada">
                        <button type="submit" title="Eliminar portada permanentemente" class="p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 rounded-md transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                    
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <!-- Estado Vacío -->
    <div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100 p-16 text-center">
        <svg class="w-20 h-20 mx-auto text-gray-300 mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Aún no hay portadas configuradas</h3>
        <p class="text-gray-500 mb-6">Sube imágenes institucionales de alta calidad para dar la bienvenida a los visitantes del portal web.</p>
    </div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.form-delete-portada');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar Portada?',
                    text: "Esta acción borrará la imagen físicamente del servidor y no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#3b82f6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    });
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>