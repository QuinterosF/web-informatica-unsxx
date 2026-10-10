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
                                'empty'   => 'Complete todos los campos.',
                                'invalid' => 'Credenciales inválidas.',
                                'expired' => 'Su sesión ha expirado. Inicie sesión nuevamente.',
                                default   => 'Error desconocido.'
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
                <label for="password" class="block text-sm font-semibold text-unsxx-blue-800 mb-1.5">Contraseña</label>
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