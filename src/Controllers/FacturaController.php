<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Cliente;
use App\Models\Factura;
use Throwable;

class FacturaController extends Controller
{
    public function showSolicitar(): void
    {
        Auth::requireRole('Cajero');

        if (!isset($_SESSION['id_salida'])) {
            $this->redirect('cajero');
        }

        $this->render('facturas/solicitar', [
            'title' => 'Facturación',
            'idSalida' => (int) $_SESSION['id_salida'],
            'total' => (float) ($_SESSION['total_venta'] ?? 0),
            'flash' => $this->getFlash(),
            'scripts' => ['assets/js/factura.js'],
        ]);
    }

    public function procesar(): void
    {
        Auth::requireRole('Cajero');
        if (!$this->requireValidCsrf()) {
            $this->redirect('factura');
        }

        if (!isset($_SESSION['id_salida'])) {
            $this->redirect('cajero');
        }

        $idSalida = (int) $_SESSION['id_salida'];
        $requiereFactura = $_POST['requiere_factura'] ?? '0';

        if ($requiereFactura !== '1') {
            unset($_SESSION['id_salida'], $_SESSION['total_venta']);
            $this->setFlash('exito', 'Venta registrada sin factura.');
            $this->redirect('cajero');
        }

        $clienteModel = new Cliente();
        $facturaModel = new Factura();

        try {
            if (($_POST['cliente_registrado'] ?? '') === 'si') {
                $rfc = strtoupper(trim((string) ($_POST['rfc_existente'] ?? '')));
                if ($rfc === '' || !$clienteModel->existsByRfc($rfc)) {
                    $this->setFlash('error', 'El RFC no está registrado en el sistema.');
                    $this->redirect('factura');
                }
            } else {
                $datosCliente = [
                    'nombre' => trim((string) ($_POST['nombre'] ?? '')),
                    'rfc' => strtoupper(trim((string) ($_POST['rfc'] ?? ''))),
                    'apellido' => trim((string) ($_POST['apellido'] ?? '')),
                    'calle' => trim((string) ($_POST['calle'] ?? '')),
                    'numero' => trim((string) ($_POST['numero'] ?? '')),
                    'colonia' => trim((string) ($_POST['colonia'] ?? '')),
                    'ciudad' => trim((string) ($_POST['ciudad'] ?? '')),
                    'estado' => trim((string) ($_POST['estado'] ?? '')),
                    'codigo_postal' => trim((string) ($_POST['codigo_postal'] ?? '')),
                    'telefono' => trim((string) ($_POST['telefono'] ?? '')),
                    'correo' => trim((string) ($_POST['correo'] ?? '')),
                ];

                if ($datosCliente['rfc'] === '') {
                    $this->setFlash('error', 'El RFC es obligatorio para facturar.');
                    $this->redirect('factura');
                }

                $clienteModel->create($datosCliente);
                $rfc = $datosCliente['rfc'];
            }

            $facturaModel->create($rfc, $idSalida);

            unset($_SESSION['id_salida'], $_SESSION['total_venta']);
            $_SESSION['factura_data'] = [
                'id_salida' => $idSalida,
                'rfc' => $rfc,
            ];

            $this->setFlash('exito', 'Factura generada exitosamente.');
            $this->redirect('factura/generada');
        } catch (Throwable $e) {
            $this->setFlash('error', 'Error al generar la factura: ' . $e->getMessage());
            $this->redirect('factura');
        }
    }

    public function showGenerada(): void
    {
        Auth::requireRole('Cajero');

        if (!isset($_SESSION['factura_data'])) {
            $this->redirect('cajero');
        }

        $facturaData = $_SESSION['factura_data'];
        $idSalida = (int) $facturaData['id_salida'];
        $rfcCliente = (string) $facturaData['rfc'];

        $clienteModel = new Cliente();
        $facturaModel = new Factura();

        $cliente = $clienteModel->findByRfc($rfcCliente);
        $factura = $facturaModel->findBySalida($idSalida);
        $productos = $facturaModel->getSaleItems($idSalida);
        $total = $facturaModel->getSaleTotal($idSalida);

        unset($_SESSION['factura_data']);

        $this->render('facturas/generada', [
            'title' => 'Factura Generada',
            'idSalida' => $idSalida,
            'cliente' => $cliente ?? [],
            'factura' => $factura ?? [],
            'productos' => $productos,
            'total' => $total,
            'flash' => $this->getFlash(),
        ]);
    }
}
