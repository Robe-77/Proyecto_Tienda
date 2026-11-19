<form method="post" action="<?= e(baseUrl('productos/modificar')) ?>">
  <input type="hidden" name="csrf_token" value="<?= e(\App\Core\Auth::csrfToken()) ?>">

  <input type="text" name="codigo_producto" placeholder="Código del producto a modificar" required>
  <input type="text" name="nombre_comercial" placeholder="Nuevo nombre comercial (opcional)">
  <input type="number" step="0.01" min="0" name="precio_publico" placeholder="Nuevo precio público (opcional)">
  <button type="submit">Modificar Producto</button>
  <a href="<?= e(baseUrl('admin')) ?>" class="btn-regresar">Regresar al Panel de Administración</a>
</form>
