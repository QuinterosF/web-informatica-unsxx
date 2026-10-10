<?php
$title = 'Laboratorios - Ingeniería Informática UNSXX';
$pageTitle = 'Laboratorios';
ob_start();
?>

<!-- Controles Superiores -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-unsxx-blue-900">Laboratorios</h2>
    <a href="/labs/create"
        class="inline-flex items-center gap-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm ml-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Nuevo Laboratorio
    </a>
</div>

<!-- Flash Messages are handled in layout -->

<!-- Responsive Table Card -->
<div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100">
    <div class="overflow-x-auto">
        <table id="labsTable" class="w-full text-sm text-left" role="grid">
            <thead class="text-xs text-white uppercase bg-unsxx-blue-900">
                <tr>
                    <th data-sortable="false" scope="col" class="px-4 py-3 rounded-tl-lg">Nombre</th>
                    <th scope="col" class="px-4 py-3">Responsable</th>
                    <th scope="col" class="px-4 py-3">Horario (PDF)</th>
                    <th scope="col" class="px-4 py-3">Estado</th>
                    <th data-sortable="false" scope="col" class="px-4 py-3 rounded-tr-lg">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-unsxx-blue-50">
                <?php if (empty($labs)): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-unsxx-blue-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-unsxx-blue-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM1.104 18.471A13.932 13.932 0 0112 16c2.5 0 4.847.655 6.896 1.746" />
                            </svg>
                            <p class="text-lg font-medium">No hay laboratorios registrados</p>
                            <p class="text-sm text-unsxx-blue-400 mt-1">Comienza agregando el primer laboratorio</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($labs as $lab): ?>
                        <tr class="hover:bg-unsxx-blue-50/50 transition-colors">
                            <td class="px-4 py-3 font-medium text-unsxx-blue-900"><?= htmlspecialchars($lab['nombre_lab']) ?>
                            </td>
                            <td class="px-4 py-3 text-unsxx-blue-700">
                                <?= $lab['responsable_nombre'] ? htmlspecialchars($lab['responsable_nombre']) : '<span class="text-unsxx-blue-400">Sin asignar</span>' ?>
                            </td>
                            <td class="px-4 py-3">
                                <?php if (!empty($lab['horario_lab'])): ?>
                                    <a href="/storage/labs/<?= htmlspecialchars($lab['horario_lab']) ?>" target="_blank"
                                        class="inline-flex items-center gap-1 text-unsxx-blue-600 hover:text-unsxx-blue-800 hover:underline transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v6h6">
                                            </path>
                                        </svg>
                                        Ver PDF
                                    </a>
                                <?php else: ?>
                                    <span class="text-unsxx-blue-400">Sin PDF</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <?php if ($lab['activo']): ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">Activo</span>
                                <?php else: ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="/labs/<?= $lab['id_lab'] ?>/edit"
                                        class="p-2 text-unsxx-blue-600 hover:text-unsxx-blue-800 hover:bg-unsxx-blue-50 rounded-lg transition"
                                        title="Editar" aria-label="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form action="/labs/<?= $lab['id_lab'] ?>/delete" method="POST"
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
        const table = document.getElementById("labsTable");
        if (table) {
            new simpleDatatables.DataTable(table, {
                searchable: true,
                fixedHeight: false,
                perPage: 5,
                perPageSelect: [5, 10, 25, 50],
                labels: {
                    placeholder: "Buscar laboratorio...",
                    noRows: "No se encontraron laboratorios",
                    info: "Mostrando {start} a {end} de {rows} laboratorios",
                    perPage: "registros por página",
                    searchLabel: "Buscar: "
                }
            });
        }
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Escuchamos los 'submit' en todo el documento (Delegación de eventos)
    document.body.addEventListener('submit', function(e) {
        
        // Si el formulario que disparó el evento tiene la clase 'form-eliminar'
        if (e.target && e.target.matches('.form-eliminar')) {
            e.preventDefault(); // Detenemos el envío nativo
            
            const form = e.target; // Capturamos el formulario exacto
            
            Swal.fire({
                title: '¿Eliminar laboratorio?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', // Rojo Tailwind
                cancelButtonColor: '#3b82f6', // Azul Tailwind
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Forzamos el envío si el usuario confirma
                }
            });
        }
    });
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>