<div class="factura-container">
  <div class="header">
    <h1>FACTURA</h1>
    <p>N° <?= e($factura['Id_Factura'] ?? '') ?></p>
    <p>Fecha: <?= e(!empty($factura['Fecha']) ? date('d/m/Y', strtotime($factura['Fecha'])) : date('d/m/Y')) ?></p>
  </div>

  <div class="datos-empresa">
    <h3>Datos de la Empresa</h3>
    <p><strong>Nombre:</strong> Tienda de Conveniencia</p>
    <p><strong>RFC:</strong> XYZ123456ABC</p>
    <p><strong>Dirección:</strong> Av. Principal 123, Centro, Ciudad, Estado, C.P. 12345</p>
    <p><strong>Teléfono:</strong> 555-123-4567</p>
  </div>

  <div class="datos-cliente">
    <h3>Datos del Cliente</h3>
    <p><strong>Nombre:</strong> <?= e(($cliente['Nombre'] ?? '') . ' ' . ($cliente['Apellido'] ?? '')) ?></p>
    <p><strong>RFC:</strong> <?= e($cliente['RFC'] ?? '') ?></p>
    <p><strong>Dirección:</strong> <?= e(
      ($cliente['Calle'] ?? '') . ' ' . ($cliente['Número'] ?? '') . ', ' .
      ($cliente['Colonia'] ?? '') . ', ' . ($cliente['Ciudad'] ?? '') . ', ' .
      ($cliente['Estado'] ?? '') . ', C.P. ' . ($cliente['Código_Postal'] ?? '')
    ) ?></p>
    <p><strong>Teléfono:</strong> <?= e($cliente['Teléfono'] ?? '') ?></p>
    <p><strong>Email:</strong> <?= e($cliente['Correo'] ?? '') ?></p>
  </div>

  <div class="datos-factura">
    <div>
      <p><strong>Folio de Venta:</strong> <?= e($idSalida) ?></p>
    </div>
    <div>
      <p><strong>Fecha de Venta:</strong> <?= e(date('d/m/Y H:i:s')) ?></p>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Cantidad</th>
        <th>Descripción</th>
        <th>Precio Unitario</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($productos as $producto): ?>
        <tr>
          <td><?= e($producto['Cantidad']) ?></td>
          <td><?= e($producto['Nombre_Comercial']) ?></td>
          <td>$<?= e(number_format((float) $producto['Precio_Público'], 2)) ?></td>
          <td>$<?= e(number_format((float) $producto['Subtotal'], 2)) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="total">
    <p>TOTAL: $<?= e(number_format((float) $total, 2)) ?></p>
  </div>

  <div class="footer">
    <p>¡Gracias por su compra!</p>
    <p>Esta factura es válida como comprobante fiscal</p>
    <button type="button" class="btn-imprimir" onclick="window.print()">Imprimir Factura</button>
    <a href="<?= e(baseUrl('cajero')) ?>" class="btn-regresar">Regresar al Panel</a>
  </div>
</div>
