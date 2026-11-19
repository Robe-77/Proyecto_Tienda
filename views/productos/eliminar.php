<?php if (!empty($productoConfirmar)): ?>
  <form method="post" action="<?= e(baseUrl('productos/eliminar')) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(\App\Core\Auth::csrfToken()) ?>">
    <h3>¿Seguro que deseas eliminar el producto <?= e($productoConfirmar['Nombre_Comercial']) ?>?</h3>
    <input type="hidden" name="codigo_producto" value="<?= e($productoConfirmar['Código_Producto']) ?>">
    <button type="submit" name="confirmar" value="true">Confirmar Eliminación</button>
    <a href="<?= e(baseUrl('productos/eliminar')) ?>" class="btn-regresar">Cancelar</a>
  </form>
<?php else: ?>
  <form method="post" action="<?= e(baseUrl('productos/eliminar')) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(\App\Core\Auth::csrfToken()) ?>">
    <input type="text" name="codigo_producto" placeholder="Código del producto a eliminar" required>
    <button type="submit" name="verificar" value="true">Verificar Producto</button>
    <a href="<?= e(baseUrl('admin')) ?>" class="btn-regresar">Regresar al Panel de Administración</a>
  </form>
<?php endif; ?>
