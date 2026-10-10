<?php

namespace App\Core;

class Controller
{
    /**
     * Redirige a una URL específica y detiene la ejecución
     * 
     * @param string $url Ruta destino (ej. '/dashboard')
     */
    protected function redirect(string $url): void
    {
        header("Location: " . $url);
        exit;
    }

    /**
     * Alias para compatibilidad con controladores que usan $this->render()
     */
    protected function render(string $view, array $data = []): void
    {
        $this->view($view, $data);
    }

    /**
     * Renderiza una vista con sus datos
     * 
     * @param string $view Ruta de la vista (ej. 'auth/login')
     * @param array $data Datos a extraer hacia la vista
     */
    protected function view(string $view, array $data = []): void
    {
        // Extraer las variables para que estén disponibles en la vista
        extract($data);

        // __DIR__ es src/Core, subimos un nivel a src/ y entramos a Views/
        $viewFile = realpath(__DIR__ . '/../Views') . '/' . $view . '.php';

        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            // Error detallado para depuración rápida
            $buscadoEn = __DIR__ . '/../Views/' . $view . '.php';
            die("
                <div style='font-family: sans-serif; padding: 20px; border: 1px solid #dc2626; background: #fee2e2; color: #991b1b; border-radius: 5px; max-width: 600px; margin: 20px auto;'>
                    <h3 style='margin-top: 0;'>Error: Vista no encontrada</h3>
                    <p>El sistema intentó cargar la vista: <strong>{$view}</strong> pero el archivo no existe.</p>
                    <p>Ruta física buscada:<br> <code style='background: #fff; padding: 2px 4px;'>{$buscadoEn}</code></p>
                </div>
            ");
        }
    }
}