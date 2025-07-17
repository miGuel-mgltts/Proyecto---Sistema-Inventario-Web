<?php include("layout/header.php"); ?>
<?php include("layout/slidebar.php");

$ventasControlador = new VentasControlador();
$formasPago = $ventasControlador->getFormasPago();
$productosDisponibles = $ventasControlador->getProductosDisponibles();
$ventaCabecera = $venta['venta'];
$ventaDetalles = $venta['detalles'];
$mensajes = [
    'registrado'   => ['Factura registrada correctamente.', 'success'],
    'actualizado'  => ['Factura actualizada correctamente.', 'success'],
    'eliminado'    => ['Factura eliminada correctamente.', 'success'],
    'sin_cambios'  => ['No se realizaron cambios en la Factura.', 'warning'],
    'error'        => ['Ocurrió un error al procesar la Factura.', 'danger'],
];
?>


<main class="ventas-container">
    <?php
    if (isset($_GET['mensaje']) && isset($mensajes[$_GET['mensaje']])) {
        [$texto, $tipo] = $mensajes[$_GET['mensaje']];
    ?>
        <div class="alert alert-<?= $tipo ?> alert-dismissible fade show text-center mb-3 alert-posicionada" role="alert">
            <?= htmlspecialchars($texto) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php
    }
    ?>
    <h2 class="ventas-titulo">Editar Venta #<?= $ventaCabecera['id'] ?></h2>

    <form id="formEditarVenta" action="index.php?accion=actualizarVenta" method="POST">
        <input type="hidden" name="id" value="<?= $ventaCabecera['id'] ?>">

        <label>Cliente: <input type="text" name="cliente" value="<?= $ventaCabecera['cliente'] ?>" required></label><br>
        <label>Identificación: <input type="text" name="identificacion" value="<?= $ventaCabecera['identificacion'] ?>" required></label><br>
        <label>Teléfono: <input type="text" name="telefono" value="<?= $ventaCabecera['telefono'] ?>"></label><br>
        <label>Correo: <input type="email" name="correo" value="<?= $ventaCabecera['correo'] ?>"></label><br>

        <label>Forma de Pago:
            <select name="id_forma_pago">
                <?php foreach ($formasPago as $fp): ?>
                    <option value="<?= $fp['id'] ?>" <?= $fp['id'] == $ventaCabecera['id_forma_pago'] ? 'selected' : '' ?>>
                        <?= $fp['descripcion'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <hr>
        <h3>Agregar Nuevo Producto</h3>
        <div>
            <select id="productoSelect">
                <option value="">-- Selecciona producto --</option>
                <?php foreach ($productosDisponibles as $prod): ?>
                    <option value="<?= $prod['id'] ?>"
                        data-nombre="<?= $prod['nombre'] ?>"
                        data-precio="<?= $prod['precio_venta'] ?>"
                        data-stock="<?= $prod['stock'] ?>">
                        <?= $prod['nombre'] ?> (Stock: <?= $prod['stock'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="number" id="cantidadInput" placeholder="Cantidad" min="1">
            <button type="button" id="btnAgregarCarrito">Agregar</button>
        </div>

        <hr>
        <h3>Productos en la venta</h3>
        <table id="tablaProductos">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total Línea</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventaDetalles as $i => $detalle): ?>
                    <tr>
                        <td>
                            <select name="productos[<?= $i ?>][id_producto]">
                                <?php foreach ($productosDisponibles as $prod): ?>
                                    <option value="<?= $prod['id'] ?>" <?= $prod['id'] == $detalle['id_producto'] ? 'selected' : '' ?>>
                                        <?= $prod['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="number" name="productos[<?= $i ?>][cantidad]" value="<?= $detalle['cantidad'] ?>" required></td>
                        <td><input type="number" name="productos[<?= $i ?>][precio_unitario]" value="<?= $detalle['precio_unitario'] ?>" step="0.01" required></td>
                        <td><input type="number" name="productos[<?= $i ?>][total_linea]" value="<?= $detalle['total_linea'] ?>" step="0.01" readonly></td>
                        <td><button type="button" class="btn-eliminar">Eliminar</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <hr>
        <div>
            <label>Subtotal: <span id="subtotal"><?= number_format($ventaCabecera['subtotal'], 2) ?></span></label><br>
            <label>IVA (12%): <span id="iva"><?= number_format($ventaCabecera['iva'], 2) ?></span></label><br>
            <label>Total: <span id="total"><?= number_format($ventaCabecera['total'], 2) ?></span></label><br>

            <label>Importe Recibido:
                <input type="number" step="0.01" name="importe_recibido" id="importeRecibido"
                    value="<?= $ventaCabecera['importe_recibido'] ?>"
                    inputmode="decimal" pattern="[0-9]+([\.,][0-9]+)?">
            </label><br>

            <label>Cambio: <span id="cambio"><?= number_format($ventaCabecera['cambio'], 2) ?></span></label>
        </div>


        <input type="hidden" name="subtotal" id="inputSubtotal" value="<?= $ventaCabecera['subtotal'] ?>">
        <input type="hidden" name="iva" id="inputIva" value="<?= $ventaCabecera['iva'] ?>">
        <input type="hidden" name="total" id="inputTotal" value="<?= $ventaCabecera['total'] ?>">
        <input type="hidden" name="cambio" id="inputCambio" value="<?= $ventaCabecera['cambio'] ?>">
        <input type="hidden" name="detalles" id="inputDetalles">

        <br><br>
        <button type="submit">Guardar Cambios</button>
        <a href="index.php?accion=verFacturas" class="btn-cancelar">Cancelar</a>
    </form>
</main>

<script src="assets/js/ventas_editar.js"></script>