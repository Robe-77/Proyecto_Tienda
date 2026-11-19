<h1>Bienvenido, Administrador (<?= e($nombre) ?>)</h1>
<nav>
  <ul>
    <li><a href="<?= e(baseUrl('empleados/registrar')) ?>">Registrar Empleado</a></li>
    <li><a href="<?= e(baseUrl('empleados/modificar')) ?>">Modificar Empleado</a></li>
    <li><a href="<?= e(baseUrl('empleados/eliminar')) ?>">Eliminar Empleado</a></li>
    <li><a href="<?= e(baseUrl('productos/registrar')) ?>">Registrar Producto</a></li>
    <li><a href="<?= e(baseUrl('productos/modificar')) ?>">Modificar Producto</a></li>
    <li><a href="<?= e(baseUrl('productos/eliminar')) ?>">Eliminar Producto</a></li>
    <li><a href="<?= e(baseUrl('movimientos')) ?>">Consultar Movimientos</a></li>
  </ul>
</nav>
<form method="post" action="<?= e(baseUrl('logout')) ?>" class="logout-form">
  <input type="hidden" name="csrf_token" value="<?= e(\App\Core\Auth::csrfToken()) ?>">
  <button type="submit" class="btn-cerrar-sesion">Cerrar Sesión</button>
</form>
