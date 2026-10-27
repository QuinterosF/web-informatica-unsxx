<?php
$title = 'Comunicados - Informática UNSXX';
$pageTitle = 'Gestión de Comunicados';
ob_start();
?>

<!-- Controles Superiores -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Lista de Comunicados</h2>
    <a href="/announcements/create"
        class="inline-flex items-center gap-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm ml-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
            </path>
        </svg>
        Nuevo Comunicado
    </a>
</div>

<!-- Contenedor Tarjeta -->
<div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100">
    <div class="overflow-x-auto">
        <table id="announcementsTable" class="w-full text-sm text-left">
            <thead class="text-xs text-white uppercase bg-unsxx-blue-900">
                <tr>
                    <th scope="col" class="px-4 py-3 w-1/4">Título</th>
                    <th scope="col" class="px-4 py-3">Fecha</th>
                    <th scope="col" class="px-4 py-3 text-center">Estado</th>
                    <th scope="col" class="px-4 py-3 text-center">Adjuntos</th>
                    <th scope="col" class="px-6 py-3 text-center">Fijar</th>
                    <th scope="col" class="px-4 py-3">Autor</th>
                    <th data-sortable="false" scope="col" class="px-4 py-3 rounded-tr-lg text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-unsxx-blue-50">
                <?php if (!empty($announcements)): ?>
                    <?php foreach ($announcements as $item): ?>
                        <tr class="hover:bg-unsxx-blue-50/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 mb-1"><?= htmlspecialchars($item['nom_com']) ?></div>
                                <div class="text-xs text-gray-500 max-w-xs line-clamp-1"
                                    title="<?= htmlspecialchars(mb_strimwidth(strip_tags($item['desc_com']), 0, 80, '...')) ?>">
                                    <?= htmlspecialchars(mb_strimwidth(strip_tags($item['desc_com']), 0, 80, '...')) ?>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-unsxx-blue-800 font-medium whitespace-nowrap">
                                <?= date('d/m/Y', strtotime($item['fech_com'])) ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($item['estado'] == 1): ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                        Publicado
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                                        Borrador
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- COLUMNA DE ADJUNTOS -->
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Lógica de Imagen (Usa default.png si está vacío) -->
                                    <?php
                                    $fotoCom = !empty($item['foto_com']) ? $item['foto_com'] : 'default.png';
                                    $isDefault = empty($item['foto_com']);
                                    ?>
                                    <a href="/uploads/img/comunicados/<?= htmlspecialchars($fotoCom) ?>" target="_blank"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full <?= $isDefault ? 'bg-gray-100 text-gray-400 hover:bg-gray-200' : 'bg-blue-50 text-blue-600 hover:bg-blue-100' ?> transition-colors"
                                        title="<?= $isDefault ? 'Ver Imagen (Por Defecto)' : 'Ver Imagen' ?>">
                                        <i class="fas fa-image"></i>
                                    </a>

                                    <!-- Lógica de PDF -->
                                    <?php if (!empty($item['doc_com'])): ?>
                                        <a href="/uploads/pdf/comunicados/<?= htmlspecialchars($item['doc_com']) ?>" target="_blank"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 transition-colors"
                                            title="Ver Documento PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    <?php else: ?>
                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-50 text-gray-300"
                                            title="Sin Documento">
                                            <i class="fas fa-file-pdf"></i>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <form action="/announcements/<?= (int) $item['id_com'] ?>/pin" method="POST"
                                    class="inline-block m-0 p-0">
                                    <button type="submit"
                                        title="<?= !empty($item['fijado']) ? 'Quitar fijado' : 'Fijar comunicado' ?>"
                                        class="p-2 rounded-xl transition-all border cursor-pointer <?= !empty($item['fijado']) ? 'bg-unsxx-yellow-400 border-unsxx-yellow-500 shadow-sm' : 'bg-slate-50 border-slate-200 hover:bg-white hover:border-slate-300' ?>">

                                        <!-- Ícono de Chincheta (Pin) -->
                                        <span
                                            class="text-base leading-none block transition-all <?= !empty($item['fijado']) ? '' : 'opacity-40 grayscale hover:opacity-100 hover:grayscale-0' ?>">
                                            📌
                                        </span>

                                    </button>
                                </form>
                            </td>

                            <td class="px-4 py-3 text-gray-500 text-xs">
                                <span
                                    class="bg-gray-100 text-gray-700 px-2 py-1 rounded-md border border-gray-200 whitespace-nowrap">
                                    <svg class="w-3 h-3 inline-block mr-1 mb-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <?= htmlspecialchars($item['autor_nombre'] ?? 'Administrador') ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="/announcements/<?= $item['id_com'] ?>/edit"
                                        class="text-unsxx-blue-600 hover:text-unsxx-blue-800 transition-colors" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form action="/announcements/<?= $item['id_com'] ?>/delete" method="POST"
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        const table = document.getElementById("announcementsTable");
        if (table) {
            new simpleDatatables.DataTable(table, {
                searchable: true,
                fixedHeight: false,
                perPage: 10,
                labels: {
                    placeholder: "Buscar comunicado...",
                    noRows: "No hay comunicados registrados",
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
                title: '¿Eliminar comunicado?',
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