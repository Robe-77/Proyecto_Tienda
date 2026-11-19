<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Movimiento;
use Throwable;

class VentaController extends Controller
{
    public function showRegistrar(): void
    {
        Auth::requireRole('Cajero');

        $this->render('ventas/registrar', [
            'title' => 'Registrar Venta',
            'flash' => $this->getFlash(),
            'scripts' => ['assets/js/venta.js'],
        ]);
    }

    public function registrar(): void
    {
        Auth::requireRole('Cajero');
        if (!$this->requireValidCsrf()) {
            $this->redirect('ventas/registrar');
        }

        $productos = $_POST['productos'] ?? [];
        $cantidades = $_POST['cantidades'] ?? [];

        if (!is_array($productos) || !is_array($cantidades) || count($productos) !== count($cantidades)) {
            $this->setFlash('error', 'Datos de venta inválidos.');
            $this->redirect('ventas/registrar');
        }

        $items = [];
        foreach ($productos as $index => $codigo) {
            $codigoLimpio = trim((string) $codigo);
            $cantidad = filter_var($cantidades[$index] ?? 0, FILTER_VALIDATE_INT);
            if ($codigoLimpio !== '' && $cantidad !== false && $cantidad > 0) {
                $items[] = [
                    'codigo' => $codigoLimpio,
                    'cantidad' => $cantidad,
                ];
            }
        }

        try {
            $model = new Movimiento();
            $resultado = $model->registrarVenta((int) Auth::userId(), $items);

            $_SESSION['id_salida'] = $resultado['id_salida'];
            $_SESSION['total_venta'] = $resultado['total'];

            $this->redirect('factura');
        } catch (Throwable $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('ventas/registrar');
        }
    }
}
