<form method="post" action="<?= e(baseUrl('compras/registrar')) ?>" id="form-compra">
  <input type="hidden" name="csrf_token" value="<?= e(\App\Core\Auth::csrfToken()) ?>">

  <input type="text" name="id_proveedor" placeholder="ID del Proveedor" required>
  <input type="text" name="codigo_producto" placeholder="Código del producto" required>
  <input type="number" name="cantidad" placeholder="Cantidad a comprar" min="1" required>
  <button type="submit">Registrar Compra</button>
  <a href="<?= e(baseUrl('cajero')) ?>" class="btn-regresar">Regresar al Panel de Cajero</a>
</form>
