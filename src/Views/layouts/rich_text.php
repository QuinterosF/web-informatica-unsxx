<?php
/**
 * Helper Global de Texto Enriquecido (Quill.js Sanitizer)
 * Disponible desde la línea 1 para todas las vistas públicas y el layout.
 */
if (!function_exists('renderRichText')) {
    function renderRichText(?string $content): string
    {
        if ($content === null || trim($content) === '') {
            return '';
        }
        $trimmed = trim($content);

        // Si es texto plano sin etiquetas HTML, escapar y conservar saltos de línea
        if ($trimmed === strip_tags($trimmed)) {
            return nl2br(htmlspecialchars($trimmed, ENT_QUOTES, 'UTF-8'));
        }

        // Lista blanca de etiquetas permitidas de Quill.js
        $allowedTags = '<p><br><strong><b><em><i><u><s><ul><ol><li><h3><h4><span><blockquote><a>';
        $clean = strip_tags($trimmed, $allowedTags);

        // Bloquear atributos de eventos JS (on*) o javascript: en enlaces
        $clean = preg_replace('/\son[a-z]+\s*=\s*(["\']).*?\1/i', '', $clean);
        $clean = preg_replace('/\son[a-z]+\s*=\s*[^\s>]+/i', '', $clean);
        $clean = preg_replace('/href\s*=\s*(["\'])\s*javascript:.*?\1/i', 'href="#"', $clean);

        // Permitir en style únicamente color y background-color seguros de la paleta Quill
        $clean = preg_replace_callback('/style\s*=\s*(["\'])(.*?)\1/i', function ($matches) {
            $styles = explode(';', $matches[2]);
            $safeStyles = [];
            foreach ($styles as $st) {
                if (preg_match('/^\s*(color|background-color)\s*:\s*(#[0-9a-fA-F]{3,6}|rgb\([0-9,\s]+\))\s*$/i', trim($st), $m)) {
                    $safeStyles[] = trim($m[1]) . ': ' . trim($m[2]);
                }
            }
            return !empty($safeStyles) ? 'style="' . implode('; ', $safeStyles) . '"' : '';
        }, $clean);

        return '<div class="rich-content">' . $clean . '</div>';
    }
}