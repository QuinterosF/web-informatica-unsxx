<?php
$title = 'Nuevo Usuario - Informática UNSXX';
$pageTitle = 'Registrar Nuevo Usuario del Sistema';

$catalogModules = [
    'institucion' => ['label' => 'Identidad y Textos', 'desc' => 'Misión, visión, historia y mensaje del director'],
    'portadas' => ['label' => 'Fotos Institucionales', 'desc' => 'Carrusel de imágenes de portada'],
    'redes' => ['label' => 'Directorio y Redes', 'desc' => 'Teléfonos, WhatsApp y enlaces de servicio'],
    'academics' => ['label' => 'Plantel Docente y Adm.', 'desc' => 'Registro de docentes, coordinadores y personal'],
    'curricular' => ['label' => 'Menciones e Institutos', 'desc' => 'Áreas curriculares e investigación'],
    'laboratories' => ['label' => 'Laboratorios (Global)', 'desc' => 'Control total de todos los laboratorios'],
    'admissions' => ['label' => 'Admisión', 'desc' => 'Procesos de prueba de suficiencia y preuniversitario'],
    'communications' => ['label' => 'Comunicados Oficiales', 'desc' => 'Publicación de avisos y comunicados'],
    'documents' => ['label' => 'Documentos Oficiales', 'desc' => 'Reglamentos, horarios generales y planes PDF'],
    'extensions' => ['label' => 'Extensión y Eventos', 'desc' => 'Noticias de eventos, ICPC, FLISoL y galerías'],
    'logros' => ['label' => 'Logros de la Carrera', 'desc' => 'Salón de la fama y premios históricos'],
];

$defaultPass = 'Infor' . date('Y') . '*';
ob_start();
?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-xl font-bold text-unsxx-blue-900">Configurar Cuenta y Permisos por Módulo</h2>
        <p class="text-xs text-gray-500 mt-0.5">Puedes vincular la cuenta a un miembro del plantel para autocompletar su
            nombre y correo.</p>
    </div>
    <a href="/users" class="inline-flex items-center gap-2 text-unsxx-blue-600 hover:text-unsxx-blue-800 font-medium">
        ← Volver a Usuarios
    </a>
</div>

<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden p-6 sm:p-8">

    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded text-sm">
            <?= $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="/users" method="POST" class="space-y-6">

        <!-- 1. Vínculo Opcional con Plantel Docente / Administrativo -->
        <div class="p-4 bg-blue-50/60 border border-blue-200 rounded-xl">
            <label for="id_acad" class="block text-sm font-bold text-unsxx-blue-900 mb-1">
                1. ¿Pertenece al Plantel Docente o Administrativo? <span
                    class="text-xs font-normal text-gray-500">(Opcional pero recomendado)</span>
            </label>
            <select id="id_acad" name="id_acad" onchange="fillFromAcademic(this)"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none bg-white text-sm">
                <option value="" data-name="" data-email="">-- No vincular / Crear cuenta independiente --</option>
                <?php if (!empty($academics)): ?>
                    <?php foreach ($academics as $acad): ?>
                        <option value="<?= (int) $acad['id_acad'] ?>" data-name="<?= htmlspecialchars($acad['nom_acad']) ?>"
                            data-email="<?= htmlspecialchars($acad['email_acad']) ?>">
                            <?= htmlspecialchars($acad['nom_acad']) ?> (<?= htmlspecialchars($acad['email_acad']) ?>)
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <p class="text-xs text-blue-700 mt-1.5">
                <svg class="w-4 h-4 text-blue-600 inline-block mr-1 -mt-0.5 shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Si seleccionas a un docente que es <strong>Responsable de Laboratorio</strong>, el sistema le dará
                acceso automático a su laboratorio sin necesidad de darle acceso global a todos los laboratorios.
            </p>
        </div>

        <!-- 2. Datos de Identificación y Contraseña por Defecto -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="usu_nom" class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo del Usuario
                    <span class="text-red-500">*</span></label>
                <input type="text" id="usu_nom" name="usu_nom" required placeholder="Ej: Lic. María García"
                    value="<?= htmlspecialchars($old['usu_nom'] ?? '') ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none text-sm">
            </div>

            <div>
                <label for="usu_correo" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico (Login)
                    <span class="text-red-500">*</span></label>
                <input type="email" id="usu_correo" name="usu_correo" required placeholder="ejemplo@unsxx.edu.bo"
                    value="<?= htmlspecialchars($old['usu_correo'] ?? '') ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none text-sm">
            </div>

            <div>
                <label for="usu_pass" class="block text-sm font-medium text-gray-700 mb-1">
                    Contraseña Inicial Temporal <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-2">
                    <input type="text" id="usu_pass" name="usu_pass" required
                        value="<?= htmlspecialchars($defaultPass) ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none text-sm font-mono bg-amber-50/40">

                    <button type="button" onclick="generateRandomPass()" title="Generar otra clave aleatoria"
                        class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 rounded-lg text-xs font-bold shrink-0 cursor-pointer">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-unsxx-blue-700 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Generar
                        </span>
                    </button>
                    <button type="button" onclick="copyCreateCredentials()"
                        title="Copiar credenciales actuales para WhatsApp"
                        class="px-3 py-2 bg-green-50 hover:bg-green-100 text-green-900 border border-green-200 rounded-lg text-xs font-bold shrink-0 cursor-pointer">
                        Copiar para WhatsApp
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Viene prellenada por defecto. Recomienda al usuario cambiarla al
                    ingresar.</p>
            </div>

            <div>
                <label for="rol_id" class="block text-sm font-medium text-gray-700 mb-1">Nivel de Rol en el Sistema
                    <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-unsxx-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <select id="rol_id" name="rol_id" onchange="toggleModulesPanel()"
                        class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none bg-white text-sm">
                        <option value="2" selected>Operador / Docente / Secretaria (Acceso por Módulos)</option>
                        <option value="1">Administrador General (Acceso Total)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 3. Selección de Módulos Permitidos -->
        <div id="modulesContainer" class="pt-4 border-t border-gray-100">
            <div class="flex justify-between items-center mb-3">
                <div>
                    <h3 class="text-sm font-bold text-unsxx-blue-900">Módulos Asignados</h3>
                    <p class="text-xs text-gray-500">Marca únicamente los módulos que este usuario podrá gestionar.</p>
                </div>
                <div class="flex gap-2 text-xs">
                    <button type="button" onclick="setAllModules(true)"
                        class="text-unsxx-blue-600 hover:underline font-medium cursor-pointer">Marcar todos</button>
                    <span class="text-gray-300">|</span>
                    <button type="button" onclick="setAllModules(false)"
                        class="text-gray-500 hover:underline cursor-pointer">Desmarcar todos</button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <?php foreach ($catalogModules as $key => $info): ?>
                    <label
                        class="flex items-start gap-3 p-3 rounded-xl border border-gray-200 hover:border-unsxx-blue-300 hover:bg-blue-50/30 cursor-pointer transition-all">
                        <input type="checkbox" name="modulos[]" value="<?= $key ?>"
                            class="module-checkbox mt-0.5 w-4 h-4 text-unsxx-blue-600 border-gray-300 rounded focus:ring-unsxx-blue-500">
                        <div>
                            <span class="block text-xs font-bold text-gray-800">
                                <?= htmlspecialchars($info['label']) ?></span>
                            <span class="block text-[11px] text-gray-500 leading-tight mt-0.5">
                                <?= htmlspecialchars($info['desc']) ?>
                            </span>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
            <a href="/users"
                class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 text-sm">Cancelar</a>
            <button type="submit"
                class="px-6 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm text-sm cursor-pointer">
                Crear Usuario
            </button>
        </div>
    </form>
</div>

<script>
    function fillFromAcademic(select) {
        const option = select.options[select.selectedIndex];
        const name = option.getAttribute('data-name');
        const email = option.getAttribute('data-email');
        if (name) document.getElementById('usu_nom').value = name;
        if (email) document.getElementById('usu_correo').value = email;
    }

    function generateRandomPass() {
        const num = Math.floor(1000 + Math.random() * 9000);
        document.getElementById('usu_pass').value = 'Infor-' + num + '*';
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
        document.querySelectorAll('.module - checkbox ').forEach(cb => cb.checked = state);
    }

    function copyCreateCredentials() {
        const email = document.getElementById('usu_correo').value.trim();
        const pass = document.getElementById('usu_pass').value.trim();
        if (!email) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Primero ingresa o selecciona un correo', showConfirmButton: false, timer: 2000 });
            return;
        }
        const text = `*CREDENCIALES DE ACCESO AL SITIO WEB DE ING. INFORMÁTICA - UNSXX*\n\n*Correo:* ${email}\n*Contraseña temporal:* ${pass}\n\n_(Se recomienda cambiar la contraseña al ingresar)_`;
        navigator.clipboard.writeText(text);
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Copiado al portapapeles', showConfirmButton: false, timer: 2000 });
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>