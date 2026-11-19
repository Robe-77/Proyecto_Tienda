<form method="post" action="<?= e(baseUrl('empleados/registrar')) ?>">
  <input type="hidden" name="csrf_token" value="<?= e(\App\Core\Auth::csrfToken()) ?>">

  <input type="password" name="contraseña" placeholder="Contraseña" required>
  <input type="text" name="nombre" placeholder="Nombre" required>
  <input type="text" name="apellido" placeholder="Apellido" required>
  <label for="fecha_ingreso">Fecha de Ingreso:</label>
  <input type="date" id="fecha_ingreso" name="fecha_ingreso" required>
  <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
  <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
  <input type="text" name="calle" placeholder="Calle" required>
  <input type="text" name="numero" placeholder="Número" required>
  <input type="text" name="colonia" placeholder="Colonia" required>
  <input type="text" name="ciudad" placeholder="Ciudad" required>
  <input type="text" name="estado" placeholder="Estado" required>
  <input type="text" name="codigo_postal" placeholder="Código Postal" required>
  <input type="text" name="telefono" placeholder="Teléfono" required>
  <label for="rol">Selecciona el Rol:</label>
  <select id="rol" name="rol">
    <option value="Administrador">Administrador</option>
    <option value="Cajero">Cajero</option>
  </select>
  <button type="submit">Registrar Empleado</button>
  <a href="<?= e(baseUrl('admin')) ?>" class="btn-regresar">Regresar al Panel de Administración</a>
</form>
