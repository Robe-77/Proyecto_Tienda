<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Producto;
use Throwable;

class ProductoController extends Controller
{
    public function showRegistrar(): void
    {
        Auth::requireRole('Administrador');

        $this->render('productos/registrar', [
            'title' => 'Registrar Producto',
            'flash' => $this->getFlash(),
        ]);
    }

    public function registrar(): void
    {
        Auth::requireRole('Administrador');
        if (!$this->requireValidCsrf()) {
            $this->redirect('productos/registrar');
        }

        $codigo = trim((string) ($_POST['codigo_producto'] ?? ''));
        $nombre = trim((string) ($_POST['nombre_comercial'] ?? ''));
        $precio = filter_input(INPUT_POST, 'precio_publico', FILTER_VALIDATE_FLOAT);
        $existencia = filter_input(INPUT_POST, 'existencia', FILTER_VALIDATE_INT);

        if ($codigo === '' || $nombre === '' || $precio === false || $existencia === false || $precio < 0 || $existencia < 0) {
            $this->setFlash('error', 'Datos de producto inválidos.');
            $this->redirect('productos/registrar');
        }

        try {
            $model = new Producto();
            if ($model->create($codigo, $nombre, (float) $precio, (int) $existencia)) {
                $this->setFlash('exito', 'Producto registrado con éxito.');
                $this->redirect('admin');
            }
            $this->setFlash('error', 'Error al registrar producto.');
        } catch (Throwable $e) {
            $this->setFlash('error', 'Error al registrar producto: ' . $e->getMessage());
        }

        $this->redirect('productos/registrar');
    }

    public function showModificar(): void
    {
        Auth::requireRole('Administrador');

        $this->render('productos/modificar', [
            'title' => 'Modificar Producto',
            'flash' => $this->getFlash(),
        ]);
    }

    public function modificar(): void
    {
        Auth::requireRole('Administrador');
        if (!$this->requireValidCsrf()) {
            $this->redirect('productos/modificar');
        }

        $codigo = trim((string) ($_POST['codigo_producto'] ?? ''));
        $nombre = trim((string) ($_POST['nombre_comercial'] ?? ''));
        $precioRaw = trim((string) ($_POST['precio_publico'] ?? ''));
        $precio = $precioRaw !== '' ? filter_var($precioRaw, FILTER_VALIDATE_FLOAT) : null;

        if ($codigo === '' || ($precioRaw !== '' && ($precio === false || $precio < 0))) {
            $this->setFlash('error', 'Datos inválidos para modificar el producto.');
            $this->redirect('productos/modificar');
        }

        try {
            $model = new Producto();
            if ($model->findByCodigo($codigo) === null) {
                $this->setFlash('error', 'Error: Producto no encontrado.');
                $this->redirect('productos/modificar');
            }

            if ($model->update($codigo, $nombre, $precio === null ? null : (float) $precio)) {
                $this->setFlash('exito', 'Producto modificado con éxito.');
                $this->redirect('admin');
            }
            $this->setFlash('error', 'Error al modificar producto.');
        } catch (Throwable $e) {
            $this->setFlash('error', 'Error al modificar producto: ' . $e->getMessage());
        }

        $this->redirect('productos/modificar');
    }

    public function showEliminar(): void
    {
        Auth::requireRole('Administrador');

        $this->render('productos/eliminar', [
            'title' => 'Eliminar Producto',
            'flash' => $this->getFlash(),
            'productoConfirmar' => null,
        ]);
    }

    public function eliminar(): void
    {
        Auth::requireRole('Administrador');
        if (!$this->requireValidCsrf()) {
            $this->redirect('productos/eliminar');
        }

        $codigo = trim((string) ($_POST['codigo_producto'] ?? ''));
        if ($codigo === '') {
            $this->setFlash('error', 'Código de producto inválido.');
            $this->redirect('productos/eliminar');
        }

        $model = new Producto();

        if (isset($_POST['verificar'])) {
            $producto = $model->findByCodigo($codigo);
            if ($producto === null) {
                $this->setFlash('error', 'Error: Producto no encontrado.');
                $this->redirect('productos/eliminar');
            }

            if ($model->isInUse($codigo)) {
                $this->setFlash(
                    'error',
                    'No se puede eliminar el producto porque está relacionado con registros de salida o entrada de productos.'
                );
                $this->redirect('productos/eliminar');
            }

            $this->render('productos/eliminar', [
                'title' => 'Eliminar Producto',
                'flash' => null,
                'productoConfirmar' => $producto,
            ]);
            return;
        }

        if (isset($_POST['confirmar']) && $_POST['confirmar'] === 'true') {
            $producto = $model->findByCodigo($codigo);
            if ($producto === null || $model->isInUse($codigo)) {
                $this->setFlash('error', 'No es posible eliminar este producto.');
                $this->redirect('productos/eliminar');
            }

            if ($model->delete($codigo)) {
                $this->setFlash('exito', 'Producto eliminado correctamente.');
                $this->redirect('admin');
            }

            $this->setFlash('error', 'Error al eliminar el producto.');
        }

        $this->redirect('productos/eliminar');
    }
}
