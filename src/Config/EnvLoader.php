<?php

namespace App\Config;

class EnvLoader
{
    public static function load(string $path = __DIR__ . '/../../.env'): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '' || str_starts_with($trimmed, '#')) {
                continue;
            }
            if (strpos($trimmed, '=') === false) {
                continue;
            }

            [$key, $value] = array_map('trim', explode('=', $trimmed, 2));
            $value = trim($value, '"\'');
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}