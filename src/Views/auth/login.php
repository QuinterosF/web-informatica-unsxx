<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ingeniería Informática UNSXX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'unsxx-blue': {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#172554',
                        },
                        'unsxx-yellow': {
                            50: '#fefce8',
                            100: '#fef9c3',
                            200: '#fef08a',
                            300: '#fde047',
                            400: '#facc15',
                            500: '#eab308',
                            600: '#ca8a04',
                            700: '#a16207',
                            800: '#854d0e',
                            900: '#713f12',
                        },
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-unsxx-blue-950 via-unsxx-blue-900 to-unsxx-blue-800 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl border border-unsxx-yellow-300/30 p-8 relative overflow-hidden">
        <!-- Decorative top accent bar -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-unsxx-yellow-400 via-unsxx-yellow-300 to-unsxx-yellow-400"></div>

        <!-- Logo/Brand -->
        <div class="text-center mb-8 relative z-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-gradient-to-br from-unsxx-blue-800 to-unsxx-blue-600 mb-4 shadow-lg shadow-unsxx-blue-900/30">
                <svg class="w-8 h-8 text-unsxx-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-unsxx-blue-900 tracking-tight">Ingeniería Informática</h1>
            <p class="text-unsxx-blue-600 mt-1 text-sm">Universidad Nacional "Siglo XX"</p>
        </div>

        <!-- Confirmación de Solicitud de Restauración de Contraseña -->
        <?php if (isset($_GET['reset_requested'])): ?>
            <?php
            $reqEmail = $_SESSION['reset_requested_email'] ?? '';
            unset($_SESSION['reset_requested_email']);
            $cleanPhone = preg_replace('/\D+/', '', $contactPhone ?? '');
            if (strlen($cleanPhone) === 8) {
                $cleanPhone = '591' . $cleanPhone;
            }
            $waText = urlencode("Hola, solicité en el sistema de Informática - UNSXX la restauración de mi contraseña para la cuenta: " . $reqEmail);
            $waUrl = !empty($cleanPhone)
                ? "https://wa.me/{$cleanPhone}?text={$waText}"
                : "https://wa.me/?text={$waText}";
            ?>
            <div class="mb-6 p-4 rounded-xl border-l-4 bg-green-50 border-green-500 text-green-800 animate-slide-down relative z-10" role="status">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-xs leading-relaxed">
                        <p class="font-bold text-sm text-green-900">Solicitud registrada en el sistema</p>
                        <p class="mt-1 text-green-700">
                            Se envió una notificación al Panel del Administrador para que genere tu nueva contraseña temporal.
                        </p>
                        <div class="mt-3 pt-2.5 border-t border-green-200/80 flex flex-col gap-1.5">
                            <span class="text-[11px] text-green-700 font-medium">Opcional: Si deseas atención inmediata, puedes avisar por WhatsApp:</span>
                            <a href="<?= htmlspecialchars($waUrl) ?>" target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold text-xs transition-colors shadow-sm w-full">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824z"/>
                                </svg>
                                Contactar al administrador por WhatsApp (Opcional)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Error Messages -->
        <?php if (isset($error)): ?>
            <div class="mb-6 p-4 rounded-xl border-l-4 bg-red-50 border-red-400 text-red-700 animate-slide-down relative z-10" role="alert">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm font-medium">
                        <?php
                            $msg = match($error) {
                                'empty'    => 'Complete todos los campos.',
                                'invalid'  => 'Credenciales inválidas.',
                                'disabled' => 'Tu cuenta está desactivada.',
                                'expired'  => 'Su sesión ha expirado. Inicie sesión nuevamente.',
                                default    => 'Error desconocido.'
                            };
                            echo htmlspecialchars($msg);
                        ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['logout'])): ?>
            <div class="mb-6 p-4 rounded-xl border-l-4 bg-unsxx-yellow-50 border-unsxx-yellow-400 text-unsxx-yellow-800 animate-slide-down relative z-10" role="status">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm font-medium">Sesión cerrada correctamente.</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="/login" class="space-y-5 relative z-10" novalidate>
            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-semibold text-unsxx-blue-800 mb-1.5">Correo electrónico</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-unsxx-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        autocomplete="email"
                        placeholder="usuario@unsxx.edu.bo"
                        class="w-full pl-10 pr-4 py-3 border border-unsxx-blue-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-unsxx-yellow-400 focus:border-unsxx-yellow-400 transition-all duration-200 placeholder:text-unsxx-blue-300"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    >
                </div>
            </div>

            <!-- Password Field -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-sm font-semibold text-unsxx-blue-800">Contraseña</label>
                    <button type="button" onclick="openForgotModal()"
                        class="text-xs font-semibold text-unsxx-blue-600 hover:text-unsxx-blue-900 hover:underline inline-flex items-center gap-1 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        ¿Olvidaste tu contraseña?
                    </button>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-unsxx-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full pl-10 pr-4 py-3 border border-unsxx-blue-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-unsxx-yellow-400 focus:border-unsxx-yellow-400 transition-all duration-200 placeholder:text-unsxx-blue-300"
                    >
                </div>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full py-3.5 bg-gradient-to-r from-unsxx-blue-700 via-unsxx-blue-600 to-unsxx-blue-700 text-white font-semibold rounded-xl hover:from-unsxx-blue-800 hover:via-unsxx-blue-700 hover:to-unsxx-blue-800 focus:outline-none focus:ring-2 focus:ring-unsxx-yellow-300 focus:ring-offset-2 focus:ring-offset-unsxx-blue-900 transition-all duration-300 transform hover:scale-[1.01] active:scale-[0.99] shadow-lg shadow-unsxx-blue-900/30"
            >
                Iniciar sesión
            </button>
        </form>

        <!-- Footer note -->
        <div class="mt-8 text-center relative z-10">
            <p class="text-xs text-unsxx-blue-500">Sistema de Gestión Académica &copy; <?= date('Y') ?></p>
        </div>
    </div>

    <!-- Modal de Solicitud de Restauración de Contraseña -->
    <div id="forgotModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-unsxx-blue-950/75 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-unsxx-blue-100 w-full max-w-md overflow-hidden animate-slide-down">
            <div class="px-6 py-4 bg-unsxx-blue-900 text-white flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-unsxx-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    <h3 class="font-bold text-base">Solicitar Restauración de Clave</h3>
                </div>
                <button type="button" onclick="closeForgotModal()" class="text-blue-200 hover:text-white cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="/forgot-password" class="p-6 space-y-4">
                <p class="text-xs text-gray-600 leading-relaxed">
                    Ingresa el correo electrónico de tu cuenta. El sistema notificará al <strong>Administrador General</strong> para que genere tu nueva contraseña temporal.
                </p>

                <div>
                    <label for="reset_email" class="block text-xs font-bold uppercase tracking-wide text-unsxx-blue-800 mb-1.5">
                        Correo registrado en el sistema
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-unsxx-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="email" id="reset_email" name="reset_email" required
                            placeholder="tucorreo@ejemplo.com"
                            class="w-full pl-9 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-unsxx-blue-500">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeForgotModal()"
                        class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 text-xs cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 px-5 py-2 bg-unsxx-yellow-400 hover:bg-unsxx-yellow-500 text-unsxx-blue-900 font-bold rounded-xl shadow-sm text-xs cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Registrar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openForgotModal() {
            const loginEmail = document.getElementById('email').value.trim();
            if (loginEmail) {
                document.getElementById('reset_email').value = loginEmail;
            }
            const modal = document.getElementById('forgotModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeForgotModal() {
            const modal = document.getElementById('forgotModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

    <!-- Custom animations -->
    <style>
        @keyframes slide-down {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-down {
            animation: slide-down 0.3s ease-out forwards;
        }
    </style>
</body>
</html>