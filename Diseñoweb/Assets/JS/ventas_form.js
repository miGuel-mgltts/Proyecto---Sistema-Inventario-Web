document.addEventListener('DOMContentLoaded', () => {
  /* ---------- elementos que existentes en Ventas.php ---------- */
  const productoSelect   = document.getElementById('productoSelect');
  const cantidadInput    = document.getElementById('cantidadInput');
  const btnAgregar       = document.getElementById('btnAgregarProducto');
  const tablaBody        = document.querySelector('#tablaDetalle tbody');

  const subtotalSpan     = document.getElementById('subtotal');
  const ivaSpan          = document.getElementById('iva');
  const totalSpan        = document.getElementById('total');
  const cambioSpan       = document.getElementById('cambio');

  const inputDetalles    = document.getElementById('inputDetalles');
  const inputSubtotal    = document.getElementById('inputSubtotal');
  const inputIva         = document.getElementById('inputIva');
  const inputTotal       = document.getElementById('inputTotal');
  const inputCambio      = document.getElementById('inputCambio');

  const importeRecibido  = document.getElementById('importeRecibido');


  if (!btnAgregar) return;

  /* ---------------- lógica del carrito ---------------- */
  let carrito = [];

  btnAgregar.addEventListener('click', () => {
    const id        = productoSelect.value;
    const option    = productoSelect.options[productoSelect.selectedIndex];
    const nombre    = option.dataset.nombre;
    const precio    = parseFloat(option.dataset.precio);
    const stock     = parseInt(option.dataset.stock, 10);
    const cantidad  = parseInt(cantidadInput.value, 10);

    if (!id || !cantidad || cantidad <= 0) {
      alert('Selecciona un producto y una cantidad válida');
      return;
    }
    if (cantidad > stock) {
      alert('No hay suficiente stock disponible');
      return;
    }

    const existente = carrito.find(p => p.id_producto === id);
    if (existente) {
      if (existente.cantidad + cantidad > stock) {
        alert('Cantidad total excede el stock disponible');
        return;
      }
      existente.cantidad += cantidad;
      existente.total_linea = (existente.cantidad * existente.precio_unitario).toFixed(2);
    } else {
      carrito.push({
        id_producto: id,
        nombre,
        cantidad,
        precio_unitario: precio,
        total_linea: (cantidad * precio).toFixed(2)
      });
    }

    renderTabla();
    calcularTotales();
    cantidadInput.value = '';
  });

  function renderTabla() {
    tablaBody.innerHTML = '';
    carrito.forEach((item, i) => {
      tablaBody.insertAdjacentHTML('beforeend', `
        <tr>
          <td>${item.nombre}</td>
          <td>${item.cantidad}</td>
          <td>$${item.precio_unitario.toFixed(2)}</td>
          <td>$${item.total_linea}</td>
          <td><button type="button" data-i="${i}" class="btnEliminar">Eliminar</button></td>
        </tr>`);
    });

    tablaBody.querySelectorAll('.btnEliminar').forEach(btn => {
      btn.addEventListener('click', () => {
        carrito.splice(btn.dataset.i, 1);
        renderTabla();
        calcularTotales();
      });
    });

    inputDetalles.value = JSON.stringify(carrito);
  }

  function calcularTotales() {
    const subtotal = carrito.reduce((s, p) => s + p.cantidad * p.precio_unitario, 0);
    const iva      = subtotal * 0.12;
    const total    = subtotal + iva;

    subtotalSpan.textContent = subtotal.toFixed(2);
    ivaSpan.textContent      = iva.toFixed(2);
    totalSpan.textContent    = total.toFixed(2);

    inputSubtotal.value = subtotal.toFixed(2);
    inputIva.value      = iva.toFixed(2);
    inputTotal.value    = total.toFixed(2);

    calcularCambio();
  }

  function calcularCambio() {
    const total    = parseFloat(inputTotal.value)     || 0;
    const recibido = parseFloat(importeRecibido.value) || 0;
    const cambio   = recibido - total;

    cambioSpan.textContent = cambio >= 0 ? cambio.toFixed(2) : '0.00';
    inputCambio.value      = cambio >= 0 ? cambio.toFixed(2) : 0;
  }

  importeRecibido.addEventListener('input', calcularCambio);
});
