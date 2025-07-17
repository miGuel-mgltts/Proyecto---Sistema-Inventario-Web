document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-ver-detalle').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;

            fetch(`index.php?accion=obtenerFactura&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.venta) {
                        const v = data.venta;
                        const d = data.detalles;

                        let html = `
                            <p><strong>Cliente:</strong> ${v.cliente}</p>
                            <p><strong>Identificación:</strong> ${v.identificacion}</p>
                            <p><strong>Teléfono:</strong> ${v.telefono}</p>
                            <p><strong>Correo:</strong> ${v.correo}</p>
                            <p><strong>Forma de Pago:</strong> ${v.forma_pago}</p>
                            <p><strong>Fecha:</strong> ${v.fecha_venta}</p>
                            <hr>
                            <table border="1" width="100%" style="border-collapse:collapse;">
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Total Línea</th>
                                </tr>`;

                        d.forEach(item => {
                            html += `
                                <tr>
                                    <td>${item.nombre}</td>
                                    <td>${item.cantidad}</td>
                                    <td>$${parseFloat(item.precio_unitario).toFixed(2)}</td>
                                    <td>$${parseFloat(item.total_linea).toFixed(2)}</td>
                                </tr>`;
                        });

                        html += `</table>
                            <hr>
                            <p><strong>Subtotal:</strong> $${parseFloat(v.subtotal).toFixed(2)}</p>
                            <p><strong>IVA:</strong> $${parseFloat(v.iva).toFixed(2)}</p>
                            <p><strong>Total:</strong> $${parseFloat(v.total).toFixed(2)}</p>
                            <p><strong>Importe Recibido:</strong> $${parseFloat(v.importe_recibido).toFixed(2)}</p>
                            <p><strong>Cambio:</strong> $${parseFloat(v.cambio).toFixed(2)}</p>
                        `;

                        document.getElementById('contenidoFactura').innerHTML = html;
                        document.getElementById('modalFactura').style.display = 'block';
                    } else {
                        alert('No se pudo cargar la factura.');
                    }
                });
        });
    });

    // Cerrar modal
    document.getElementById('cerrarModal').addEventListener('click', () => {
        document.getElementById('modalFactura').style.display = 'none';
    });
});
