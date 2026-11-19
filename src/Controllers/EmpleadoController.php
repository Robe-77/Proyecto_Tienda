<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Empleado;
use Throwable;

class EmpleadoController extends Controller
{
    public function showRegistrar(): void
    {
        Auth::requireRole('Administrador');

        $this->render('empleados/registrar', [
            'title' => 'Registrar Empleado',
            'flash' => $this->getFlash(),
        ]);
    }

    public function registrar(): void
    {
        Auth::requireRole('Administrador');
        if (!$this->requireValidCsrf()) {
            $this->redirect('empleados/registrar');
        }

        $data = [
            'contraseña' => (string) ($_POST['contraseña'] ?? ''),
            'nombre' => trim((string) ($_POST['nombre'] ?? '')),
            'apellido' => trim((string) ($_POST['apellido'] ?? '')),
            'fecha_ingreso' => trim((string) ($_POST['fecha_ingreso'] ?? '')),
            'fecha_nacimiento' => trim((string) ($_POST['fecha_nacimiento'] ?? '')),
            'calle' => trim((string) ($_POST['calle'] ?? '')),
            'numero' => trim((string) ($_POST['numero'] ?? '')),
            'colonia' => trim((string) ($_POST['colonia'] ?? '')),
            'ciudad' => trim((string) ($_POST['ciudad'] ?? '')),
            'estado' => trim((string) ($_POST['estado'] ?? '')),
            'codigo_postal' => trim((string) ($_POST['codigo_postal'] ?? '')),
            'telefono' => trim((string) ($_POST['telefono'] ?? '')),
            'rol' => trim((string) ($_POST['rol'] ?? '')),
        ];

        try {
            $model = new Empleado();
            if ($model->create($data)) {
                $this->setFlash('exito', 'Empleado registrado con éxito.');
                $this->redirect('admin');
            }
            $this->setFlash('error', 'Error al registrar empleado.');
        } catch (Throwable $e) {
            $this->setFlash('error', 'Error al registrar empleado: ' . $e->getMessage());
        }

        $this->redirect('empleados/registrar');
    }

    public function showModificar(): void
    {
        Auth::requireRole('Administrador');

        $this->render('empleados/modificar', [
            'title' => 'Modificar Empleado',
            'flash' => $this->getFlash(),
        ]);
    }

    public function modificar(): void
    {
        Auth::requireRole('Administrador');
        if (!$this->requireValidCsrf()) {
            $this->redirect('empleados/modificar');
        }

        $id = filter_input(INPUT_POST, 'id_empleado', FILTER_VALIDATE_INT);
        if (!$id) {
            $this->setFlash('error', 'ID de empleado inválido.');
            $this->redirect('empleados/modificar');
        }

        $data = [
            'nombre' => trim((string) ($_POST['nombre'] ?? '')),
            'apellido' => trim((string) ($_POST['apellido'] ?? '')),
            'fecha_ingreso' => trim((string) ($_POST['fecha_ingreso'] ?? '')),
            'fecha_nacimiento' => trim((string) ($_POST['fecha_nacimiento'] ?? '')),
            'calle' => trim((string) ($_POST['calle'] ?? '')),
            'numero' => trim((string) ($_POST['numero'] ?? '')),
            'colonia' => trim((string) ($_POST['colonia'] ?? '')),
            'ciudad' => trim((string) ($_POST['ciudad'] ?? '')),
            'estado' => trim((string) ($_POST['estado'] ?? '')),
            'codigo_postal' => trim((string) ($_POST['codigo_postal'] ?? '')),
            'telefono' => trim((string) ($_POST['telefono'] ?? '')),
            'rol' => trim((string) ($_POST['rol'] ?? '')),
        ];

        try {
            $model = new Empleado();
            if ($model->findById($id) === null) {
                $this->setFlash('error', 'Error: Empleado no encontrado.');
                $this->redirect('empleados/modificar');
            }

            if ($model->update($id, $data)) {
                $this->setFlash('exito', 'Empleado modificado con éxito.');
                $this->redirect('admin');
            }
            $this->setFlash('error', 'Error al modificar empleado.');
        } catch (Throwable $e) {
            $this->setFlash('error', 'Error al modificar empleado: ' . $e->getMessage());
        }

        $this->redirect('empleados/modificar');
    }

    public function showEliminar(): void
    {
        Auth::requireRole('Administrador');

        $this->render('empleados/eliminar', [
            'title' => 'Eliminar Empleado',
            'flash' => $this->getFlash(),
            'empleadoConfirmar' => null,
        ]);
    }

    public function eliminar(): void
    {
        Auth::requireRole('Administrador');
        if (!$this->requireValidCsrf()) {
            $this->redirect('empleados/eliminar');
        }

        $id = filter_input(INPUT_POST, 'id_empleado', FILTER_VALIDATE_INT);
        if (!$id) {
            $this->setFlash('error', 'ID de empleado inválido.');
            $this->redirect('empleados/eliminar');
        }

        $model = new Empleado();

        if (isset($_POST['verificar'])) {
            $empleado = $model->findById($id);
            if ($empleado === null) {
                $this->setFlash('error', 'Error: Empleado no encontrado.');
                $this->redirect('empleados/eliminar');
            }

            if (strtolower($empleado['Rol']) === 'administrador') {
                $this->setFlash('error', 'No se puede eliminar a un usuario con rol de Administrador.');
                $this->redirect('empleados/eliminar');
            }

            if ($model->isInUse($id)) {
                $this->setFlash(
                    'error',
                    'No se puede eliminar al empleado porque está relacionado con registros de salida o entrada de productos.'
                );
                $this->redirect('empleados/eliminar');
            }

            $this->render('empleados/eliminar', [
                'title' => 'Eliminar Empleado',
                'flash' => null,
                'empleadoConfirmar' => $empleado,
            ]);
            return;
        }

        if (isset($_POST['confirmar']) && $_POST['confirmar'] === 'true') {
            $empleado = $model->findById($id);
            if ($empleado === null || strtolower($empleado['Rol']) === 'administrador' || $model->isInUse($id)) {
                $this->setFlash('error', 'No es posible eliminar este empleado.');
                $this->redirect('empleados/eliminar');
            }

            if ($model->delete($id)) {
                $this->setFlash('exito', 'Empleado eliminado correctamente.');
                $this->redirect('admin');
            }

            $this->setFlash('error', 'Error al eliminar el empleado.');
        }

        $this->redirect('empleados/eliminar');
    }
}
