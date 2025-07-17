<?php include("layout/header.php"); ?>
<?php include("layout/slidebar.php");

require_once __DIR__ . '/../Controlador/VentasControlador.php';
$ventasControlador = new VentasControlador();
$formasPago = $ventasControlador->getFormasPago();
$productos = $ventasControlador->getProductosDisponibles();
$mensajes = [
    'registrado'   => ['Venta registrada correctamente.', 'success'],
    'actualizado'  => ['Venta actualizada correctamente.', 'success'],
    'eliminado'    => ['Venta eliminada correctamente.', 'success'],
    'sin_cambios'  => ['No se realizaron cambios en la venta.', 'warning'],
    'error'        => ['Ocurrió un error al procesar la venta.', 'danger'],
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
    
    <form id="formVenta" action="index.php?accion=guardarVenta" method="POST" class="ventas-form">
        <h2 class="ventas-titulo">Registrar Venta</h2>

        <!-- CLIENTE Y PRODUCTO -->
        <div class="ventas-columnas">
            <!-- DATOS CLIENTE -->
            <fieldset class="ventas-fieldset">
                <legend class="ventas-legend">Cliente</legend>
                <input type="text" name="cliente" placeholder="Nombre del Cliente" class="ventas-input" required>
                <input type="text" name="identificacion" placeholder="Cédula o RUC" class="ventas-input">
                <input type="tel" name="telefono" placeholder="Teléfono" class="ventas-input">
                <input type="email" name="correo" placeholder="Correo Electrónico" class="ventas-input">
            </fieldset>

            <!-- PRODUCTOS -->
            <fieldset class="ventas-fieldset">
                <legend class="ventas-legend">Agregar Productos</legend>
                <select id="productoSelect" class="ventas-select">
                    <option value="">Seleccione un producto</option>
                    <?php foreach ($productos as $prod): ?>
                        <option value="<?= $prod['id'] ?>"
                            data-nombre="<?= $prod['nombre'] ?>"
                            data-precio="<?= $prod['precio_venta'] ?>"
                            data-stock="<?= $prod['stock'] ?>">
                            <?= $prod['nombre'] ?> - $<?= number_format($prod['precio_venta'], 2) ?> (Stock: <?= $prod['stock'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="number" id="cantidadInput" placeholder="Cantidad" min="1" class="ventas-input">
                <button type="button" id="btnAgregarProducto" class="btn ventas-btn">Agregar</button>
            </fieldset>
        </div>

        <!-- TABLA -->
        <div class="ventas-tabla-container">
            <table id="tablaDetalle" class="ventas-tabla">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- TOTALES -->
        <fieldset class="ventas-fieldset">
            <legend class="ventas-legend">Totales</legend>
            <label>Subtotal: $<span id="subtotal">0.00</span></label><br>
            <label>IVA (12%): $<span id="iva">0.00</span></label><br>
            <label>Total: $<span id="total">0.00</span></label>
        </fieldset>

        <!-- PAGO -->
        <fieldset class="ventas-fieldset">
            <legend class="ventas-legend">Pago</legend>
            <select name="id_forma_pago" class="ventas-select" required>
                <option value="">Seleccione forma de pago</option>
                <?php foreach ($formasPago as $fp): ?>
                    <option value="<?= $fp['id'] ?>"><?= $fp['descripcion'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" step="0.01" name="importe_recibido" id="importeRecibido" placeholder="Importe Recibido" class="ventas-input" required>
            <label>Cambio: $<span id="cambio">0.00</span></label>
        </fieldset>

        <!--INPUTS -->
        <input type="hidden" name="detalles" id="inputDetalles">
        <input type="hidden" name="subtotal" id="inputSubtotal">
        <input type="hidden" name="iva" id="inputIva">
        <input type="hidden" name="total" id="inputTotal">
        <input type="hidden" name="cambio" id="inputCambio">

        <!-- BOTONES -->
        <div class="ventas-botones">
            <button type="submit" class="btn ventas-btn">Guardar Venta</button>
            <a href="index.php?accion=verFacturas" class="btn ventas-btn">Ver Facturas</a>
        </div>
    </form>
</main>


<script src="assets/js/ventas_form.js"></script>
<?php include 'layout/footer.php'; ?>