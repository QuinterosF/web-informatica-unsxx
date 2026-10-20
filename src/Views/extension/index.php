<?php
$title = 'Extensión y Eventos - Informática UNSXX';
$pageTitle = 'Extensión y Eventos';

function getEventBadgeClasses(string $color): string {
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

<!-- Controles Superiores -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <p class="text-sm text-gray-500">Publica actividades académicas, culturales o deportivas en las que participó la Carrera y gestiona sus galerías fotográficas como un blog social.</p>
    </div>
    <div class="flex items-center gap-3 ml-auto shrink-0">
        <!-- Botón Gestionar Categorías -->
        <a href="/extensions/categories"
            class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-unsxx-blue-900 border border-unsxx-blue-200 font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm text-sm">
            <svg class="w-4 h-4 text-unsxx-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            Categorías
        </a>

        <!-- Botón Nuevo Evento -->
        <a href="/extensions/create"
            class="inline-flex items-center gap-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold px-4 py-2 rounded-lg transition-colors shadow-sm text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nuevo Evento
        </a>
    </div>
</div>

<!-- Tabla de Eventos -->
<div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100">
    <div class="overflow-x-auto">
        <table id="extensionsTable" class="w-full text-sm text-left">
            <thead class="text-xs text-white uppercase bg-unsxx-blue-900">
                <tr>
                    <th data-sortable="false" scope="col" class="px-4 py-3 rounded-tl-lg">Portada</th>
                    <th scope="col" class="px-4 py-3">Evento / Actividad</th>
                    <th scope="col" class="px-4 py-3">Categoría</th>
                    <th scope="col" class="px-4 py-3">Fecha y Lugar</th>
                    <th scope="col" class="px-4 py-3">Estado</th>
                    <th data-sortable="false" scope="col" class="px-4 py-3 text-right rounded-tr-lg">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-unsxx-blue-50">
                <?php if (!empty($extensions)): ?>
                    <?php foreach ($extensions as $ext): ?>
                        <tr class="hover:bg-unsxx-blue-50/50 transition-colors">
                            <!-- Miniatura Portada -->
                            <td class="px-4 py-3">
                                <?php if (!empty($ext['foto_portada'])): ?>
                                    <a href="/uploads/img/extension/<?= htmlspecialchars($ext['foto_portada']) ?>" target="_blank" class="block w-14 h-10 rounded-lg overflow-hidden border border-gray-200 shadow-sm hover:opacity-85 transition-opacity">
                                        <img src="/uploads/img/extension/<?= htmlspecialchars($ext['foto_portada']) ?>" alt="Portada" class="w-full h-full object-cover">
                                    </a>
                                <?php else: ?>
                                    <div class="w-14 h-10 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400" title="Sin foto de portada">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Título, Slug y Enlace Externo -->
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1.5">
                                    <?php if (!empty($ext['destacado'])): ?>
                                        <span title="Evento fijado / destacado en portada" class="text-amber-500 text-xs">📌</span>
                                    <?php endif; ?>
                                    <span class="font-bold text-gray-900"><?= htmlspecialchars($ext['nom_ext']) ?></span>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    <?php if (!empty($ext['slug_ext'])): ?>
                                        <span class="text-[11px] text-gray-400 font-mono truncate max-w-[220px]" title="/eventos/<?= htmlspecialchars($ext['slug_ext']) ?>">
                                            /<?= htmlspecialchars($ext['slug_ext']) ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($ext['enlace_externo'])): ?>
                                        <a href="<?= htmlspecialchars($ext['enlace_externo']) ?>" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center gap-1 text-[10px] font-semibold text-unsxx-blue-600 bg-blue-50 hover:bg-blue-100 px-2 py-0.5 rounded border border-blue-200 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            Enlace externo
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Categoría -->
                            <td class="px-4 py-3">
                                <?php if (!empty($ext['nom_cat'])): ?>
                                    <?php $badgeClass = getEventBadgeClasses($ext['color_badge'] ?? 'blue'); ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border <?= $badgeClass ?>">
                                        <?= htmlspecialchars($ext['nom_cat']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400 italic">General</span>
                                <?php endif; ?>
                            </td>

                            <!-- Fecha y Lugar -->
                            <td class="px-4 py-3">
                                <div class="text-gray-800 font-medium text-xs">
                                    <?= htmlspecialchars(date('d/m/Y', strtotime($ext['fech_ext']))) ?>
                                </div>
                                <?php if (!empty($ext['lugar_ext'])): ?>
                                    <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5 truncate max-w-[180px]" title="<?= htmlspecialchars($ext['lugar_ext']) ?>">
                                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <?= htmlspecialchars($ext['lugar_ext']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Estado (Publicado / Borrador) -->
                            <td class="px-4 py-3">
                                <?php if (!empty($ext['activo'])): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Publicado
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Borrador
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Acciones (Galería Dinámica + Editar + Eliminar) -->
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <?php
                                    $totalFotos = isset($ext['total_fotos']) ? (int)$ext['total_fotos'] : 0;
                                    $isEmpty = $totalFotos === 0;
                                    $btnClass = $isEmpty
                                        ? 'text-red-700 bg-red-50 border-red-300 hover:bg-red-100'
                                        : 'text-teal-700 bg-teal-50 border-teal-200 hover:bg-teal-100';
                                    ?>
                                    <a href="/extensions/<?= $ext['id_ext'] ?>/gallery"
                                       class="inline-flex flex-col items-center gap-0.5 px-3 py-1 border rounded-lg transition-colors shadow-sm text-center min-w-[72px] <?= $btnClass ?>"
                                       title="<?= $isEmpty ? 'Alerta: Evento sin fotos' : 'Gestionar ' . $totalFotos . ' fotos del evento' ?>">
                                        <div class="flex items-center gap-1.5 text-xs font-bold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Galería
                                        </div>
                                        <?php if ($isEmpty): ?>
                                            <span class="text-[10px] font-bold text-red-600 leading-none tracking-wide mt-0.5">(Vacío)</span>
                                        <?php else: ?>
                                            <span class="text-[10px] font-semibold text-teal-700 opacity-80 leading-none mt-0.5"><?= $totalFotos ?> fotos</span>
                                        <?php endif; ?>
                                    </a>

                                    <!-- Editar -->
                                    <a href="/extensions/<?= $ext['id_ext'] ?>/edit" title="Editar Evento"
                                       class="p-1.5 text-unsxx-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <!-- Eliminar -->
                                    <form action="/extensions/<?= $ext['id_ext'] ?>/delete" method="POST" class="inline-block form-eliminar">
                                        <button type="submit" title="Eliminar Evento"
                                                class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

<!-- Simple-DataTables CDN y Estilos Institucionales -->
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<style>
    .dataTable-wrapper .dataTable-container { overflow-x: auto; border-bottom: 1px solid #e5e7eb; }
    .dataTable-top { display: flex; justify-content: space-between; align-items: center; padding-bottom: 1.5rem; }
    .dataTable-dropdown label, .dataTable-search label { font-size: 0; }
    .dataTable-dropdown label:after { content: " registros por página"; font-size: 0.875rem; color: #4b5563; margin-left: 0.5rem; }
    .dataTable-input, .dataTable-selector { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 1rem; outline: none; font-size: 0.875rem; color: #374151; background-color: #f9fafb; transition: all 0.2s; }
    .dataTable-input:focus, .dataTable-selector:focus { border-color: #1e3a8a; box-shadow: 0 0 0 2px rgba(30, 58, 138, 0.2); background-color: #ffffff; }
    .dataTable-pagination a { border-radius: 0.375rem; color: #1e3a8a; padding: 0.5rem 0.75rem; margin: 0 0.125rem; transition: all 0.2s; font-size: 0.875rem; }
    .dataTable-pagination a:hover { background-color: #eff6ff; }
    .dataTable-pagination .active a, .dataTable-pagination .active a:hover { background-color: #facc15; color: #1e3a8a; font-weight: bold; }
    .dataTable-info { color: #6b7280; font-size: 0.875rem; }
    .dataTable-table>thead>tr>th { background-color: #1e3a8a !important; color: #ffffff !important; border-bottom: none; }
    .dataTable-table th a { color: #ffffff !important; text-decoration: none; }
    @media (max-width: 768px) {
        .dataTable-top, .dataTable-bottom { display: flex !important; flex-direction: column !important; align-items: flex-start !important; gap: 1rem !important; }
        .dataTable-dropdown, .dataTable-search, .dataTable-info, .dataTable-pagination { float: none !important; width: 100% !important; text-align: left !important; }
        .dataTable-input { width: 100% !important; margin-top: 0.5rem !important; box-sizing: border-box !important; }
    }
    .dataTable-table { min-width: 850px !important; }
</style>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.getElementById("extensionsTable");
        if (table) {
            new simpleDatatables.DataTable(table, {
                searchable: true,
                fixedHeight: false,
                perPage: 10,
                perPageSelect: [10, 20, 30, 40, 50],
                labels: {
                    placeholder: "Buscar evento, categoría, lugar...",
                    noRows: "No hay eventos ni actividades registradas",
                    info: "Mostrando {start} a {end} de {rows} eventos",
                    perPage: "registros por página",
                    searchLabel: "Buscar: "
                }
            });
        }

        // Confirmación de eliminación con SweetAlert2
        const forms = document.querySelectorAll('.form-eliminar');
        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar evento?',
                    text: "Se eliminará la publicación y todas las fotos asociadas en su galería físicamente del servidor.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#3b82f6',
                    confirmButtonText: 'Sí, eliminar todo',
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