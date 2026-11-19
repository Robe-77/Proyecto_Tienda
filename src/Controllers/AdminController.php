<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;

class AdminController extends Controller
{
    public function index(): void
    {
        Auth::requireRole('Administrador');

        $this->render('admin/dashboard', [
            'title' => 'Panel de Administración',
            'nombre' => Auth::userName(),
            'flash' => $this->getFlash(),
        ]);
    }
}
