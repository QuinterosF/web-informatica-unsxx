<?php
$title = 'Plantel Docente - Informática UNSXX';
$pageTitle = 'Plantel Docente';
ob_start();
?>

<!-- Controles Superiores -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Gestión de Docentes</h2>
    <a href="/academics/create"
        class="inline-flex items-center gap-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm ml-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Nuevo Docente
    </a>
</div>

<!-- Contenedor Tarjeta -->
<div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100">
    <div class="overflow-x-auto">
        <table id="docentesTable" class="w-full text-sm text-left">
            <thead class="text-xs text-white uppercase bg-unsxx-blue-900">
                <tr>
                    <th data-sortable="false" scope="col" class="px-4 py-3 rounded-tl-lg">Foto</th>
                    <th scope="col" class="px-4 py-3">Nombre</th>
                    <th scope="col" class="px-4 py-3">Email</th>
                    <th scope="col" class="px-4 py-3">Categoría</th>
                    <th scope="col" class="px-4 py-3">Celular</th>
                    <th data-sortable="false" scope="col" class="px-4 py-3 rounded-tr-lg">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-unsxx-blue-50">
                <?php if (!empty($academics)): ?>
                    <?php foreach ($academics as $academic): ?>
                        <tr class="hover:bg-unsxx-blue-50/50 transition-colors">
                            <td class="px-4 py-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-unsxx-blue-100 flex items-center justify-center text-unsxx-blue-700 font-bold overflow-hidden border border-unsxx-blue-200">
                                    <?php if (!empty($academic['foto_acad']) && $academic['foto_acad'] !== 'default.png'): ?>
                                        <img src="/uploads/img/academic/<?= htmlspecialchars($academic['foto_acad']) ?>" alt="Foto"
                                            class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <?= substr(htmlspecialchars($academic['nom_acad']), 0, 1) ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900">
                                <?= htmlspecialchars($academic['nom_acad']) ?>
                            </td>
                            <td class="px-4 py-3 text-unsxx-blue-600">
                                <a href="mailto:<?= htmlspecialchars($academic['email_acad']) ?>" class="hover:underline">
                                    <?= htmlspecialchars($academic['email_acad']) ?>
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2.5 py-1 text-xs font-medium rounded-full bg-unsxx-blue-100 text-unsxx-blue-800 border border-unsxx-blue-200">
                                    <?= htmlspecialchars($academic['cat_nombre']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                <?= htmlspecialchars($academic['cel_acad']) ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <a href="/academics/<?= $academic['id_acad'] ?>/edit"
                                        class="text-unsxx-blue-600 hover:text-unsxx-blue-800 transition-colors" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form action="/academics/<?= $academic['id_acad'] ?>/delete" method="POST"
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

<!-- Simple-DataTables CDN y Configuración -->
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<style>
    /* Adaptación de DataTables a Tailwind y colores UNSXX */
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

    /* Limpieza de textos por defecto y traducción forzada */
    .dataTable-dropdown label,
    .dataTable-search label {
        font-size: 0;
        /* Oculta el "{select}" y el "Search" defectuosos */
    }

    .dataTable-dropdown label:after {
        content: " registros por página";
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
        transition: all 0.2s;
        font-size: 0.875rem;
    }

    .dataTable-pagination a:hover {
        background-color: #eff6ff;
    }

    .dataTable-pagination .active a,
    .dataTable-pagination .active a:hover {
        background-color: #facc15;
        color: #1e3a8a;
        font-weight: bold;
    }

    .dataTable-info {
        color: #6b7280;
        font-size: 0.875rem;
    }

    /* Forzar cabecera azul UNSXX */
    .dataTable-table>thead>tr>th {
        background-color: #1e3a8a !important;
        color: #ffffff !important;
        border-bottom: none;
    }

    .dataTable-table th a {
        color: #ffffff !important;
        text-decoration: none;
    }

    /* --- Ajustes Responsive Extremos para Móviles --- */
    @media (max-width: 768px) {

        .dataTable-top,
        .dataTable-bottom {
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 1rem !important;
        }

        .dataTable-dropdown,
        .dataTable-search,
        .dataTable-info,
        .dataTable-pagination {
            float: none !important;
            width: 100% !important;
            text-align: left !important;
        }

        .custom-dt-search {
            width: 100% !important;
            display: block !important;
        }

        .dataTable-input {
            width: 100% !important;
            margin-top: 0.5rem !important;
            box-sizing: border-box !important;
        }

        .dataTable-pagination ul {
            justify-content: center !important;
            flex-wrap: wrap !important;
        }
    }

    /* Forzar Scroll Horizontal de la Tabla */
    .dataTable-wrapper {
        width: 100% !important;
        max-width: 100vw !important;
    }

    .dataTable-container {
        width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
    }

    .dataTable-table {
        min-width: 800px !important;
        /* Fuerza el scroll */
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.getElementById("docentesTable");
        if (table) {
            new simpleDatatables.DataTable(table, {
                searchable: true,
                fixedHeight: false,
                perPage: 5,
                perPageSelect: [5, 10, 25, 50],
                labels: {
                    placeholder: "Buscar docente...",
                    noRows: "No se encontraron docentes",
                    info: "Mostrando {start} a {end} de {rows} docentes",
                    perPage: "registros por página",
                    searchLabel: "Buscar: "
                }
            });
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Seleccionar todos los formularios de eliminación
        const formEliminar = document.querySelectorAll('.form-eliminar');

        formEliminar.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Detener el envío nativo del formulario

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción eliminará al docente permanentemente del sistema.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444', // Rojo Tailwind
                    cancelButtonColor: '#3b82f6', // Azul Tailwind
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true // Pone el botón de cancelar a la izquierda
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, enviamos el formulario programáticamente
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