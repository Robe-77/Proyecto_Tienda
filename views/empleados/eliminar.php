<?php if (!empty($empleadoConfirmar)): ?>
  <form method="post" action="<?= e(baseUrl('empleados/eliminar')) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(\App\Core\Auth::csrfToken()) ?>">
    <h3>¿Seguro que deseas eliminar a <?= e($empleadoConfirmar['Nombre'] . ' ' . $empleadoConfirmar['Apellido']) ?>?</h3>
    <p>Rol: <?= e($empleadoConfirmar['Rol']) ?></p>
    <input type="hidden" name="id_empleado" value="<?= e($empleadoConfirmar['Id_Empleado']) ?>">
    <button type="submit" name="confirmar" value="true">Confirmar Eliminación</button>
    <a href="<?= e(baseUrl('empleados/eliminar')) ?>" class="btn-regresar">Cancelar</a>
  </form>
<?php else: ?>
  <form method="post" action="<?= e(baseUrl('empleados/eliminar')) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(\App\Core\Auth::csrfToken()) ?>">
    <input type="text" name="id_empleado" placeholder="ID del empleado a eliminar" required>
    <button type="submit" name="verificar" value="true">Verificar Empleado</button>
    <a href="<?= e(baseUrl('admin')) ?>" class="btn-regresar">Regresar al Panel de Administración</a>
  </form>
<?php endif; ?>
