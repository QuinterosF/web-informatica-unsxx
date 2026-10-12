<?php
$title = 'Admisión - Informática UNSXX';
$pageTitle = 'Procesos de Admisión';
ob_start();
?>

<!-- Controles Superiores -->
<div class="flex justify-between items-center mb-6">
    <a href="/admissions/create"
        class="inline-flex items-center gap-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm ml-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Nuevo Proceso
    </a>
</div>

<!-- Contenedor Tarjeta -->
<div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100">
    <div class="overflow-x-auto">
        <table id="admissionsTable" class="w-full text-sm text-left">
            <thead class="text-xs text-white uppercase bg-unsxx-blue-900">
                <tr>
                    <th scope="col" class="px-4 py-3">Modalidad</th>
                    <th scope="col" class="px-4 py-3 text-center">Gestión</th>
                    <th scope="col" class="px-4 py-3 text-center">Estado</th>
                    <th scope="col" class="px-4 py-3">Requisitos</th>
                    <th scope="col" class="px-4 py-3">Proceso</th>
                    <th scope="col" class="px-4 py-3">Última Modificación</th>
                    <th data-sortable="false" scope="col" class="px-4 py-3 rounded-tr-lg text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-unsxx-blue-50">
                <?php if (!empty($admissions)): ?>
                    <?php foreach ($admissions as $item): ?>
                        <tr class="hover:bg-unsxx-blue-50/50 transition-colors">
                            <td class="px-4 py-3 text-center sm:text-left whitespace-nowrap">
                                <?php if ($item['tipo'] === 'prueba'): ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Prueba de Admisión
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                            </path>
                                        </svg>
                                        Preuniversitario
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center font-bold text-gray-700">
                                <?= htmlspecialchars($item['gestion']) ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($item['estado']): ?>
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Activo
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Oculto
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                <div class="max-w-[200px] line-clamp-2" title="<?= htmlspecialchars($item['req_adm']) ?>">
                                    <?= htmlspecialchars($item['req_adm']) ?>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-900 font-medium">
                                <div class="max-w-[200px] line-clamp-2" title="<?= htmlspecialchars($item['proc_adm']) ?>">
                                    <?= htmlspecialchars($item['proc_adm']) ?>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">
                                <span
                                    class="bg-gray-100 text-gray-700 px-2 py-1 rounded-md border border-gray-200 whitespace-nowrap inline-flex items-center"
                                    title="Editado por última vez por: <?= htmlspecialchars($item['actualizado_por_nombre'] ?? 'Administrador') ?>">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                    <?= htmlspecialchars($item['actualizado_por_nombre'] ?? 'Administrador') ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="/admissions/<?= $item['id_adm'] ?>/edit"
                                        class="text-unsxx-blue-600 hover:text-unsxx-blue-800 transition-colors" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form action="/admissions/<?= $item['id_adm'] ?>/delete" method="POST"
                                        class="inline-block form-eliminar">
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors"
                                            title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
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

<!-- Simple-DataTables y SweetAlert2 Scripts -->
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<style>
    .dataTable-wrapper .dataTable-container {
        overflow-x: auto;
        border-bottom: 1px solid #e5e7eb;
    }

    .dataTable-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 1.5rem;
    }

    .dataTable-dropdown label,
    .dataTable-search label {
        font-size: 0;
    }

    .dataTable-dropdown label:after {
        content: " registros";
        font-size: 0.875rem;
        color: #4b5563;
        margin-left: 0.5rem;
    }

    .dataTable-input,
    .dataTable-selector {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        outline: none;
        font-size: 0.875rem;
        color: #374151;
        background-color: #f9fafb;
        transition: all 0.2s;
    }

    .dataTable-input:focus,
    .dataTable-selector:focus {
        border-color: #1e3a8a;
        box-shadow: 0 0 0 2px rgba(30, 58, 138, 0.2);
        background-color: #ffffff;
    }

    .dataTable-pagination a {
        border-radius: 0.375rem;
        color: #1e3a8a;
        padding: 0.5rem 0.75rem;
        margin: 0 0.125rem;
        font-size: 0.875rem;
    }

    .dataTable-pagination a:hover {
        background-color: #eff6ff;
    }

    .dataTable-pagination .active a {
        background-color: #facc15;
        color: #1e3a8a;
        font-weight: bold;
    }

    .dataTable-info {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .dataTable-table>thead>tr>th {
        background-color: #1e3a8a !important;
        color: #ffffff !important;
        border-bottom: none;
    }

    .dataTable-table th a {
        color: #ffffff !important;
        text-decoration: none;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.getElementById("admissionsTable");
        if (table) {
            new simpleDatatables.DataTable(table, {
                searchable: true,
                fixedHeight: false,
                perPage: 5,
                labels: {
                    placeholder: "Buscar proceso...",
                    noRows: "No hay procesos de admisión registrados",
                    info: "Mostrando {start} a {end} de {rows}",
                    perPage: "registros por página",
                    searchLabel: "Buscar: "
                }
            });
        }
    });

    document.body.addEventListener('submit', function (e) {
        if (e.target && e.target.matches('.form-eliminar')) {
            e.preventDefault();
            const form = e.target;

            Swal.fire({
                title: '¿Eliminar proceso?',
                text: "Esta acción no se puede deshacer.",
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