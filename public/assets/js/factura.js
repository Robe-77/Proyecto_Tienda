function toggleDatosFactura() {
  const facturaSi = document.getElementById('factura-si').checked;
  document.getElementById('opcion-cliente').classList.toggle('is-hidden', !facturaSi);
  document.getElementById('rfc-existente').classList.add('is-hidden');
  document.getElementById('datos-factura').classList.add('is-hidden');

  if (!facturaSi) {
    document.querySelectorAll('#datos-factura input, #rfc-existente input').forEach(input => {
      input.required = false;
    });
  }

  if (facturaSi) {
    toggleClienteRegistrado();
  }
}

function toggleClienteRegistrado() {
  const clienteRegistrado = document.querySelector('input[name="cliente_registrado"]:checked');
  if (!clienteRegistrado) return;

  if (clienteRegistrado.value === 'si') {
    document.getElementById('rfc-existente').classList.remove('is-hidden');
    document.getElementById('datos-factura').classList.add('is-hidden');
    document.querySelectorAll('#datos-factura input').forEach(input => input.required = false);
    document.querySelector('input[name="rfc_existente"]').required = true;
  } else {
    document.getElementById('rfc-existente').classList.add('is-hidden');
    document.getElementById('datos-factura').classList.remove('is-hidden');
    document.querySelectorAll('#datos-factura input').forEach(input => input.required = true);
    document.querySelector('input[name="rfc_existente"]').required = false;
  }
}

window.addEventListener('load', toggleDatosFactura);
