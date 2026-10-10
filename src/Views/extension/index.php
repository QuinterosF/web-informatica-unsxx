<?php
$title = 'Extensión Universitaria - Informática UNSXX';
$pageTitle = 'Gestión de Extensión';
ob_start();
?>

<!-- Controles Superiores -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Actividades de Extensión</h2>
    <a href="/extensions/create"
        class="inline-flex items-center gap-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm ml-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Nueva Actividad
    </a>
</div>

<!-- Contenedor Tarjeta -->
<div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100">
    <div class="overflow-x-auto">
        <table id="extensionsTable" class="w-full text-sm text-left">
            <thead class="text-xs text-white uppercase bg-unsxx-blue-900">
                <tr>
                    <th scope="col" class="px-4 py-3 rounded-tl-lg">ID</th>
                    <th scope="col" class="px-4 py-3">Actividad</th>
                    <th scope="col" class="px-4 py-3">Fecha</th>
                    <th scope="col" class="px-4 py-3">Descripción</th>
                    <th scope="col" class="px-4 py-3">Registrado por</th>
                    <th data-sortable="false" scope="col" class="px-4 py-3 rounded-tr-lg">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-unsxx-blue-50">
                <?php if (!empty($extensions)): ?>
                    <?php foreach ($extensions as $ext): ?>
                        <tr class="hover:bg-unsxx-blue-50/50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-500">
                                #<?= htmlspecialchars($ext['id_ext']) ?>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900">
                                <?= htmlspecialchars($ext['nom_ext']) ?>
                            </td>
                            <td class="px-4 py-3 text-unsxx-blue-800 font-medium">
                                <?= date('d/m/Y', strtotime($ext['fech_ext'])) ?>
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                <div class="max-w-xs line-clamp-2" title="<?= htmlspecialchars($ext['desc_ext']) ?>">
                                    <?= htmlspecialchars($ext['desc_ext']) ?>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-md border border-gray-200">
                                    <svg class="w-3 h-3 inline-block mr-1 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <?= htmlspecialchars($ext['creador_nombre'] ?? 'Administrador') ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <a href="/extensions/<?= $ext['id_ext'] ?>/edit" class="text-unsxx-blue-600 hover:text-unsxx-blue-800 transition-colors" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="/extensions/<?= $ext['id_ext'] ?>/delete" method="POST" class="inline-block form-eliminar">
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Si no hay datos, DataTables mostrará su propio mensaje, pero dejamos esto por si acaso -->
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Simple-DataTables CDN y Estilos base -->
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<style>
    .dataTable-wrapper .dataTable-container { overflow-x: auto; border-bottom: 1px solid #e5e7eb; }
    .dataTable-top { display: flex; justify-content: space-between; align-items: center; padding-bottom: 1.5rem; }
    .dataTable-dropdown label, .dataTable-search label { font-size: 0; }
    .dataTable-dropdown label:after { content: " registros"; font-size: 0.875rem; color: #4b5563; margin-left: 0.5rem; }
    .dataTable-input, .dataTable-selector { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 1rem; outline: none; font-size: 0.875rem; color: #374151; background-color: #f9fafb; transition: all 0.2s; }
    .dataTable-input:focus, .dataTable-selector:focus { border-color: #1e3a8a; box-shadow: 0 0 0 2px rgba(30, 58, 138, 0.2); background-color: #ffffff; }
    .dataTable-pagination a { border-radius: 0.375rem; color: #1e3a8a; padding: 0.5rem 0.75rem; margin: 0 0.125rem; font-size: 0.875rem; }
    .dataTable-pagination a:hover { background-color: #eff6ff; }
    .dataTable-pagination .active a { background-color: #facc15; color: #1e3a8a; font-weight: bold; }
    .dataTable-info { color: #6b7280; font-size: 0.875rem; }
    .dataTable-table>thead>tr>th { background-color: #1e3a8a !important; color: #ffffff !important; border-bottom: none; }
    .dataTable-table th a { color: #ffffff !important; text-decoration: none; }
    
    @media (max-width: 768px) {
        .dataTable-top, .dataTable-bottom { display: flex !important; flex-direction: column !important; align-items: flex-start !important; gap: 1rem !important; }
        .dataTable-dropdown, .dataTable-search, .dataTable-info, .dataTable-pagination { float: none !important; width: 100% !important; text-align: left !important; }
        .dataTable-input { width: 100% !important; margin-top: 0.5rem !important; box-sizing: border-box !important; }
        .dataTable-pagination ul { justify-content: center !important; flex-wrap: wrap !important; }
    }
</style>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Inicializar DataTables
        const table = document.getElementById("extensionsTable");
        if (table) {
            new simpleDatatables.DataTable(table, {
                searchable: true,
                fixedHeight: false,
                perPage: 10,
                labels: {
                    placeholder: "Buscar actividad...",
                    noRows: "No hay actividades registradas",
                    info: "Mostrando {start} a {end} de {rows}",
                    perPage: ""
                }
            });
        }
    });

    // Delegación de eventos para SweetAlert2 al eliminar
    document.body.addEventListener('submit', function(e) {
        if (e.target && e.target.matches('.form-eliminar')) {
            e.preventDefault();
            const form = e.target;
            
            Swal.fire({
                title: '¿Eliminar actividad?',
                text: "Esta acción borrará la actividad de forma permanente.",
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
    });
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>