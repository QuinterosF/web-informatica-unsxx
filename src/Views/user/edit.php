<?php
$title = 'Editar Usuario - Informática UNSXX';
$pageTitle = 'Editar Cuenta y Permisos de Usuario';

$catalogModules = [
    'institucion'    => ['label' => 'Identidad y Textos', 'desc' => 'Misión, visión, historia y mensaje del director'],
    'portadas'       => ['label' => 'Fotos Institucionales', 'desc' => 'Carrusel de imágenes de portada'],
    'redes'          => ['label' => 'Directorio y Redes', 'desc' => 'Teléfonos, WhatsApp y enlaces de servicio'],
    'academics'      => ['label' => 'Plantel Docente y Adm.', 'desc' => 'Registro de docentes, coordinadores y personal'],
    'curricular'     => ['label' => 'Menciones e Institutos', 'desc' => 'Áreas curriculares e investigación'],
    'laboratories'   => ['label' => 'Laboratorios (Global)', 'desc' => 'Control total de todos los laboratorios'],
    'admissions'     => ['label' => 'Admisión', 'desc' => 'Procesos de prueba de suficiencia y preuniversitario'],
    'communications' => ['label' => 'Comunicados Oficiales', 'desc' => 'Publicación de avisos y comunicados'],
    'documents'      => ['label' => 'Documentos Oficiales', 'desc' => 'Reglamentos, horarios generales y planes PDF'],
    'extensions'     => ['label' => 'Extensión y Eventos', 'desc' => 'Noticias de eventos, ICPC, FLISoL y galerías'],
    'logros'         => ['label' => 'Logros de la Carrera', 'desc' => 'Salón de la fama y premios históricos'],
];

$userMods = is_array($user['modulos'] ?? null)
    ? $user['modulos']
    : (json_decode($user['modulos'] ?? '[]', true) ?: []);
$currentRoleId = (int)($user['rol_id'] ?? 2);
$isEditingSelf = ((int)($user['usu_id'] ?? 0) === (int)($_SESSION['user_id'] ?? 0));
$isSuperAdmin = ((int)($user['usu_id'] ?? 0) === 1);

ob_start();
?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-xl font-bold text-unsxx-blue-900">Editar: <?= htmlspecialchars($user['usu_nom']) ?></h2>
        <p class="text-xs text-gray-500 mt-0.5">Actualiza los datos de contacto, el vínculo académico o los módulos permitidos.</p>
    </div>
    <a href="/users" class="inline-flex items-center gap-2 text-unsxx-blue-600 hover:text-unsxx-blue-800 font-medium">
        ← Volver a Usuarios
    </a>
</div>

<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden p-6 sm:p-8">

    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded text-sm">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="/users/<?= (int)$user['usu_id'] ?>/edit" method="POST" class="space-y-6">

        <!-- 1. Vínculo con Plantel Docente -->
        <div class="p-4 bg-blue-50/60 border border-blue-200 rounded-xl">
            <label for="id_acad" class="block text-sm font-bold text-unsxx-blue-900 mb-1">
                Vínculo con Plantel Docente / Administrativo
            </label>
            <select id="id_acad" name="id_acad" onchange="fillFromAcademicEdit(this)"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none bg-white text-sm">
                <option value="" data-phone="">-- Sin vínculo académico --</option>
                <?php if (!empty($academics)): ?>
                    <?php foreach ($academics as $acad): ?>
                        <?php $sel = ((int)($user['id_acad'] ?? 0) === (int)$acad['id_acad']) ? 'selected' : ''; ?>
                        <option value="<?= (int)$acad['id_acad'] ?>"
                                data-phone="<?= htmlspecialchars($acad['cel_acad'] ?? '') ?>"
                                <?= $sel ?>>
                            <?= htmlspecialchars($acad['nom_acad']) ?> (<?= htmlspecialchars($acad['email_acad']) ?>)
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <!-- 2. Datos Principales (Sin campo contraseña por RF-06) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="usu_nom" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Usuario <span class="text-red-500">*</span></label>
                <input type="text" id="usu_nom" name="usu_nom" required
                    value="<?= htmlspecialchars($user['usu_nom']) ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none text-sm">
            </div>

            <div>
                <label for="usu_correo" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico (Login) <span class="text-red-500">*</span></label>
                <input type="email" id="usu_correo" name="usu_correo" required
                    value="<?= htmlspecialchars($user['usu_correo']) ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none text-sm">
            </div>

            <div>
                <label for="usu_cel" class="block text-sm font-medium text-gray-700 mb-1">Número de Celular (WhatsApp) <span class="text-red-500">*</span></label>
                <input type="text" id="usu_cel" name="usu_cel" required placeholder="Ej: 72400000"
                    value="<?= htmlspecialchars($user['usu_cel'] ?? ($user['cel_acad'] ?? '')) ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none text-sm font-mono">
            </div>

            <div>
                <label for="rol_id" class="block text-sm font-medium text-gray-700 mb-1">Nivel de Rol en el Sistema <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-unsxx-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <select id="rol_id" name="rol_id" onchange="toggleModulesPanel()"
                        class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg outline-none text-sm <?= $isSuperAdmin ? 'bg-gray-100 opacity-60 cursor-not-allowed' : 'bg-white focus:ring-2 focus:ring-unsxx-blue-500' ?>"
                        <?= $isSuperAdmin ? 'disabled' : '' ?>>
                        <option value="2" <?= $currentRoleId === 2 ? 'selected' : '' ?>>Operador / Docente / Secretaria (Acceso por Módulos)</option>
                        <option value="1" <?= $currentRoleId === 1 ? 'selected' : '' ?>>Administrador General (Acceso Total)</option>
                    </select>
                    
                    <?php if ($isSuperAdmin): ?>
                        <!-- Si el select está disabled, HTML no envía el valor. Usamos un hidden input para enviar el rol = 1 -->
                        <input type="hidden" name="rol_id" value="1">
                        <p class="text-[10px] text-red-600 mt-1.5 font-bold flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Cuenta de Super Administrador Institucional (Inmutable)
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($isEditingSelf): ?>
            <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-xl flex items-center justify-between gap-4">
                <p class="text-xs text-amber-900">
                    Por razones de seguridad, la contraseña no se muestra ni modifica en este formulario.
                </p>
                <a href="/password" class="px-3 py-1.5 bg-unsxx-blue-900 hover:bg-unsxx-blue-800 text-white text-xs font-bold rounded-lg shrink-0">
                    Ir a Cambiar mi Contraseña
                </a>
            </div>
        <?php endif; ?>

        <!-- 3. Módulos Permitidos -->
        <div id="modulesContainer" class="pt-4 border-t border-gray-100 <?= $currentRoleId === 1 ? 'opacity-40 pointer-events-none' : '' ?>">
            <div class="flex justify-between items-center mb-3">
                <div>
                    <h3 class="text-sm font-bold text-unsxx-blue-900">Módulos Asignados</h3>
                    <p class="text-xs text-gray-500">Marca o desmarca los módulos permitidos para este usuario.</p>
                </div>
                <div class="flex gap-2 text-xs">
                    <button type="button" onclick="setAllModules(true)" class="text-unsxx-blue-600 hover:underline font-medium cursor-pointer">Marcar todos</button>
                    <span class="text-gray-300">|</span>
                    <button type="button" onclick="setAllModules(false)" class="text-gray-500 hover:underline cursor-pointer">Desmarcar todos</button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <?php foreach ($catalogModules as $key => $info): ?>
                    <?php $checked = in_array($key, $userMods, true) ? 'checked' : ''; ?>
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200 hover:border-unsxx-blue-300 hover:bg-blue-50/30 cursor-pointer transition-all">
                        <input type="checkbox" name="modulos[]" value="<?= $key ?>" <?= $checked ?>
                            class="module-checkbox mt-0.5 w-4 h-4 text-unsxx-blue-600 border-gray-300 rounded focus:ring-unsxx-blue-500">
                        <div>
                            <span class="block text-xs font-bold text-gray-800"><?= htmlspecialchars($info['label']) ?></span>
                            <span class="block text-[11px] text-gray-500 leading-tight mt-0.5"><?= htmlspecialchars($info['desc']) ?></span>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
            <a href="/users" class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 text-sm">Cancelar</a>
            <button type="submit" class="px-6 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm text-sm cursor-pointer">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

<script>
    function fillFromAcademicEdit(select) {
        const option = select.options[select.selectedIndex];
        const phone = option.getAttribute('data-phone');
        const celInput = document.getElementById('usu_cel');
        if (phone && !celInput.value.trim()) {
            celInput.value = phone;
        }
    }
    function toggleModulesPanel() {
        const rol = document.getElementById('rol_id').value;
        const container = document.getElementById('modulesContainer');
        if (rol === '1') {
            container.classList.add('opacity-40', 'pointer-events-none');
        } else {
            container.classList.remove('opacity-40', 'pointer-events-none');
        }
    }
    function setAllModules(state) {
        document.querySelectorAll('.module-checkbox').forEach(cb => cb.checked = state);
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>