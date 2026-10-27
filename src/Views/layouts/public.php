<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Inicio') ?> - Informática UNSXX</title>
    
    <!-- Favicon institucional -->
    <link rel="icon" type="image/png" href="/assets/img/logo-informatica.png">

    <!-- Google Fonts: Inter (UI), Figtree (Headlines), IBM Plex Mono (Code) y Dancing Script (Cursiva) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@500;700&family=Figtree:wght@500;600;700;800&family=IBM+Plex+Mono:wght@400;500;600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Configuración Custom Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        institucional: {
                            navy: '#1e3a8a',
                            'navy-dark': '#172554',
                            gold: '#eab308',
                            light: '#f1f5f9',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Figtree', 'sans-serif'],
                        mono: ['"IBM Plex Mono"', 'monospace'],
                        cursive: ['"Dancing Script"', 'cursive'],
                    }
                }
            }
        }
    </script>

    <!-- Hoja de Estilos Modular (Aurora Glows, Superficies y Sombras) -->
    <link rel="stylesheet" href="/assets/css/public-theme.css">
</head>
<body class="aurora-light-canvas tech-grid-light text-slate-700 font-sans antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <?php require_once __DIR__ . '/../components/public/navbar.php'; ?>

    <!-- Contenido Dinámico Principal -->
    <main class="flex-grow pt-20">
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <?php require_once __DIR__ . '/../components/public/footer.php'; ?>

    <!-- Script modular público -->
    <script src="/assets/js/public-main.js" defer></script>
</body>
</html>