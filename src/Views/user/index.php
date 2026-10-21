<?php
$title = 'Usuarios y Roles - Informática UNSXX';
$pageTitle = 'Gestión de Usuarios, Roles y Accesos';

$moduleLabels = [
    'institucion' => 'Identidad y Textos',
    'portadas' => 'Portadas de Inicio',
    'redes' => 'Directorio y Redes',
    'academics' => 'Plantel Docente',
    'curricular' => 'Malla / Menciones',
    'laboratories' => 'Laboratorios (Global)',
    'admissions' => 'Admisión',
    'communications' => 'Comunicados',
    'documents' => 'Documentos Oficiales',
    'extensions' => 'Extensión y Eventos',
    'logros' => 'Logros de la Carrera',
];

ob_start();
?>

<!-- Mensajes Flash Especiales: Credenciales listas para copiar a WhatsApp -->
<?php if (isset($_SESSION['temp_credentials'])): ?>
    <?php
    $creds = $_SESSION['temp_credentials'];
    // Soportar tanto 1 usuario ['email'=>..., 'password'=>...] como varios [['email'=>...], ...]
    $credList = isset($creds['email']) ? [$creds] : (is_array($creds) ? $creds : []);
    ?>
    <?php foreach ($credList as $cred): ?>
        <div
            class="mb-6 p-5 bg-amber-50 border-l-4 border-amber-500 rounded-xl shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-amber-900 flex items-center gap-2">
                    <span>🔐</span> Credenciales de Acceso
                    Generadas<?= !empty($cred['name']) ? ' para ' . htmlspecialchars($cred['name']) : '' ?>
                </h3>
                <p class="text-xs text-amber-800 mt-1">
                    Copia estos datos con el botón de la derecha para enviarlos por WhatsApp al usuario:
                </p>
                <div
                    class="mt-2 inline-flex flex-wrap items-center gap-3 bg-white px-3.5 py-2 rounded-lg border border-amber-200 font-mono text-xs text-gray-800">
                    <span><strong>Correo:</strong> <?= htmlspecialchars($cred['email']) ?></span>
                    <span>•</span>
                    <span><strong>Contraseña temporal:</strong> <span
                            class="text-red-600 font-bold"><?= htmlspecialchars($cred['password']) ?></span></span>
                </div>
            </div>
            <button type="button"
                onclick="navigator.clipboard.writeText('*CREDENCIALES DE ACCESO AL SITIO WEB DE ING. INFORMÁTICA - UNSXX*\n\n*Correo:* <?= htmlspecialchars(addslashes($cred['email'])) ?>\n*Contraseña temporal:* <?= htmlspecialchars(addslashes($cred['password'])) ?>\n\n_(Se recomienda cambiar la contraseña al ingresar)_'); Swal.fire({toast:true, position:'top-end', icon:'success', title:'Copiado al portapapeles', showConfirmButton:false, timer:2000});"
                class="px-4 py-2 bg-unsxx-blue-900 hover:bg-unsxx-blue-800 text-white text-xs font-bold rounded-lg shadow-sm shrink-0 cursor-pointer">
                Copiar para WhatsApp
            </button>
        </div>
    <?php endforeach; ?>
    <?php unset($_SESSION['temp_credentials']); ?>
<?php endif; ?>

<!-- Cabecera y Acciones Superiores -->
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
    <div>
        <p class="text-sm text-gray-500">
            Administra quién tiene acceso al sistema. <br><strong>Regla de seguridad:</strong> Cuando una autoridad,
            coordinador o secretaria cumpla su gestión, desactiva su cuenta con el interruptor en lugar de eliminarla
            para preservar su historial de registros.
        </p>
    </div>
    <div class="flex flex-wrap items-center gap-3 ml-auto shrink-0">
        <!-- Botón para sincronizar automáticamente cuentas de Responsables de Laboratorio -->
        <form action="/users/sync-labs" method="POST" class="m-0">
            <button type="submit"
                title="Crea automáticamente cuentas de acceso para los docentes asignados como Responsables de Laboratorio que aún no tengan usuario"
                class="inline-flex items-center gap-2 bg-teal-50 hover:bg-teal-100 text-teal-800 border border-teal-200 font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm text-xs cursor-pointer">
                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                Sincronizar Responsables de Lab
            </button>
        </form>

        <!-- Botón Nuevo Usuario -->
        <a href="/users/create"
            class="inline-flex items-center gap-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold px-4 py-2 rounded-lg transition-colors shadow-sm text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nuevo Usuario
        </a>
    </div>
</div>

<!-- SOLICITUDES DE RESTAURACIÓN DE CONTRASEÑA (CON NOMBRE, CORREO Y ACCIÓN DIRECTA) -->
    <?php if (!empty($pendingResets)): ?>
        <div class="mb-8 bg-amber-50 border-l-4 border-amber-500 rounded-2xl shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2 rounded-xl bg-amber-100 text-amber-700 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-amber-900">
                        Solicitudes de Restauración de Contraseña (<?= count($pendingResets) ?>)
                    </h3>
                    <p class="text-xs text-amber-800">
                        Los siguientes usuarios indicaron en el Login que olvidaron su contraseña. Restáurala con un clic para generar su clave temporal:
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                <?php foreach ($pendingResets as $reqUser): ?>
                    <div class="bg-white border border-amber-200 rounded-xl p-3.5 flex items-center justify-between gap-3 shadow-2xs">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-unsxx-blue-900 truncate">
                                <?= htmlspecialchars($reqUser['usu_nom']) ?>
                            </p>
                            <p class="text-xs font-mono text-amber-800 truncate mt-0.5">
                                <?= htmlspecialchars($reqUser['usu_correo']) ?>
                            </p>
                        </div>
                        <form action="/users/<?= (int)$reqUser['usu_id'] ?>/reset-password" method="POST" class="m-0 shrink-0">
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-unsxx-blue-900 hover:bg-unsxx-blue-800 text-white text-xs font-bold rounded-lg shadow-xs transition-colors cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-unsxx-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Restaurar clave ahora
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

<!-- Tabla de Usuarios -->
<div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table id="usersTable" class="w-full text-sm text-left">
            <thead class="text-xs text-white uppercase bg-unsxx-blue-900">
                <tr>
                    <th scope="col" class="px-4 py-3">Usuario / Correo</th>
                    <th scope="col" class="px-4 py-3">Vínculo Académico</th>
                    <th scope="col" class="px-4 py-3">Rol</th>
                    <th scope="col" class="px-4 py-3">Módulos y Laboratorios Permitidos</th>
                    <th scope="col" class="px-4 py-3 text-center">Acceso</th>
                    <th data-sortable="false" scope="col" class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                        <?php
                        $isCurrentUser = ((int) $u['usu_id'] === (int) ($_SESSION['user_id'] ?? 0));
                        $isAdminRole = (($u['rol_nombre'] ?? '') === 'admin' || (int) ($u['rol_id'] ?? 0) === 1);
                        $isActive = !empty($u['estado']);
                        $mods = is_array($u['modulos'] ?? null)
                            ? $u['modulos']
                            : (json_decode($u['modulos'] ?? '[]', true) ?: []);
                        ?>
                        <tr
                            class="hover:bg-unsxx-blue-50/40 transition-colors <?= !$isActive ? 'bg-gray-50/80 opacity-70' : '' ?>">
                            <!-- Nombre y Correo -->
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900 flex items-center gap-1.5">
                                    <?= htmlspecialchars($u['usu_nom']) ?>
                                    <?php if ($isCurrentUser): ?>
                                        <span
                                            class="text-[10px] bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full font-semibold">Tú</span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-xs text-gray-500 font-mono mt-0.5"><?= htmlspecialchars($u['usu_correo']) ?>
                                </div>
                            </td>

                            <!-- Vínculo con Plantel Docente/Administrativo -->
                            <td class="px-4 py-3">
                                <?php if (!empty($u['nom_acad'])): ?>
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-unsxx-blue-800 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200">
                                        <svg class="w-3.5 h-3.5 text-unsxx-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <?= htmlspecialchars($u['nom_acad']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400 italic">Cuenta institucional / Sin ficha</span>
                                <?php endif; ?>
                            </td>

                            <!-- Rol -->
                            <td class="px-4 py-3">
                                <?php if ($isAdminRole): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                        <svg class="w-3.5 h-3.5 text-amber-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        Administrador
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-300">
                                        <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Operador
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Módulos Permitidos + Laboratorios a Cargo -->
                            <td class="px-4 py-3">
                                <?php if ($isAdminRole): ?>
                                    <span
                                        class="text-xs font-semibold text-green-700 bg-green-50 px-2.5 py-1 rounded-md border border-green-200">
                                        Acceso Total al Sistema (Todos los módulos)
                                    </span>
                                <?php else: ?>
                                    <div class="flex flex-wrap gap-1.5 max-w-md">
                                        <?php foreach ($mods as $mKey): ?>
                                            <span
                                                class="px-2 py-0.5 text-[11px] font-medium bg-blue-50 text-unsxx-blue-800 border border-blue-200 rounded">
                                                <?= htmlspecialchars($moduleLabels[$mKey] ?? $mKey) ?>
                                            </span>
                                        <?php endforeach; ?>

                                        <?php if (!empty($u['labs_asignados'])): ?>
                                            <span class="px-2 py-0.5 text-[11px] font-bold bg-teal-50 text-teal-800 border border-teal-200 rounded inline-flex items-center gap-1" title="Acceso automático por ser Responsable de Laboratorio">
                                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                Lab: <?= htmlspecialchars($u['labs_asignados']) ?>
                                            </span>
                                        <?php endif; ?>

                                        <?php if (empty($mods) && empty($u['labs_asignados'])): ?>
                                            <span class="text-xs text-red-500 italic">Sin módulos asignados</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Estado de Acceso (Activo / Inactivo) -->
                            <td class="px-4 py-3 text-center">
                                <?php if ($isCurrentUser): ?>
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200"
                                        title="No puedes desactivar tu propia cuenta activa">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Activo
                                    </span>
                                <?php else: ?>
                                    <form action="/users/<?= (int) $u['usu_id'] ?>/toggle" method="POST" class="m-0 inline-block">
                                        <label class="inline-flex items-center cursor-pointer"
                                            title="<?= $isActive ? 'Desactivar acceso al sistema (Conserva auditoría)' : 'Reactivar acceso al sistema' ?>">
                                            <input type="checkbox" onChange="this.form.submit()" class="sr-only peer" <?= $isActive ? 'checked' : '' ?>>
                                            <div
                                                class="relative w-9 h-5 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600">
                                            </div>
                                            <span
                                                class="ms-2 text-xs font-semibold <?= $isActive ? 'text-green-700' : 'text-gray-500' ?>">
                                                <?= $isActive ? 'Activo' : 'Inactivo' ?>
                                            </span>
                                        </label>
                                    </form>
                                <?php endif; ?>
                            </td>

                            <!-- Acciones: Restaurar Contraseña + Editar -->
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Botón Restaurar Contraseña por Defecto -->
                                    <form action="/users/<?= (int) $u['usu_id'] ?>/reset-password" method="POST"
                                        class="inline-block form-reset-pass"
                                        data-username="<?= htmlspecialchars($u['usu_nom']) ?>">
                                        <button type="submit" title="Restaurar contraseña temporal"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg transition-colors cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                            </svg>
                                            Restaurar clave
                                        </button>
                                    </form>

                                    <!-- Botón Editar Usuario y Permisos -->
                                    <a href="/users/<?= (int) $u['usu_id'] ?>/edit" title="Editar Usuario y Módulos"
                                        class="p-1.5 text-unsxx-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

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

    .dataTable-input,
    .dataTable-selector {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        outline: none;
        font-size: 0.875rem;
    }

    .dataTable-table>thead>tr>th {
        background-color: #1e3a8a !important;
        color: #ffffff !important;
    }

    .dataTable-table th a {
        color: #ffffff !important;
        text-decoration: none;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.getElementById("usersTable");
        if (table) {
            new simpleDatatables.DataTable(table, {
                searchable: true,
                perPage: 10,
                labels: {
                    placeholder: "Buscar usuario, correo, laboratorio...",
                    noRows: "No hay usuarios registrados",
                    info: "Mostrando {start} a {end} de {rows} usuarios",
                    perPage: "registros por página",
                    searchLabel: "Buscar: "
                }
            });
        }

        document.querySelectorAll('.form-reset-pass').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const userName = this.getAttribute('data-username');
                Swal.fire({
                    title: '¿Restaurar contraseña?',
                    text: "Se generará una nueva contraseña temporal para '" + userName + "' y se mostrará en pantalla para que puedas compartírsela.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d97706',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, generar clave temporal',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) this.submit();
                });
            });
        });
    });
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>