<form method="post" action="<?= e(baseUrl('ventas/registrar')) ?>" id="form-venta">
  <input type="hidden" name="csrf_token" value="<?= e(\App\Core\Auth::csrfToken()) ?>">

  <div id="productos-container">
    <div class="producto-row">
      <input type="text" name="productos[]" placeholder="Código del producto" required>
      <input type="number" name="cantidades[]" placeholder="Cantidad" min="1" required>
      <button type="button" onclick="eliminarProducto(this)">Eliminar</button>
    </div>
  </div>
  <button type="button" onclick="agregarProducto()">Agregar otro producto</button>
  <button type="submit">Registrar Venta</button>
  <a href="<?= e(baseUrl('cajero')) ?>" class="btn-regresar">Regresar al Panel de Cajero</a>
</form>
