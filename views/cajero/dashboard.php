<h1>Bienvenido, <?= e($nombre) ?></h1>
<nav>
  <ul>
    <li><a href="<?= e(baseUrl('ventas/registrar')) ?>">Registrar Venta</a></li>
    <li><a href="<?= e(baseUrl('devoluciones/registrar')) ?>">Registrar Devolución</a></li>
    <li><a href="<?= e(baseUrl('compras/registrar')) ?>">Registrar Compra</a></li>
  </ul>
</nav>
<form method="post" action="<?= e(baseUrl('logout')) ?>" class="logout-form">
  <input type="hidden" name="csrf_token" value="<?= e(\App\Core\Auth::csrfToken()) ?>">
  <button type="submit" class="btn-cerrar-sesion">Cerrar Sesión</button>
</form>
