<?php
$title = 'Cambiar Contraseña - Informática UNSXX';
$pageTitle = 'Seguridad de la Cuenta';
ob_start();
?>

<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-unsxx-blue-100 overflow-hidden">
        <div class="px-6 py-4 bg-unsxx-blue-900 text-white flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold">Actualizar Contraseña de Acceso</h2>
                <p class="text-xs text-blue-200 mt-0.5">Cuenta activa: <?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></p>
            </div>
            <span class="text-2xl">🔒</span>
        </div>

        <div class="p-6 sm:p-8">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded text-sm">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="/password" method="POST" class="space-y-5">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                        Contraseña Actual <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="current_password" name="current_password" required
                        placeholder="Ingresa tu contraseña actual"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none text-sm">
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
                        Nueva Contraseña <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="new_password" name="new_password" required minlength="6"
                        placeholder="Mínimo 6 caracteres"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none text-sm">
                </div>

                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">
                        Confirmar Nueva Contraseña <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="6"
                        placeholder="Repite la nueva contraseña"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-unsxx-blue-500 outline-none text-sm">
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="/dashboard" class="px-5 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 text-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-lg shadow-sm text-sm cursor-pointer">
                        Actualizar Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>