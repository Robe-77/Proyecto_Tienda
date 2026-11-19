<?php

declare(strict_types=1);

namespace App\Core;

class Auth
{
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    public static function isLoggedIn(): bool
    {
        self::startSession();
        return !empty($_SESSION['loggedin']) && !empty($_SESSION['id']);
    }

    public static function userId(): ?int
    {
        self::startSession();
        return isset($_SESSION['id']) ? (int) $_SESSION['id'] : null;
    }

    public static function userName(): string
    {
        self::startSession();
        return (string) ($_SESSION['nombre'] ?? 'Usuario');
    }

    public static function userRole(): ?string
    {
        self::startSession();
        return $_SESSION['rol'] ?? null;
    }

    public static function login(int $id, string $nombre, string $rol): void
    {
        self::startSession();
        session_regenerate_id(true);
        $_SESSION['id'] = $id;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['rol'] = $rol;
        $_SESSION['loggedin'] = true;
    }

    public static function logout(): void
    {
        self::startSession();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
    }

    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . baseUrl('login'));
            exit();
        }
    }

    public static function requireRole(string ...$roles): void
    {
        self::requireLogin();
        $currentRole = self::userRole();
        if ($currentRole === null || !in_array($currentRole, $roles, true)) {
            $fallback = $currentRole === 'Administrador' ? 'admin' : ($currentRole === 'Cajero' ? 'cajero' : 'login');
            header('Location: ' . baseUrl($fallback));
            exit();
        }
    }

    public static function csrfToken(): string
    {
        self::startSession();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrf(): bool
    {
        self::startSession();
        $token = $_POST['csrf_token'] ?? '';
        return is_string($token)
            && isset($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $token);
    }
}
