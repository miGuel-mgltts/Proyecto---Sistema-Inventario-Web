<?php include("layout/header.php"); ?>
<?php include("layout/slidebar.php"); 

require_once __DIR__ . '../../Controlador/ProductoControlador.php';
require_once __DIR__ . '../../Controlador/DevolucionControlador.php';

$productoControlador = new ProductoControlador();
$devolucionControlador = new DevolucionControlador();

$motivos = $devolucionControlador->getMotivos();
$devoluciones = $devolucionControlador->getDevoluciones();
$Productos = $productoControlador->getProductos();

$mensajes = [
    'registrado'   => ['Devolución registrada correctamente.', 'success'],
    'actualizado'  => ['Devolución actualizada correctamente.', 'success'],
    'eliminado'    => ['Devolución eliminada correctamente.', 'success'],
    'sin_cambios'  => ['No se realizaron cambios en la Devolución.', 'warning'],
    'error'        => ['Ocurrió un error al procesar la Devolución.', 'danger'],
];

?> 

<main class="conteiner home">

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

     
    <section class="conteiner-left">
        <h1>DEVOLUCIÓN</h1>

        <form action="index.php?accion=<?= isset($devolucion) ? 'actualizarDevolucion' : 'guardarDevolucion' ?>"  method="POST" class="form-registro">

         <?php if (isset($devolucion)): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($devolucion['id']) ?>">
        <?php endif; ?>

            <div class="grid-form">
                <label for="codigo" class="col-span-2">Código</label>
                <label for="nombre" class="col-span-4">Nombre del Producto</label>
                <input type="text" id="codigo" name="codigo" required class="col-span-2" value="<?= isset($devolucion) ? htmlspecialchars($devolucion['codigo']) : '' ?>">               
                <select id="id_producto" name="id_producto" class="col-span-4" required >
                    <option value="">Seleccione un Producto</option>
                                <?php foreach ($Productos as $p): ?>
                                    <option value="<?= $p['id'] ?>" 
                                        <?= (isset($devolucion) && $devolucion['id_producto'] == $p['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                </select>
            </div>

            <div class="grid-form">
                <label for="cantidad_devuelta" class="col-span-2">Cantidad</label>
                <label for="motivo" class="col-span-4">Motivo</label>
                <input type="number" id="cantidad_devuelta" name="cantidad_devuelta" required class="col-span-2" value="<?= isset($devolucion) ? htmlspecialchars($devolucion['cantidad_devuelta']) : '' ?>">
                <select id="motivo_id" name="motivo_id" class="col-span-4">
                    <option value="">Seleccione un Motivo</option>
                                <?php foreach ($motivos as $m): ?>
                                    <option value="<?= $m['id'] ?>" 
                                        <?= (isset($devolucion) && $devolucion['motivo_id'] == $m['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($m['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                </select>
            </div>

            <div class="grid-form">
                <label for="fecha_devolucion" class="col-span-3">Fecha</label>
                <label for="responsable" class="col-span-3">Responsable</label>
                <input type="date" id="fecha_devolucion" name="fecha_devolucion" required class="col-span-3"value="<?= isset($devolucion) ? htmlspecialchars($devolucion['fecha_devolucion']) : '' ?>">
                <input type="text" id="responsable" name="responsable" required class="col-span-3"value="<?= isset($devolucion) ? htmlspecialchars($devolucion['responsable']) : '' ?>">
            </div>

            <div class="submit-row">
                <button class="btn" type="submit"><?= isset($devolucion) ? 'Actualizar' : 'Registrar' ?></button>
            </div>
        </form>
    </section>
         

        <section class="conteiner-right">

            <h1>REGISTROS DE DEVOLUCIÓN</h1>


            <!-- TABLA DE DEVOLUCIONES -->
            <table class="tabla-productos" id="tabla-devoluciones">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Motivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
              <tbody>
                    <?php foreach ($devoluciones as $dev): ?>
                        <tr>
                            <td><?= $dev['codigo'] ?></td>
                            <td><?= $dev['nombre_producto'] ?? 'Sin producto' ?></td>
                            <td><?= $dev['cantidad_devuelta'] ?></td>
                            <td><?= $dev['motivo_nombre'] ?? 'Sin motivo' ?></td>

                            <td>
                                <!-- Botón ver -->
                                <button 
                                    class="btn btn-editar btn-ver-devolucion"
                                    data-id="<?= $dev['id'] ?>"
                                    data-codigo="<?= $dev['codigo'] ?>"
                                    data-id-producto="<?= $dev['id_producto'] ?>"
                                    data-nombre-producto="<?= htmlspecialchars($dev['nombre_producto']) ?>"
                                    data-cantidad="<?= $dev['cantidad_devuelta'] ?>"
                                    data-motivo="<?= $dev['motivo_id'] ?>"
                                    data-motivo-nombre="<?= htmlspecialchars($dev['motivo_nombre']) ?>"
                                    data-fecha="<?= $dev['fecha_devolucion'] ?>"
                                    data-responsable="<?= htmlspecialchars($dev['responsable']) ?>">
                                    <i class='bx bx-show'></i>
                                </button>

                                <!-- Botón editar -->
                                <a href="index.php?accion=editarDevolucion&id=<?= $dev['id'] ?>" class="btn btn-editar">
                                    <i class='bx bx-edit'></i>
                                </a>

                                <!-- Botón eliminar -->
                                <a href="index.php?accion=eliminarDevolucion&id=<?= $dev['id'] ?>"
                                class="btn btn-eliminar"
                                onclick="return confirm('¿Estás seguro de eliminar esta devolución?');">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </section>

<!-- MODAL -->
<div class="modal fade" id="verDevolucionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalles de la Devolución</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p><strong>Código del Producto:</strong> <span id="modal-codigo"></span></p>
        <p><strong>Nombre del Producto:</strong> <span id="modal-nombre-producto"></span></p>
        <p><strong>Cantidad Devuelta:</strong> <span id="modal-cantidad"></span></p>
        <p><strong>Motivo:</strong> <span id="modal-motivo"></span></p>
        <p><strong>Fecha de Devolución:</strong> <span id="modal-fecha"></span></p>
        <p><strong>Responsable:</strong> <span id="modal-responsable"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

</main>

<?php include 'layout/footer.php'; ?>