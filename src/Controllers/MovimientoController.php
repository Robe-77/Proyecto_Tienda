<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Movimiento;
use Throwable;

class MovimientoController extends Controller
{
    public function consultar(): void
    {
        Auth::requireRole('Administrador');

        $fechaFiltro = trim((string) ($_REQUEST['fecha'] ?? ''));
        $codigoFiltro = trim((string) ($_REQUEST['codigo'] ?? ''));

        $model = new Movimiento();
        $entradas = $model->getEntradas($fechaFiltro, $codigoFiltro);
        $salidas = $model->getSalidas($fechaFiltro, $codigoFiltro);

        $this->render('admin/movimientos', [
            'title' => 'Consultar Movimientos',
            'fechaFiltro' => $fechaFiltro,
            'codigoFiltro' => $codigoFiltro,
            'entradas' => $entradas,
            'salidas' => $salidas,
            'flash' => $this->getFlash(),
        ]);
    }

    public function showCompra(): void
    {
        Auth::requireRole('Cajero');

        $this->render('ventas/compra', [
            'title' => 'Registrar Compra',
            'flash' => $this->getFlash(),
        ]);
    }

    public function registrarCompra(): void
    {
        Auth::requireRole('Cajero');
        if (!$this->requireValidCsrf()) {
            $this->redirect('compras/registrar');
        }

        $idProveedor = trim((string) ($_POST['id_proveedor'] ?? ''));
        $codigoProducto = trim((string) ($_POST['codigo_producto'] ?? ''));
        $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);

        if ($idProveedor === '' || $codigoProducto === '' || !$cantidad || $cantidad <= 0) {
            $this->setFlash('error', 'Datos inválidos para registrar la compra.');
            $this->redirect('compras/registrar');
        }

        try {
            $model = new Movimiento();
            $model->registrarEntrada((int) Auth::userId(), $idProveedor, $codigoProducto, $cantidad, 'Compra');
            $this->setFlash('exito', 'Compra registrada con éxito.');
        } catch (Throwable $e) {
            $this->setFlash('error', 'Error al registrar la compra: ' . $e->getMessage());
        }

        $this->redirect('compras/registrar');
    }

    public function showDevolucion(): void
    {
        Auth::requireRole('Cajero');

        $this->render('ventas/devolucion', [
            'title' => 'Registrar Devolución',
            'flash' => $this->getFlash(),
        ]);
    }

    public function registrarDevolucion(): void
    {
        Auth::requireRole('Cajero');
        if (!$this->requireValidCsrf()) {
            $this->redirect('devoluciones/registrar');
        }

        $codigoProducto = trim((string) ($_POST['codigo_producto'] ?? ''));
        $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);

        if ($codigoProducto === '' || !$cantidad || $cantidad <= 0) {
            $this->setFlash('error', 'Datos inválidos para registrar la devolución.');
            $this->redirect('devoluciones/registrar');
        }

        try {
            $model = new Movimiento();
            $model->registrarEntrada((int) Auth::userId(), null, $codigoProducto, $cantidad, 'Devolución');
            $this->setFlash('exito', 'Devolución registrada con éxito.');
        } catch (Throwable $e) {
            $this->setFlash('error', 'Error al registrar la devolución: ' . $e->getMessage());
        }

        $this->redirect('devoluciones/registrar');
    }
}
