<h1>Filtrar Movimientos</h1>
<form method="get" action="<?= e(baseUrl('movimientos')) ?>" class="filtro-form">
  <div class="form-group">
    <label for="fecha">Fecha:</label>
    <input type="date" name="fecha" id="fecha" value="<?= e($fechaFiltro) ?>">
  </div>
  <div class="form-group">
    <label for="codigo">Código de Producto:</label>
    <input type="text" name="codigo" id="codigo" value="<?= e($codigoFiltro) ?>">
  </div>
  <div class="form-group">
    <button type="submit" class="btn-filtro">Filtrar</button>
    <a href="<?= e(baseUrl('movimientos')) ?>" class="btn-filtro limpiar">Limpiar Filtros</a>
  </div>
</form>

<h2>Entradas de Productos</h2>
<table border="1">
  <thead>
    <tr>
      <th>Fecha</th>
      <th>ID Empleado</th>
      <th>Código Producto</th>
      <th>Tipo</th>
      <th>Cantidad</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($entradas as $row): ?>
      <tr>
        <td><?= e($row['Fecha']) ?></td>
        <td><?= e($row['Id_Empleado']) ?></td>
        <td><?= e($row['Código_Producto']) ?></td>
        <td><?= e($row['Tipo']) ?></td>
        <td><?= e($row['Cantidad']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<h2>Salidas de Productos</h2>
<table border="1">
  <thead>
    <tr>
      <th>Fecha</th>
      <th>ID Empleado</th>
      <th>Código Producto</th>
      <th>Cantidad</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($salidas as $row): ?>
      <tr>
        <td><?= e($row['Fecha']) ?></td>
        <td><?= e($row['Id_Empleado']) ?></td>
        <td><?= e($row['Código_Producto']) ?></td>
        <td><?= e($row['Cantidad']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<a href="<?= e(baseUrl('admin')) ?>" class="btn-regresar">Regresar al Panel de Administración</a>
