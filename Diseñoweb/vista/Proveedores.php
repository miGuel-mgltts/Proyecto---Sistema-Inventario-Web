<?php include("layout/header.php"); ?>
<?php include("layout/slidebar.php"); 

require_once __DIR__ . '../../Controlador/ProductoControlador.php';
require_once __DIR__ . '../../Controlador/ProveedorControlador.php';
$productoControlador = new ProductoControlador();
$proveedorControlador = new ProveedorControlador();

$Proveedores = $proveedorControlador->getProveedores();
$Productos = $productoControlador->getProductos();

$mensajes = [
    'registrado'   => ['Proveedor registrado correctamente.', 'success'],
    'actualizado'  => ['Proveedor actualizado correctamente.', 'success'],
    'eliminado'    => ['Proveedor eliminado correctamente.', 'success'],
    'sin_cambios'  => ['No se realizaron cambios en el Proveedor.', 'warning'],
    'error'        => ['Ocurrió un error al procesar el Proveedor.', 'danger'],
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
            <h1>REGISTRO</h1>
            <form action="index.php?accion=<?= isset($proveedor) ? 'actualizarProveedor' : 'guardarProveedor' ?>" method="post" id="formProveedor" class="form-registro">

            <?php if (isset($proveedor)): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($proveedor['id']) ?>">
            <?php endif; ?>

                <div class="grid-form">
                    <label for="nombre_compania" class="col-span-3">Nombre de la Compañía:</label>
                    <label for="nombre_contacto" class="col-span-3" >Nombre del Contacto:</label>

                    <input type="text" id="nombre_compania" name="nombre_compania" required class="col-span-3" value="<?= isset($proveedor) ? htmlspecialchars($proveedor['nombre_compania']) : '' ?>">
                    <input type="text" id="nombre_contacto" name="nombre_contacto" required class="col-span-3" value="<?= isset($proveedor) ? htmlspecialchars($proveedor['nombre_contacto']) : '' ?>">
                </div>

                <div class="grid-form">
                    <label for="cedula" class="col-span-3">Cédula:</label>
                    <label for="telefono" class="col-span-3" >Teléfono:</label>

                    <input type="text" id="cedula" name="cedula" required class="col-span-3"  value="<?= isset($proveedor) ? htmlspecialchars($proveedor['cedula']) : '' ?>">
                    <input type="text" id="telefono" name="telefono" required class="col-span-3" value="<?= isset($proveedor) ? htmlspecialchars($proveedor['telefono']) : '' ?>">
                </div>

                <div class="grid-form">
                    <label for="email" class="col-span-3">Email:</label>
                    <label for="fecha_integracion" class="col-span-3">Fecha de Integración:</label>

                    <input type="email" id="email" name="email" required class="col-span-3" value="<?= isset($proveedor) ? htmlspecialchars($proveedor['email']) : '' ?>">
                    <input type="date" id="fecha_integracion" name="fecha_integracion" required class="col-span-3" value="<?= isset($proveedor) ? htmlspecialchars($proveedor['fecha_integracion']) : '' ?>">
                </div>

                <div class="grid-form">
                    <label for="producto_relacionado" class="col-span-3">Producto Relacionado:</label>
                    <label class="col-span-3">Tipo de Contrato:</label>

                    
                    <select id="producto_relacionado" name="producto_relacionado" class="col-span-3">
                            <option value="">Seleccione una Producto</option>
                                <?php foreach ($Productos as $p): ?>
                                    <option value="<?= $p['id'] ?>" 
                                        <?= (isset($proveedor) && $proveedor['producto_relacionado'] == $p['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                    </select>

                    <div class="col-span-3" style="display: flex; gap: 1.3rem; align-items: center;">
                        <label style="display: flex; align-items: center; gap: 0.4rem;">
                            <input type="radio" name="tipo_contrato" value="Anual">Anual
                        </label>

                        <label style="display: flex; align-items: center; gap: 0.2rem;">
                            <input type="radio" name="tipo_contrato" value="Por Proyecto" checked>Por Proyecto
                        </label>
                    </div>
                </div>
            
                <div class="submit-row">
                    <button type="submit" class="btn"><?= isset($proveedor) ? 'Actualizar' : 'Registrar' ?></button>
                </div>
            </form>
        </section>

        <section class="conteiner-right">
            <h1>LISTA DE PROVEEDORES</h1>

            <table class="tabla-productos" id="tabla-proveedores">
                <thead>
                    <tr>
                        <th>Compañia</th>
                        <th>Cedula</th>
                        <th>Telefono</th>
                        <th>Email</th>
                        <th>Producto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($Proveedores as $prov): ?>
                        <tr>
                            <td><?= $prov['nombre_compania'] ?></td>
                            <td><?= $prov['cedula'] ?></td>
                            <td><?= $prov['telefono'] ?></td>
                            <td><?= $prov['email'] ?></td>
                            <td><?= $prov['nombre_producto'] ?? 'Sin producto' ?></td>

                            <td>
                                <!-- Botón ver -->
                                <button 
                                    class="btn btn-editar btn-ver-proveedor"
                                    data-id="<?= $prov['id'] ?>"
                                    data-nombre-compania="<?= $prov['nombre_compania'] ?>"
                                    data-nombre-contacto="<?= $prov['nombre_contacto'] ?>"
                                    data-cedula="<?= $prov['cedula'] ?>"
                                    data-telefono="<?= $prov['telefono'] ?>"
                                    data-email="<?= $prov['email'] ?>"
                                    data-fecha-integracion="<?= $prov['fecha_integracion'] ?>"
                                    data-producto-relacionado="<?= $prov['producto_relacionado'] ?>"
                                    data-tipo-contrato="<?= $prov['tipo_contrato'] ?>">
                                    <i class='bx bx-show'></i>
                                </button>

                                <!-- Botón editar -->
                                <a href="index.php?accion=editarProveedor&id=<?= $prov['id'] ?>" class="btn btn-editar">
                                    <i class='bx bx-edit'></i>
                                </a>

                                <!-- Botón eliminar -->
                                <a href="index.php?accion=eliminarProveedor&id=<?= $prov['id'] ?>"
                                class="btn btn-eliminar"
                                onclick="return confirm('¿Estás seguro de eliminar este proveedor?');">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </section>

<!-- MODAL -->
<div class="modal fade" id="verProveedorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalles del Proveedor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p><strong>Nombre de la Compañía:</strong> <span id="modal-nombre-compania"></span></p>
        <p><strong>Nombre del Contacto:</strong> <span id="modal-nombre-contacto"></span></p>
        <p><strong>Cédula:</strong> <span id="modal-cedula"></span></p>
        <p><strong>Teléfono:</strong> <span id="modal-telefono"></span></p>
        <p><strong>Email:</strong> <span id="modal-email"></span></p>
        <p><strong>Fecha de Integración:</strong> <span id="modal-fecha-integracion"></span></p>
        <p><strong>Producto Relacionado:</strong> <span id="modal-producto-relacionado"></span></p>
        <p><strong>Tipo de Contrato:</strong> <span id="modal-tipo-contrato"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


</main>

<?php include 'layout/footer.php'; ?>