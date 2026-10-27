<?php
$title = 'Logros Institucionales - Informática UNSXX';
$pageTitle = 'Historial de Logros y Premios';

function getMedalIcon($posicion) {
    switch ($posicion) {
        case '1er Lugar': return '<span class="text-xl" title="1er Lugar">🥇</span>';
        case '2do Lugar': return '<span class="text-xl" title="2do Lugar">🥈</span>';
        case '3er Lugar': return '<span class="text-xl" title="3er Lugar">🥉</span>';
        case 'Mención Honorífica':
        case 'Reconocimiento': return '<span class="text-xl" title="Reconocimiento">🏅</span>';
        default: return '<span class="text-xl" title="Logro Destacado">🏆</span>';
    }
}
ob_start();
?>

<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-gray-500">Administra los logros y la línea de tiempo de victorias de la Carrera.</p>
    <a href="/admin/logros/create" class="inline-flex items-center gap-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold px-4 py-2 rounded-lg transition-colors shadow-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Registrar Logro
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
    <table id="logrosTable" class="w-full text-sm text-left">
        <thead class="text-xs text-white uppercase bg-unsxx-blue-900">
            <tr>
                <th scope="col" class="px-4 py-3">Premio</th>
                <th scope="col" class="px-4 py-3">Evento / Competencia</th>
                <th scope="col" class="px-4 py-3">Lugar y Fecha</th>
                <th scope="col" class="px-4 py-3 text-center">Foto</th>
                <th scope="col" class="px-4 py-3 text-center">Visibilidad</th>
                <th scope="col" class="px-4 py-3 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php if (!empty($logros)): ?>
                <?php foreach ($logros as $logro): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <?= getMedalIcon($logro['posicion']) ?>
                                <span class="font-bold text-gray-900"><?= htmlspecialchars($logro['posicion']) ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-unsxx-blue-900"><?= htmlspecialchars($logro['nom_log']) ?></div>
                            <?php if (!empty($logro['categoria_logro'])): ?>
                                <span class="inline-block mt-1 text-xs px-2 py-0.5 bg-purple-50 text-purple-700 border border-purple-200 rounded-md">
                                    <?= htmlspecialchars($logro['categoria_logro']) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-gray-800 font-medium"><?= htmlspecialchars(date('Y', strtotime($logro['fech_log']))) ?></div>
                            <div class="text-xs text-gray-500 mt-0.5 truncate max-w-[150px]"><?= htmlspecialchars($logro['lugar_log'] ?? 'No especificado') ?></div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <?php if (!empty($logro['foto_logro'])): ?>
                                <a href="/uploads/img/logros/<?= htmlspecialchars($logro['foto_logro']) ?>" target="_blank" class="inline-block hover:opacity-80">
                                    <img src="/uploads/img/logros/<?= htmlspecialchars($logro['foto_logro']) ?>" class="w-10 h-10 object-cover rounded shadow-sm border">
                                </a>
                            <?php else: ?>
                                <span class="text-xs text-gray-400 italic">Sin foto</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form action="/admin/logros/<?= $logro['id_log'] ?>/toggle" method="POST" class="m-0">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" onChange="this.form.submit()" class="sr-only peer" <?= $logro['activo'] ? 'checked' : '' ?>>
                                    <div class="relative w-9 h-5 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                                </label>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="/admin/logros/<?= $logro['id_log'] ?>/edit" class="p-1.5 text-unsxx-blue-600 hover:bg-blue-50 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="/admin/logros/<?= $logro['id_log'] ?>/delete" method="POST" class="inline-block form-eliminar">
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg">
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

<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<style>
    .dataTable-wrapper .dataTable-container { overflow-x: auto; border-bottom: 1px solid #e5e7eb; }
    .dataTable-top { display: flex; justify-content: space-between; align-items: center; padding-bottom: 1.5rem; }
    .dataTable-input { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem 1rem; outline: none; }
    .dataTable-input:focus { border-color: #1e3a8a; box-shadow: 0 0 0 2px rgba(30, 58, 138, 0.2); }
    .dataTable-table>thead>tr>th { background-color: #1e3a8a !important; color: #ffffff !important; border-bottom: none; }
    .dataTable-table th a { color: #ffffff !important; text-decoration: none; }
</style>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.getElementById("logrosTable");
        if (table) {
            new simpleDatatables.DataTable(table, {
                searchable: true,
                fixedHeight: false,
                perPage: 10,
                perPageSelect: [10, 15, 20, 30],
                labels: {
                    placeholder: "Buscar logro o evento",
                    noRows: "No hay logros registrados",
                    info: "Mostrando {start} a {end} de {rows} logros",
                    perPage: "registros por página",
                    searchLabel: "Buscar: "
                }
            });
        }
        document.querySelectorAll('.form-eliminar').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar logro?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#3b82f6',
                    confirmButtonText: 'Sí, eliminar',
                    reverseButtons: true
                }).then((r) => { if (r.isConfirmed) this.submit(); });
            });
        });
    });
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>