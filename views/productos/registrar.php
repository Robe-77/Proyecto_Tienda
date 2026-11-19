<form method="post" action="<?= e(baseUrl('productos/registrar')) ?>">
  <input type="hidden" name="csrf_token" value="<?= e(\App\Core\Auth::csrfToken()) ?>">

  <input type="text" name="codigo_producto" placeholder="Código del producto" required>
  <input type="text" name="nombre_comercial" placeholder="Nombre comercial" required>
  <input type="number" step="0.01" min="0" name="precio_publico" placeholder="Precio público" required>
  <input type="number" min="0" name="existencia" placeholder="Existencia" required>
  <button type="submit">Registrar Producto</button>
  <a href="<?= e(baseUrl('admin')) ?>" class="btn-regresar">Regresar al Panel de Administración</a>
</form>
