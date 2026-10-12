<?php
$title = 'Editar Mención / Instituto - Informática UNSXX';
$pageTitle = 'Editar Mención / Instituto';
ob_start();

$listCoordinators = $coordinators ?? $academics ?? [];
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Editar Registro</h2>
    <a href="/mentions"
        class="inline-flex items-center gap-2 text-unsxx-blue-600 hover:text-unsxx-blue-800 font-medium ml-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Volver al listado
    </a>
</div>

<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
    <div class="p-6 sm:p-8">
        <form action="/mentions/<?= $mention['id_men'] ?>/edit" method="POST" enctype="multipart/form-data">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-2">Nombre de la Mención /
                        Instituto <span class="text-red-500">*</span></label>
                    <input type="text" id="nombre" name="nombre" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-white"
                        value="<?= htmlspecialchars($old['nombre'] ?? $mention['nombre'] ?? '') ?>">
                </div>

                <!-- Coordinador (Tom Select) -->
                <div>
                    <label for="coordinador" class="block text-sm font-semibold text-gray-700 mb-2">Coordinador Asignado
                        <span class="text-red-500">*</span></label>
                    <select id="coordinador" name="coordinador" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-white">
                        <option value="">Buscar coordinador...</option>
                        <?php foreach ($listCoordinators as $coord): ?>
                            <?php $selected = ($old['coordinador'] ?? $mention['coordinador'] ?? '') == $coord['id_acad'] ? 'selected' : ''; ?>
                            <option value="<?= $coord['id_acad'] ?>" <?= $selected ?>>
                                <?= htmlspecialchars($coord['nom_acad']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Perfil Profesional -->
            <div class="mb-6">
                <label for="perfil_profesional" class="block text-sm font-semibold text-gray-700 mb-2">Perfil
                    Profesional / Descripción <span class="text-red-500">*</span></label>
                <textarea id="perfil_profesional" name="perfil_profesional" required rows="4"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-white"><?= htmlspecialchars($old['perfil_profesional'] ?? $mention['perfil_profesional'] ?? '') ?></textarea>
            </div>

            <!-- Escudo y Estado -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Columna 1: Imagen / Escudo -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-3">
                            <i class="fas fa-shield-alt text-blue-500 mr-2"></i> Escudo de la Mención / IIDAI <span class="text-red-500">*</span>
                        </label>

                        <?php if (!empty($mention['escudo_url'])): ?>
                            <div class="mb-4 bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">ESCUDO ACTUAL:</p>
                                <div class="flex items-start gap-4">
                                    <img src="/uploads/img/menciones/<?= htmlspecialchars($mention['escudo_url']) ?>"
                                        alt="Escudo"
                                        class="w-24 h-24 object-contain rounded shadow-sm border border-gray-200 bg-gray-50 p-1">
                                    <div class="flex flex-col justify-center py-1">
                                        <a href="/uploads/img/menciones/<?= htmlspecialchars($mention['escudo_url']) ?>"
                                            target="_blank"
                                            class="text-sm text-blue-600 hover:underline font-medium break-all line-clamp-2">Ver escudo original</a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <label for="escudo_url" class="block text-xs font-medium text-gray-600 mb-1">Subir nuevo escudo para reemplazar (JPG, PNG):</label>
                        <input type="file" id="escudo_url" name="escudo_url" accept="image/*"
                            <?= empty($mention['escudo_url']) ? 'required' : '' ?>
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg bg-white transition-all cursor-pointer">
                    </div>

                    <!-- Columna 2: Visibilidad (Estado) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Visibilidad</label>
                        <label class="inline-flex items-center cursor-pointer mt-2">
                            <?php 
                            // Verifica si está activo en $old (tras un error de validación) o directamente en la BD ($mention)
                            $isChecked = (isset($old['estado']) && $old['estado'] == 1) || (!isset($old) && isset($mention['estado']) && $mention['estado'] == 1); 
                            ?>
                            <input type="checkbox" name="estado" value="1" class="sr-only peer" <?= $isChecked ? 'checked' : '' ?>>
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500">
                            </div>
                            <span class="ms-3 text-sm font-medium text-gray-700 peer-checked:text-green-600">Activo / Visible</span>
                        </label>
                    </div>

                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                <a href="/mentions"
                    class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">Cancelar</a>
                <button type="submit"
                    class="px-6 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm transition-colors">
                    Actualizar Registro
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tom Select Scripts -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<style>
    .ts-control {
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        border-color: #d1d5db;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        font-size: 0.875rem;
        background-color: #ffffff;
    }

    .ts-control.focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    .ts-dropdown {
        border-radius: 0.375rem;
        border-color: #d1d5db;
        font-size: 0.875rem;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (document.getElementById("coordinador")) {
            new TomSelect("#coordinador", { create: false, placeholder: "Buscar coordinador..." });
        }
    });
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>