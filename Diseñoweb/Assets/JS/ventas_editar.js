document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formEditarVenta');
    const tabla = document.querySelector('#tablaProductos tbody');

    const inputSubtotal = document.getElementById('inputSubtotal');
    const inputIva = document.getElementById('inputIva');
    const inputTotal = document.getElementById('inputTotal');
    const inputCambio = document.getElementById('inputCambio');
    const inputDetalles = document.getElementById('inputDetalles');
    const importeRecibido = document.getElementById('importeRecibido');

    const productoSelect = document.getElementById('productoSelect');
    const cantidadInput = document.getElementById('cantidadInput');
    const btnAgregar = document.getElementById('btnAgregarCarrito');

    // Agregar nuevo producto desde combo
    if (btnAgregar && productoSelect && cantidadInput) {
        btnAgregar.addEventListener('click', () => {
            const id = productoSelect.value;
            const option = productoSelect.options[productoSelect.selectedIndex];
            const nombre = option.dataset.nombre;
            const precio = parseFloat(option.dataset.precio);
            const stock = parseInt(option.dataset.stock);
            const cantidad = parseInt(cantidadInput.value);

            if (!id || !cantidad || cantidad <= 0) {
                alert('Selecciona un producto y una cantidad válida');
                return;
            }

            // Verificacion
            const existe = Array.from(tabla.querySelectorAll('tr')).find(row => {
                return row.querySelector('select')?.value === id;
            });

            if (existe) {
                const cantidadInput = existe.querySelector('input[name*="[cantidad]"]');
                const totalInput = existe.querySelector('input[name*="[total_linea]"]');
                const precioUnitarioInput = existe.querySelector('input[name*="[precio_unitario]"]');

                let nuevaCantidad = parseInt(cantidadInput.value) + cantidad;
                if (nuevaCantidad > stock) {
                    alert('Cantidad total excede stock');
                    return;
                }
                cantidadInput.value = nuevaCantidad;
                totalInput.value = (nuevaCantidad * parseFloat(precioUnitarioInput.value)).toFixed(2);
            } else {
                const i = tabla.querySelectorAll('tr').length;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <select name="productos[${i}][id_producto]">
                            <option value="${id}" selected>${nombre}</option>
                        </select>
                    </td>
                    <td><input type="number" name="productos[${i}][cantidad]" value="${cantidad}" required></td>
                    <td><input type="number" name="productos[${i}][precio_unitario]" value="${precio}" step="0.01" required></td>
                    <td><input type="number" name="productos[${i}][total_linea]" value="${(cantidad * precio).toFixed(2)}" step="0.01" readonly></td>
                    <td><button type="button" class="btn-eliminar">Eliminar</button></td>
                `;
                tabla.appendChild(row);
            }

            cantidadInput.value = '';
            productoSelect.value = '';
            calcularTotales();
        });
    }

    tabla.addEventListener('input', calcularTotales);
    importeRecibido.addEventListener('input', calcularTotales);

    function calcularTotales() {
        let subtotal = 0;
        tabla.querySelectorAll('tr').forEach(row => {
            const cantidad = parseFloat(row.querySelector('input[name*="[cantidad]"]').value) || 0;
            const precio = parseFloat(row.querySelector('input[name*="[precio_unitario]"]').value) || 0;
            const totalLinea = cantidad * precio;
            row.querySelector('input[name*="[total_linea]"]').value = totalLinea.toFixed(2);
            subtotal += totalLinea;
        });

        const iva = subtotal * 0.12;
        const total = subtotal + iva;
        const recibido = parseFloat(importeRecibido.value || 0);
        const cambio = recibido - total;

        inputSubtotal.value = subtotal.toFixed(2);
        inputIva.value = iva.toFixed(2);
        inputTotal.value = total.toFixed(2);
        inputCambio.value = cambio >= 0 ? cambio.toFixed(2) : 0;
    }

    tabla.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-eliminar')) {
            e.target.closest('tr').remove();
            calcularTotales();
        }
    });

    form.addEventListener('submit', () => {
        const detalles = [];
        tabla.querySelectorAll('tr').forEach(row => {
            detalles.push({
                id_producto: row.querySelector('select').value,
                cantidad: row.querySelector('input[name*="[cantidad]"]').value,
                precio_unitario: row.querySelector('input[name*="[precio_unitario]"]').value,
                total_linea: row.querySelector('input[name*="[total_linea]"]').value
            });
        });
        inputDetalles.value = JSON.stringify(detalles);
    });

    calcularTotales();
});

