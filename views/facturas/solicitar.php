<div class="resumen-venta">
  <h2>Resumen de Venta</h2>
  <p>Número de Venta: <?= e($idSalida) ?></p>
  <p>Total: $<?= e(number_format((float) $total, 2)) ?></p>
</div>
<form method="post" action="<?= e(baseUrl('factura')) ?>">
  <input type="hidden" name="csrf_token" value="<?= e(\App\Core\Auth::csrfToken()) ?>">

  <h3>¿Generar factura?</h3>
  <label>
    <input type="radio" name="requiere_factura" id="factura-no" value="0" onchange="toggleDatosFactura()"> No
  </label>
  <label>
    <input type="radio" name="requiere_factura" id="factura-si" value="1" onchange="toggleDatosFactura()"> Sí
  </label>

  <div id="opcion-cliente" class="is-hidden">
    <label>¿El cliente ya está dado de alta en el sistema?</label>
    <label>
      <input type="radio" name="cliente_registrado" value="si" onchange="toggleClienteRegistrado()"> Sí
    </label>
    <label>
      <input type="radio" name="cliente_registrado" value="no" onchange="toggleClienteRegistrado()"> No
    </label>
  </div>

  <div id="rfc-existente" class="is-hidden">
    <input type="text" name="rfc_existente" placeholder="RFC del cliente registrado" pattern="^[A-ZÑ&amp;]{3,4}[0-9]{6}[A-Z0-9]{3}$" title="Ingrese un RFC válido">
  </div>

  <div id="datos-factura" class="is-hidden">
    <h4>Datos para Facturación</h4>
    <input type="text" name="rfc" placeholder="RFC" pattern="^[A-ZÑ&amp;]{3,4}[0-9]{6}[A-Z0-9]{3}$" title="Ingrese un RFC válido">
    <input type="text" name="nombre" placeholder="Ingrese su nombre" required>
    <input type="text" name="apellido" placeholder="Ingrese su apellido" required>
    <input type="text" name="calle" placeholder="Calle" required>
    <input type="text" name="numero" placeholder="Número" required>
    <input type="text" name="colonia" placeholder="Colonia" required>
    <input type="text" name="ciudad" placeholder="Ciudad" required>
    <input type="text" name="estado" placeholder="Estado" required>
    <input type="text" name="codigo_postal" placeholder="Código Postal" pattern="^\d{5}$" title="Ingrese un código postal válido" required>
    <input type="tel" name="telefono" placeholder="Teléfono" title="Ingrese un número de teléfono válido" required>
    <input type="email" name="correo" placeholder="Correo Electrónico" required>
  </div>

  <button type="submit">Continuar</button>
</form>
