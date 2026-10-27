<?php
$title = 'Documentos Oficiales - Informática UNSXX';
$pageTitle = 'Gestión de Documentos Oficiales';
ob_start();

$categorias = [
    'plan_estudios'         => ['label' => 'Plan de Estudios', 'class' => 'bg-blue-100 text-blue-800'],
    'horario_clases'        => ['label' => 'Horario de Clases', 'class' => 'bg-green-100 text-green-800'],
    'reglamento_titulacion' => ['label' => 'Reglamento Titulación', 'class' => 'bg-amber-100 text-amber-800'],
    'reglamento_admision'   => ['label' => 'Reglamento de Admisión', 'class' => 'bg-orange-100 text-orange-800'],
    'reglamento_interno'    => ['label' => 'Reglamentos Internos', 'class' => 'bg-indigo-100 text-indigo-800'],
    'reglamento_macro'      => ['label' => 'Reglamentos Macro', 'class' => 'bg-purple-100 text-purple-800'],
    'otro'                  => ['label' => 'Otro Documento', 'class' => 'bg-gray-100 text-gray-800'],
];
?>

<!-- Controles Superiores -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Documentos y Reglamentos</h2>
        <p class="text-xs text-gray-500 mt-1 hidden sm:block">Gestione horarios, reglamentos u otros documentos oficiales publicados en formato PDF.</p>
    </div>
    <a href="/documents/create" class="inline-flex items-center gap-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold px-4 py-2.5 rounded-lg shadow-sm transition-all text-sm ml-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Nuevo Documento
    </a>
</div>

<!-- Contenedor Tarjeta -->
<div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100">
    <div class="overflow-x-auto">
        <table id="documentsTable" class="w-full text-sm text-left">
            <thead class="text-xs text-white uppercase bg-unsxx-blue-900">
                <tr>
                    <th scope="col" class="px-6 py-3 rounded-tl-lg">Documento</th>
                    <th scope="col" class="px-6 py-3">Categoría</th>
                    <th scope="col" class="px-6 py-3 text-center">Gestión</th>
                    <th data-sortable="false" scope="col" class="px-6 py-3 text-center">Archivo PDF</th>
                    <th scope="col" class="px-6 py-3 text-center">Estado</th>
                    <th scope="col" class="px-6 py-3">Subido Por</th>
                    <th data-sortable="false" scope="col" class="px-6 py-3 text-right rounded-tr-lg">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-unsxx-blue-50">
                <?php if (!empty($documents)): ?>
                    <?php foreach ($documents as $doc): ?>
                        <tr class="hover:bg-unsxx-blue-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <?= htmlspecialchars($doc['titulo']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php $catInfo = $categorias[$doc['categoria']] ?? $categorias['otro']; ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border border-opacity-20 <?= $catInfo['class'] ?>">
                                    <?= $catInfo['label'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-700">
                                <?= htmlspecialchars($doc['gestion']) ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if (!empty($doc['archivo_pdf'])): ?>
                                    <a href="/uploads/pdf/documents/<?= urlencode($doc['archivo_pdf']) ?>" target="_blank" 
                                       class="inline-flex items-center gap-1.5 text-xs text-red-600 font-semibold hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg border border-red-200 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        Ver PDF
                                    </a>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400">Sin archivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($doc['estado']): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Activo
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Oculto
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                <?= htmlspecialchars($doc['subido_por_nombre'] ?? 'Sistema') ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="/documents/<?= $doc['id_doc'] ?>/edit" title="Editar" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="/documents/<?= $doc['id_doc'] ?>/delete" method="POST" class="inline form-delete">
                                        <button type="submit" title="Eliminar" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
</style>

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.getElementById("documentsTable");
        if (table) {
            new simpleDatatables.DataTable(table, {
                searchable: true,
                fixedHeight: false,
                perPage: 5,
                labels: {
                    placeholder: "Buscar documento...",
                    noRows: "No hay documentos oficiales registrados",
                    info: "Mostrando {start} a {end} de {rows}",
                    perPage: "registros por página",
                    searchLabel: "Buscar: "
                }
            });
        }
    });

    // Delegación de eventos para SweetAlert2 (asegura que funcione en todas las páginas de DataTables)
    document.body.addEventListener('submit', function(e) {
        if (e.target && e.target.matches('.form-delete')) {
            e.preventDefault();
            const form = e.target;
            
            Swal.fire({
                title: '¿Eliminar documento?',
                text: "Se eliminará el registro y el archivo PDF guardado en el servidor.",
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