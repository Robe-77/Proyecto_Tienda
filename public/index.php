<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/app.php';
require_once BASE_PATH . '/src/Core/Autoloader.php';

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\CajeroController;
use App\Controllers\EmpleadoController;
use App\Controllers\FacturaController;
use App\Controllers\MovimientoController;
use App\Controllers\ProductoController;
use App\Controllers\VentaController;
use App\Core\Auth;
use App\Core\Router;

\App\Core\Autoloader::register();
Auth::startSession();

$router = new Router();

// Auth
$router->get('/', [AuthController::class, 'showLogin']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->match(['GET', 'POST'], '/logout', [AuthController::class, 'logout']);

// Dashboards
$router->get('/admin', [AdminController::class, 'index']);
$router->get('/cajero', [CajeroController::class, 'index']);

// Empleados
$router->get('/empleados/registrar', [EmpleadoController::class, 'showRegistrar']);
$router->post('/empleados/registrar', [EmpleadoController::class, 'registrar']);
$router->get('/empleados/modificar', [EmpleadoController::class, 'showModificar']);
$router->post('/empleados/modificar', [EmpleadoController::class, 'modificar']);
$router->get('/empleados/eliminar', [EmpleadoController::class, 'showEliminar']);
$router->post('/empleados/eliminar', [EmpleadoController::class, 'eliminar']);

// Productos
$router->get('/productos/registrar', [ProductoController::class, 'showRegistrar']);
$router->post('/productos/registrar', [ProductoController::class, 'registrar']);
$router->get('/productos/modificar', [ProductoController::class, 'showModificar']);
$router->post('/productos/modificar', [ProductoController::class, 'modificar']);
$router->get('/productos/eliminar', [ProductoController::class, 'showEliminar']);
$router->post('/productos/eliminar', [ProductoController::class, 'eliminar']);

// Movimientos (Admin + Cajero)
$router->match(['GET', 'POST'], '/movimientos', [MovimientoController::class, 'consultar']);
$router->get('/compras/registrar', [MovimientoController::class, 'showCompra']);
$router->post('/compras/registrar', [MovimientoController::class, 'registrarCompra']);
$router->get('/devoluciones/registrar', [MovimientoController::class, 'showDevolucion']);
$router->post('/devoluciones/registrar', [MovimientoController::class, 'registrarDevolucion']);

// Ventas & Facturas
$router->get('/ventas/registrar', [VentaController::class, 'showRegistrar']);
$router->post('/ventas/registrar', [VentaController::class, 'registrar']);
$router->get('/factura', [FacturaController::class, 'showSolicitar']);
$router->post('/factura', [FacturaController::class, 'procesar']);
$router->get('/factura/generada', [FacturaController::class, 'showGenerada']);

// Legacy .php route aliases for backward compatibility
$router->get('/IniciarSesion.php', [AuthController::class, 'showLogin']);
$router->post('/IniciarSesion.php', [AuthController::class, 'login']);
$router->match(['GET', 'POST'], '/CerrarSesion.php', [AuthController::class, 'logout']);
$router->get('/InicioAdmin.php', [AdminController::class, 'index']);
$router->get('/InicioCajero.php', [CajeroController::class, 'index']);
$router->get('/RegistrarEmpleado.php', [EmpleadoController::class, 'showRegistrar']);
$router->post('/RegistrarEmpleado.php', [EmpleadoController::class, 'registrar']);
$router->get('/ModificarEmpleado.php', [EmpleadoController::class, 'showModificar']);
$router->post('/ModificarEmpleado.php', [EmpleadoController::class, 'modificar']);
$router->get('/EliminarEmpleado.php', [EmpleadoController::class, 'showEliminar']);
$router->post('/EliminarEmpleado.php', [EmpleadoController::class, 'eliminar']);
$router->get('/RegistrarProducto.php', [ProductoController::class, 'showRegistrar']);
$router->post('/RegistrarProducto.php', [ProductoController::class, 'registrar']);
$router->get('/ModificarProducto.php', [ProductoController::class, 'showModificar']);
$router->post('/ModificarProducto.php', [ProductoController::class, 'modificar']);
$router->get('/EliminarProducto.php', [ProductoController::class, 'showEliminar']);
$router->post('/EliminarProducto.php', [ProductoController::class, 'eliminar']);
$router->match(['GET', 'POST'], '/ConsultarMovimientos.php', [MovimientoController::class, 'consultar']);
$router->get('/RegistrarCompra.php', [MovimientoController::class, 'showCompra']);
$router->post('/RegistrarCompra.php', [MovimientoController::class, 'registrarCompra']);
$router->get('/RegistrarDevolucion.php', [MovimientoController::class, 'showDevolucion']);
$router->post('/RegistrarDevolucion.php', [MovimientoController::class, 'registrarDevolucion']);
$router->get('/RegistrarVenta.php', [VentaController::class, 'showRegistrar']);
$router->post('/RegistrarVenta.php', [VentaController::class, 'registrar']);
$router->get('/Factura.php', [FacturaController::class, 'showSolicitar']);
$router->post('/Factura.php', [FacturaController::class, 'procesar']);
$router->get('/FacturaGenerada.php', [FacturaController::class, 'showGenerada']);

$router->dispatch();
