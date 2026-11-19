function agregarProducto() {
  const container = document.getElementById('productos-container');
  const row = document.createElement('div');
  row.className = 'producto-row';
  row.innerHTML = `
    <input type="text" name="productos[]" placeholder="Código del producto" required />
    <input type="number" name="cantidades[]" placeholder="Cantidad" min="1" required />
    <button type="button" onclick="eliminarProducto(this)">Eliminar</button>
  `;
  container.appendChild(row);
}

function eliminarProducto(button) {
  button.parentElement.remove();
}
