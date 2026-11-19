<form method="post" action="<?= e(baseUrl('devoluciones/registrar')) ?>" id="form-devolucion">
  <input type="hidden" name="csrf_token" value="<?= e(\App\Core\Auth::csrfToken()) ?>">

  <input type="text" name="codigo_producto" placeholder="Código del producto" required>
  <input type="number" name="cantidad" placeholder="Cantidad a devolver" min="1" required>
  <button type="submit">Registrar Devolución</button>
  <a href="<?= e(baseUrl('cajero')) ?>" class="btn-regresar">Regresar al Panel de Cajero</a>
</form>
