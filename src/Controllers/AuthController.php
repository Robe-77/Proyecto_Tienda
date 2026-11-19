<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Empleado;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::isLoggedIn()) {
            $this->redirectByRole(Auth::userRole());
        }

        $this->render('auth/login', [
            'title' => 'Iniciar Sesión',
            'error' => '',
            'flash' => $this->getFlash(),
        ]);
    }

    public function login(): void
    {
        if (!$this->requireValidCsrf()) {
            $this->redirect('login');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $password = (string) ($_POST['contraseña'] ?? '');
        $error = '';

        if (!$id || $password === '') {
            $error = 'Debes ingresar tu ID y contraseña.';
        } else {
            $empleadoModel = new Empleado();
            $user = $empleadoModel->findById($id);

            if ($user === null) {
                $error = 'ID no encontrada.';
            } elseif (!password_verify($password, $user['Contraseña'])) {
                $error = 'Contraseña incorrecta.';
            } else {
                $rol = $user['Rol'];
                if (!in_array($rol, ['Administrador', 'Cajero'], true)) {
                    $error = 'Rol de usuario no válido.';
                } else {
                    Auth::login((int) $user['Id_Empleado'], $user['Nombre'], $rol);
                    $this->redirectByRole($rol);
                }
            }
        }

        $this->render('auth/login', [
            'title' => 'Iniciar Sesión',
            'error' => $error,
            'flash' => $this->getFlash(),
        ]);
    }

    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$this->requireValidCsrf()) {
            $this->redirect('login');
        }

        Auth::logout();
        $this->redirect('login');
    }

    private function redirectByRole(?string $rol): never
    {
        if ($rol === 'Administrador') {
            $this->redirect('admin');
        }
        if ($rol === 'Cajero') {
            $this->redirect('cajero');
        }
        $this->redirect('login');
    }
}
