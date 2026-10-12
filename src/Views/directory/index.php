<?php
$title = 'Directorio y Redes - Informática UNSXX';
$pageTitle = 'Directorio y Redes Institucionales';
ob_start();
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-unsxx-blue-900 hidden sm:block">Directorio y Redes</h2>
        <p class="text-xs text-gray-500 mt-1 hidden sm:block">Gestione los números de contacto y enlaces externos del
            portal.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">

    <!-- Pestañas -->
    <div class="border-b border-gray-200">
        <nav class="flex overflow-x-auto" aria-label="Tabs">
            <button onclick="changeTab(event, 'tab-phones')"
                class="tab-btn active border-unsxx-blue-600 text-unsxx-blue-600 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                <i class="fas fa-phone-alt mr-2"></i> Teléfonos de Contacto
            </button>
            <button onclick="changeTab(event, 'tab-links')"
                class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                <i class="fas fa-link mr-2"></i> Enlaces y Redes Sociales
            </button>
        </nav>
    </div>

    <!-- CONTENIDO: Teléfonos -->
    <div id="tab-phones" class="tab-content block p-6">
        <div class="flex justify-end mb-4">
            <a href="/directory/phones/create"
                class="inline-flex items-center gap-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold px-4 py-2 rounded-lg shadow-sm transition-all text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Teléfono
            </a>
        </div>
        <div class="overflow-x-auto">
            <table id="phonesTable" class="w-full text-sm text-left">
                <thead class="text-xs text-white uppercase bg-unsxx-blue-900">
                    <tr>
                        <th class="px-6 py-3 rounded-tl-lg w-16 text-center">Ícono</th>
                        <th class="px-6 py-3">Referencia (Nombre)</th>
                        <th class="px-6 py-3">Número</th>
                        <th class="px-6 py-3 text-center">Estado</th>
                        <th class="px-6 py-3 text-right rounded-tr-lg">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (!empty($phones)): ?>
                        <?php foreach ($phones as $item): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center text-2xl">
                                    <i class="<?= htmlspecialchars($item['icono']) ?> brand-icon"></i>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900"><?= htmlspecialchars($item['nom_cel']) ?></td>
                                <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($item['num_cel']) ?></td>
                                <td class="px-6 py-4 text-center">
                                    <?php if ($item['estado']): ?>
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">Activo</span>
                                    <?php else: ?>
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="/directory/phones/<?= $item['id_cel'] ?>/edit"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg"><svg class="w-5 h-5"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg></a>
                                        <form action="/directory/phones/<?= $item['id_cel'] ?>/delete" method="POST"
                                            class="inline form-delete">
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg"><svg
                                                    class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg></button>
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

    <!-- CONTENIDO: Enlaces y Redes -->
    <div id="tab-links" class="tab-content hidden p-6">
        <div class="flex justify-end mb-4">
            <a href="/directory/links/create"
                class="inline-flex items-center gap-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold px-4 py-2 rounded-lg shadow-sm transition-all text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Enlace
            </a>
        </div>
        <div class="overflow-x-auto">
            <table id="linksTable" class="w-full text-sm text-left">
                <thead class="text-xs text-white uppercase bg-unsxx-blue-900">
                    <tr>
                        <th class="px-6 py-3 rounded-tl-lg w-16 text-center">Ícono</th>
                        <th class="px-6 py-3">Nombre / URL</th>
                        <th class="px-6 py-3">Ubicaciones (Dónde aparece)</th>
                        <th class="px-6 py-3 text-center">Estado</th>
                        <th class="px-6 py-3 text-right rounded-tr-lg">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (!empty($links)): ?>
                        <?php
                        $nombresUbicacion = [
                            'footer_redes' => 'Pie (Redes)',
                            'footer_enlaces' => 'Pie (Enlaces)',
                            'menu_servicios' => 'Menú (Servicios)',
                            'menu_investigacion' => 'Menú (Investigación)'
                        ];
                        foreach ($links as $item): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center text-2xl">
                                    <i class="<?= htmlspecialchars($item['icono']) ?> brand-icon"></i>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900"><?= htmlspecialchars($item['nombre']) ?></div>
                                    <a href="<?= htmlspecialchars($item['url']) ?>" target="_blank"
                                        class="text-blue-600 hover:underline flex items-center gap-1 text-xs mt-1">
                                        Ir al sitio <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                            </path>
                                        </svg>
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <div class="flex flex-wrap gap-1">
                                        <?php
                                        // Leemos EXACTAMENTE la variable que genera el GROUP_CONCAT del Modelo
                                        $ubicacionesString = $item['ubicaciones_str'] ?? '';

                                        // Lo convertimos en un array, separándolo por comas
                                        $ubicacionesArray = explode(',', $ubicacionesString);

                                        $tieneUbicaciones = false;

                                        foreach ($ubicacionesArray as $ub) {
                                            $ub = trim($ub);
                                            if (!empty($ub) && isset($nombresUbicacion[$ub])) {
                                                echo '<span class="bg-gray-100 border border-gray-200 px-2 py-0.5 rounded text-xs text-gray-700">' . $nombresUbicacion[$ub] . '</span>';
                                                $tieneUbicaciones = true;
                                            }
                                        }

                                        // Si no se imprimió ninguna insignia, mostramos el aviso
                                        if (!$tieneUbicaciones) {
                                            echo '<span class="text-xs text-red-500 italic">Sin ubicación</span>';
                                        }
                                        ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if ($item['estado']): ?>
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">Activo</span>
                                    <?php else: ?>
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="/directory/links/<?= $item['id_enl'] ?>/edit"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg"><svg class="w-5 h-5"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg></a>
                                        <form action="/directory/links/<?= $item['id_enl'] ?>/delete" method="POST"
                                            class="inline form-delete">
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg"><svg
                                                    class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg></button>
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
</div>

<!-- Estilos y Scripts -->
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .brand-icon.fa-whatsapp {
        color: #25D366;
    }

    .brand-icon.fa-facebook {
        color: #1877F2;
    }

    .brand-icon.fa-telegram {
        color: #0088cc;
    }

    .brand-icon.fa-tiktok {
        color: #000000;
    }

    .brand-icon.fa-youtube {
        color: #FF0000;
    }

    .brand-icon.fa-instagram {
        color: #E4405F;
    }

    .brand-icon.fa-twitter {
        color: #1DA1F2;
    }

    .brand-icon.fa-linkedin {
        color: #0a66c2;
    }

    .brand-icon:not(.fa-whatsapp):not(.fa-facebook):not(.fa-telegram):not(.fa-tiktok):not(.fa-youtube):not(.fa-instagram):not(.fa-twitter):not(.fa-linkedin) {
        color: #1e3a8a;
    }

    .dataTable-search label {
        font-size: 0 !important;
    }

    .dataTable-search input {
        font-size: 0.875rem !important;
    }

    .dataTable-wrapper .dataTable-container {
        overflow-x: auto;
        border-bottom: 1px solid #e5e7eb;
    }

    .dataTable-top {
        padding-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dataTable-input {
        border-radius: 0.5rem;
        border: 1px solid #d1d5db;
        padding: 0.5rem;
        outline: none;
    }

    .dataTable-input:focus {
        border-color: #1e3a8a;
        box-shadow: 0 0 0 2px rgba(30, 58, 138, 0.2);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script>
    function changeTab(event, tabId) {
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden'); el.classList.remove('block');
        });
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('border-unsxx-blue-600', 'text-unsxx-blue-600', 'active');
            el.classList.add('border-transparent', 'text-gray-500');
        });
        document.getElementById(tabId).classList.remove('hidden');
        document.getElementById(tabId).classList.add('block');
        event.currentTarget.classList.remove('border-transparent', 'text-gray-500');
        event.currentTarget.classList.add('border-unsxx-blue-600', 'text-unsxx-blue-600', 'active');
    }

    document.addEventListener("DOMContentLoaded", function () {
        const dtLabels = {
            placeholder: "Buscar registro...",
            noRows: "No hay registros",
            info: "Mostrando {start} a {end} de {rows}",
            perPage: "registros por página",
            searchTitle: "Buscar",
            searchLabel: "Buscar: "
        };
        if (document.getElementById("phonesTable")) {
            new simpleDatatables.DataTable(document.getElementById("phonesTable"), { searchable: true, perPage: 10, labels: dtLabels });
        }
        if (document.getElementById("linksTable")) {
            new simpleDatatables.DataTable(document.getElementById("linksTable"), { searchable: true, perPage: 10, labels: dtLabels });
        }
    });

    document.body.addEventListener('submit', function (e) {
        if (e.target && e.target.matches('.form-delete')) {
            e.preventDefault();
            const form = e.target;
            Swal.fire({
                title: '¿Eliminar registro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#3b82f6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        }
    });
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>