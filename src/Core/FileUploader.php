<?php

namespace App\Core;

class FileUploader
{
    public static function uploadImage(array $file): ?string
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($mime, $allowedMimes, true)) {
            return null;
        }

        $extension = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg'
        };

        $filename = uniqid('img_') . '.' . $extension;
        $destinationDir = __DIR__ . '/../../public/uploads/img/';

        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $destinationPath = $destinationDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destinationPath)) {
            return $filename;
        }

        return null;
    }

    public static function uploadPdf(array $file, string $customFolder = ''): ?string
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if ($mime !== 'application/pdf') {
            return null;
        }

        $filename = uniqid('doc_') . '.pdf';
        
        // Concatena dentro de pdf/ (ej. si pasas 'documents', será uploads/pdf/documents/)
        $subFolder = !empty($customFolder) ? rtrim($customFolder, '/') . '/' : '';
        $destinationDir = __DIR__ . '/../../public/uploads/pdf/' . $subFolder;

        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $destinationPath = $destinationDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destinationPath)) {
            return $filename;
        }

        return null;
    }

    public static function delete(string $filename, string $folder = 'img'): bool
    {
        $baseDir = __DIR__ . '/../../public/uploads/';
        
        // Manejo estricto de carpetas legacy y nuevas rutas relativas
        if ($folder === 'img') {
            $subDir = 'img/';
        } elseif ($folder === 'pdf') {
            $subDir = 'pdf/';
        } else {
            // Permite pasar rutas compuestas desde el controlador (ej: 'pdf/documents')
            $subDir = rtrim($folder, '/') . '/';
        }

        $filepath = $baseDir . $subDir . $filename;

        if (file_exists($filepath)) {
            return unlink($filepath);
        }

        return false;
    }
}