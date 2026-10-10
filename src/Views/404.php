<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'unsxx-blue': { 50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a', 950: '#172554' },
                        'unsxx-yellow': { 50: '#fefce8', 100: '#fef9c3', 200: '#fef08a', 300: '#fde047', 400: '#facc15', 500: '#eab308', 600: '#ca8a04', 700: '#a16207', 800: '#854d0e', 900: '#713f12', 950: '#713f12' },
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-unsxx-blue-950 via-unsxx-blue-900 to-unsxx-blue-800 flex items-center justify-center p-4">
    <div class="text-center">
        <div class="w-32 h-32 mx-auto mb-8 rounded-full bg-gradient-to-br from-unsxx-blue-800 to-unsxx-blue-600 flex items-center justify-center shadow-2xl">
            <span class="text-5xl font-bold text-unsxx-yellow-300">404</span>
        </div>
        <h1 class="text-3xl font-bold text-white mb-4">Página no encontrada</h1>
        <p class="text-unsxx-blue-300 mb-8 max-w-md mx-auto">Lo sentimos, la página que buscas no existe o ha sido movida.</p>
        <a href="/" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-unsxx-yellow-400 to-unsxx-yellow-300 text-unsxx-blue-950 font-semibold rounded-xl hover:from-unsxx-yellow-300 hover:to-unsxx-yellow-200 transition shadow-lg shadow-unsxx-yellow-400/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver al inicio
        </a>
    </div>
</body>
</html>