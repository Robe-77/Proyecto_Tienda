<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    /**
     * Render a view inside the shared layout.
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $viewFile = VIEWS_PATH . '/' . $view . '.php';
        if (!is_file($viewFile)) {
            http_response_code(500);
            echo 'Vista no encontrada: ' . e($view);
            return;
        }

        require VIEWS_PATH . '/layouts/header.php';
        require $viewFile;
        require VIEWS_PATH . '/layouts/footer.php';
    }

    protected function redirect(string $path): never
    {
        header('Location: ' . baseUrl($path));
        exit();
    }

    protected function setFlash(string $type, string $message): void
    {
        Auth::startSession();
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    protected function getFlash(): ?array
    {
        Auth::startSession();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    protected function requireValidCsrf(): bool
    {
        if (!Auth::verifyCsrf()) {
            $this->setFlash('error', 'Token de seguridad inválido. Intenta de nuevo.');
            return false;
        }
        return true;
    }
}
