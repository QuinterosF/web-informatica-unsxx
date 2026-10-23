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

<!-- Mensajes Flash Especiales: Credenciales listas para enviar directo por WhatsApp o copiar (RF-05) -->
<?php if (isset($_SESSION['temp_credentials'])): ?>
    <?php
    $creds = $_SESSION['temp_credentials'];
    $credList = isset($creds['email']) ? [$creds] : (is_array($creds) ? $creds : []);
    ?>
    <?php foreach ($credList as $cred): ?>
        <?php
        $rawPhone = $cred['phone'] ?? ($cred['usu_cel'] ?? '');
        $cleanPhone = preg_replace('/\D+/', '', (string) $rawPhone);
        if (strlen($cleanPhone) === 8) {
            $cleanPhone = '591' . $cleanPhone;
        }
        $waMessage = "*CREDENCIALES DE ACCESO AL SITIO WEB DE ING. INFORMÁTICA - UNSXX*\n\n*Correo:* " . ($cred['email'] ?? '') . "\n*Contraseña temporal:* " . ($cred['password'] ?? '') . "\n\n_(Se recomienda cambiar la contraseña al ingresar)_";
        $waDirectUrl = !empty($cleanPhone)
            ? "https://wa.me/{$cleanPhone}?text=" . urlencode($waMessage)
            : "https://wa.me/?text=" . urlencode($waMessage);
        ?>
        <div
            class="mb-6 p-5 bg-amber-50 border-l-4 border-amber-500 rounded-xl shadow-sm flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-amber-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    Credenciales Generadas<?= !empty($cred['name']) ? ' para ' . htmlspecialchars($cred['name']) : '' ?>
                </h3>
                <div
                    class="mt-2 inline-flex flex-wrap items-center gap-3 bg-white px-3.5 py-2 rounded-lg border border-amber-200 font-mono text-xs text-gray-800">
                    <span><strong>Correo:</strong> <?= htmlspecialchars($cred['email']) ?></span>
                    <span>•</span>
                    <span><strong>Clave temporal:</strong> <span
                            class="text-red-600 font-bold"><?= htmlspecialchars($cred['password']) ?></span></span>
                    <?php if (!empty($rawPhone)): ?>
                        <span>•</span>
                        <span><strong>Celular:</strong> <?= htmlspecialchars($rawPhone) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="<?= htmlspecialchars($waDirectUrl) ?>" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824z" />
                    </svg>
                    <?= !empty($cleanPhone) ? 'Enviar al WhatsApp del Usuario' : 'Enviar por WhatsApp' ?>
                </a>

                <button type="button"
                    onclick="navigator.clipboard.writeText('*CREDENCIALES DE ACCESO AL SITIO WEB DE ING. INFORMÁTICA - UNSXX*\n\n*Correo:* <?= htmlspecialchars(addslashes($cred['email'])) ?>\n*Contraseña temporal:* <?= htmlspecialchars(addslashes($cred['password'])) ?>\n\n_(Se recomienda cambiar la contraseña al ingresar)_'); Swal.fire({toast:true, position:'top-end', icon:'success', title:'Copiado al portapapeles', showConfirmButton:false, timer:2000});"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-unsxx-blue-900 hover:bg-unsxx-blue-800 text-white text-xs font-bold rounded-lg shadow-sm cursor-pointer">
                    <svg class="w-4 h-4 text-unsxx-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                    </svg>
                    Copiar Texto
                </button>
            </div>
        </div>
    <?php endforeach; ?>
    <?php unset($_SESSION['temp_credentials']); ?>
<?php endif; ?>

<!-- Solicitudes de Restauración Pendientes -->
<?php if (!empty($pendingResets)): ?>
    <div class="mb-6 bg-red-50 border-l-4 border-red-600 rounded-xl shadow-sm p-4">
        <p class="text-xs font-bold text-red-900 uppercase tracking-wide mb-2">
            Solicitudes pendientes de restauración de contraseña (<?= count($pendingResets) ?>)
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <?php foreach ($pendingResets as $reqUser): ?>
                <div class="bg-white border border-red-200 rounded-lg p-3 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-unsxx-blue-900 truncate"><?= htmlspecialchars($reqUser['usu_nom']) ?>
                        </p>
                        <p class="text-xs font-mono text-red-700 truncate"><?= htmlspecialchars($reqUser['usu_correo']) ?></p>
                    </div>
                    <form action="/users/<?= (int) $reqUser['usu_id'] ?>/reset-password" method="POST" class="m-0 shrink-0">
                        <button type="submit"
                            class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg cursor-pointer">
                            Generar clave ahora
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Cabecera y Acciones Superiores -->
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
    <div>
        <p class="text-sm text-gray-500">
            Administra quién tiene acceso al sistema. <strong>Regla de auditoría:</strong> Desactiva las cuentas que
            cumplan su gestión en lugar de eliminarlas.
        </p>
    </div>
    <div class="flex flex-wrap items-center gap-3 ml-auto shrink-0">
        <form action="/users/sync-labs" method="POST" class="m-0">
            <button type="submit"
                class="inline-flex items-center gap-2 bg-teal-50 hover:bg-teal-100 text-teal-800 border border-teal-200 font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm text-xs cursor-pointer">
                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                Sincronizar Responsables de Lab
            </button>
        </form>

        <a href="/users/create"
            class="inline-flex items-center gap-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold px-4 py-2 rounded-lg transition-colors shadow-sm text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg>
            Nuevo Usuario
        </a>
    </div>
</div>

<!-- Tabla de Usuarios -->
<div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table id="usersTable" class="w-full text-sm text-left">
            <thead class="text-xs text-white uppercase bg-unsxx-blue-900">
                <tr>
                    <th scope="col" class="px-4 py-3">Usuario / Contacto</th>
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
                        $requestedReset = !empty($u['solicitud_reset']);
                        $userPhone = $u['usu_cel'] ?? ($u['cel_acad'] ?? '');
                        $mods = is_array($u['modulos'] ?? null)
                            ? $u['modulos']
                            : (json_decode($u['modulos'] ?? '[]', true) ?: []);
                        $isSuperAdmin = ((int) $u['usu_id'] === 1);
                        ?>
                        <tr
                            class="hover:bg-unsxx-blue-50/40 transition-colors <?= !$isActive ? 'bg-gray-50/80 opacity-70' : ($requestedReset ? 'bg-red-50/40' : '') ?>">
                            <!-- Nombre, Correo y Celular -->
                            <td class="px-4 py-3">
                                <div class="font-bold text-gray-900 flex flex-wrap items-center gap-1.5">
                                    <?= htmlspecialchars($u['usu_nom']) ?>
                                    <?php if ($isCurrentUser): ?>
                                        <span
                                            class="text-[10px] bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full font-semibold">Tú</span>
                                    <?php endif; ?>
                                    <?php if ($requestedReset): ?>
                                        <span
                                            class="inline-flex items-center gap-1 text-[10px] bg-red-100 text-red-800 border border-red-300 px-2 py-0.5 rounded-full font-bold animate-pulse">
                                            Solicitó restaurar clave
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-xs text-gray-500 font-mono mt-0.5"><?= htmlspecialchars($u['usu_correo']) ?>
                                </div>
                                <?php if (!empty($userPhone)): ?>
                                    <div class="text-[11px] text-teal-700 font-mono mt-0.5">Cel: <?= htmlspecialchars($userPhone) ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Vínculo Académico -->
                            <td class="px-4 py-3">
                                <?php if (!empty($u['nom_acad'])): ?>
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs font-medium text-unsxx-blue-800 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200">
                                        <svg class="w-3.5 h-3.5 text-unsxx-blue-600 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
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
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                        <svg class="w-3.5 h-3.5 text-amber-700 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        Administrador
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-300">
                                        <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Operador
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Módulos Permitidos + Laboratorios -->
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
                                            <span
                                                class="px-2 py-0.5 text-[11px] font-bold bg-teal-50 text-teal-800 border border-teal-200 rounded inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
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

                            <!-- Estado de Acceso -->
                            <td class="px-4 py-3 text-center">
                                <?php if ($isCurrentUser): ?>
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Activo
                                    </span>
                                <?php else: ?>
                                    <form action="/users/<?= (int) $u['usu_id'] ?>/toggle" method="POST" class="m-0 inline-block">
                                        <label class="inline-flex items-center cursor-pointer">
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

                            <!-- Acciones -->
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">

                                    <?php if (!$isSuperAdmin): ?>
                                        <!-- Botón Restaurar Clave para usuarios normales -->
                                        <form action="/users/<?= (int) $u['usu_id'] ?>/reset-password" method="POST"
                                            class="inline-block form-reset-pass"
                                            data-username="<?= htmlspecialchars($u['usu_nom']) ?>">
                                            <button type="submit" title="Restaurar contraseña temporal"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold <?= $requestedReset ? 'text-white bg-red-600 hover:bg-red-700 border-red-600 shadow-xs' : 'text-amber-800 bg-amber-50 hover:bg-amber-100 border-amber-200' ?> border rounded-lg transition-colors cursor-pointer">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                                </svg>
                                                Restaurar clave
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <!-- Badge de Seguridad para el Super Admin -->
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-bold text-gray-400 bg-gray-50 border border-transparent rounded-lg cursor-not-allowed"
                                            title="La clave del Super Administrador no puede ser restaurada desde aquí">
                                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                            Protegido
                                        </span>
                                    <?php endif; ?>

                                    <!-- Botón Editar -->
                                    <a href="/users/<?= (int) $u['usu_id'] ?>/edit" title="Editar Usuario y Módulos"
                                        class="p-1.5 text-unsxx-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
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
                    placeholder: "Buscar usuario, correo, celular, laboratorio...",
                    noRows: "No hay usuarios registrados",
                    info: "Mostrando {start} a {end} de {rows} usuarios",
                    perPage: "registros por página"
                }
            });
        }

        document.querySelectorAll('.form-reset-pass').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const userName = this.getAttribute('data-username');
                Swal.fire({
                    title: '¿Restaurar contraseña?',
                    text: "Se generará una nueva contraseña temporal para '" + userName + "' y podrás enviársela directo a su WhatsApp.",
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