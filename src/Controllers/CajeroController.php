<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;

class CajeroController extends Controller
{
    public function index(): void
    {
        Auth::requireRole('Cajero');

        $this->render('cajero/dashboard', [
            'title' => 'Panel de Cajeros',
            'nombre' => Auth::userName(),
            'flash' => $this->getFlash(),
        ]);
    }
}
