<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('VIEWS_PATH', BASE_PATH . '/views');

/**
 * Load key=value pairs from .env file into $_ENV if present.
 */
function loadEnv(string $path): void
{
    if (!is_file($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || str_starts_with($trimmed, '#') || !str_contains($trimmed, '=')) {
            continue;
        }

        [$name, $value] = explode('=', $trimmed, 2);
        $name = trim($name);
        $value = trim($value);
        $value = trim($value, "\"'");

        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
    }
}

loadEnv(BASE_PATH . '/.env');

/**
 * Retrieve an environment variable with a default fallback.
 */
function env(string $key, ?string $default = null): ?string
{
    return $_ENV[$key] ?? $default;
}

/**
 * Escape HTML special characters safely.
 */
function e(mixed $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Compute the application base path (handles XAMPP subdirectory + public/ rewrite).
 */
function appBasePath(): string
{
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $scriptDir = rtrim(dirname($scriptName), '/');

    if (str_ends_with($scriptDir, '/public')) {
        $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        if (!str_starts_with($requestUri, $scriptDir)) {
            $scriptDir = substr($scriptDir, 0, -strlen('/public'));
        }
    }

    return $scriptDir === '/' ? '' : $scriptDir;
}

/**
 * Compute the full URL path for assets and links.
 */
function baseUrl(string $path = ''): string
{
    $base = appBasePath();
    if ($path === '') {
        return $base === '' ? '/' : $base;
    }
    return $base . '/' . ltrim($path, '/');
}
