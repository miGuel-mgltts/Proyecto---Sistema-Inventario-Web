<?php include("layout/header.php"); ?>
<?php include("layout/slidebar.php");

require_once __DIR__ . '/../controlador/VentasControlador.php';
$ventasControlador = new VentasControlador();
$formasPago = $ventasControlador->getFormasPago();

if (!empty($_GET['identificacion']) || !empty($_GET['forma_pago'])) {
    $ventas = $ventasControlador->filtrarFacturas();
} else {
    $ventas = $ventasControlador->listarVentas();
}

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
    <h2 class="ventas-titulo">Listado de Facturas</h2>

    <!-- Filtro -->
    <form method="GET" class="mb-3">
        <input type="hidden" name="accion" value="verFacturas">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="identificacion" class="form-control" placeholder="Buscar por Identificación"
                    value="<?= htmlspecialchars($_GET['identificacion'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <select name="forma_pago" class="form-control">
                    <option value="">-- Forma de Pago --</option>
                    <?php foreach ($formasPago as $forma): ?>
                        <option value="<?= htmlspecialchars($forma['descripcion']) ?>"
                            <?= (isset($_GET['forma_pago']) && $_GET['forma_pago'] === $forma['descripcion']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($forma['descripcion']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex">
                <button type="submit" class="btn btn-primary me-2">Buscar</button>
                <a href="index.php?accion=verFacturas" class="btn btn-secondary">Limpiar</a>
            </div>
        </div>
    </form>

    <table class="ventas-tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Identificación</th>
                <th>Total</th>
                <th>Fecha</th>
                <th>Forma de Pago</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ventas as $venta): ?>
                <tr>
                    <td><?= $venta['id'] ?></td>
                    <td><?= $venta['cliente'] ?></td>
                    <td><?= $venta['identificacion'] ?></td>
                    <td>$<?= number_format($venta['total'], 2) ?></td>
                    <td><?= $venta['fecha_venta'] ?></td>
                    <td><?= $venta['forma_pago'] ?></td>
                    <td>
                        <button class="btn btn-sm btn-info btn-ver-detalle" data-id="<?= $venta['id'] ?>">Ver</button>
                        <a href="index.php?accion=editarVenta&id=<?= $venta['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="index.php?accion=eliminarVenta&id=<?= $venta['id'] ?>" onclick="return confirm('¿Estás seguro de eliminar esta venta?');" class="btn btn-sm btn-danger">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<div id="modalFactura" class="modal" style="display:none;">
    <div class="modal-contenido">
        <span id="cerrarModal" style="float:right; cursor:pointer;">&times;</span>
        <h3>Factura</h3>
        <div id="contenidoFactura">
            <!-- Aquí se cargará la factura con JS -->
        </div>
    </div>
</div>

<script src="assets/js/ventas_modal.js"></script>