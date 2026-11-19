<form method="post" action="<?= e(baseUrl('empleados/modificar')) ?>">
  <input type="hidden" name="csrf_token" value="<?= e(\App\Core\Auth::csrfToken()) ?>">

  <input type="text" name="id_empleado" placeholder="ID del empleado a modificar" required>
  <input type="text" name="nombre" placeholder="Nuevo nombre (opcional)">
  <input type="text" name="apellido" placeholder="Nuevo apellido (opcional)">
  <label for="fecha_ingreso">Fecha de Ingreso:</label>
  <input type="date" id="fecha_ingreso" name="fecha_ingreso">
  <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
  <input type="date" id="fecha_nacimiento" name="fecha_nacimiento">
  <input type="text" name="calle" placeholder="Nueva calle (opcional)">
  <input type="text" name="numero" placeholder="Nuevo número (opcional)">
  <input type="text" name="colonia" placeholder="Nueva colonia (opcional)">
  <input type="text" name="ciudad" placeholder="Nueva ciudad (opcional)">
  <input type="text" name="estado" placeholder="Nuevo estado (opcional)">
  <input type="text" name="codigo_postal" placeholder="Nuevo código postal (opcional)">
  <input type="text" name="telefono" placeholder="Nuevo teléfono (opcional)">
  <label for="rol">Selecciona el Rol (opcional):</label>
  <select id="rol" name="rol">
    <option value="">Sin cambio</option>
    <option value="Administrador">Administrador</option>
    <option value="Cajero">Cajero</option>
    <option value="Comprador">Comprador</option>
  </select>
  <button type="submit">Modificar Empleado</button>
  <a href="<?= e(baseUrl('admin')) ?>" class="btn-regresar">Regresar al Panel de Administración</a>
</form>
