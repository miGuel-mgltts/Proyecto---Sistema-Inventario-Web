<?php include("layout/header.php"); ?>
<?php include("layout/slidebar.php");

require_once __DIR__ . '../../Controlador/ProductoControlador.php';
$productoControlador = new ProductoControlador();
$categorias = $productoControlador->getCategorias();
$Productos = $productoControlador->getProductos();

$mensajes = [
    'registrado'   => ['Producto registrado correctamente.', 'success'],
    'actualizado'  => ['Producto actualizado correctamente.', 'success'],
    'eliminado'    => ['Producto eliminado correctamente.', 'success'],
    'sin_cambios'  => ['No se realizaron cambios en el producto.', 'warning'],
    'error'        => ['Ocurrió un error al procesar el producto.', 'danger'],
];

?>

    <!--CONTENEDOR-->
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
    
            <form action="index.php?accion=<?= isset($producto) ? 'actualizarProducto' : 'guardarProducto' ?>" method="POST" id="formProducto" class="form-registro">

            <?php if (isset($producto)): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($producto['id']) ?>">
            <?php endif; ?>
            
                    <div class="grid-form">
                        <!-- FILA DE ETIQUETAS -->
                        <label for="codigo" class="col-span-2">Código</label>
                        <label for="nombre" class="col-span-4">Nombre</label>
            
                        <!-- FILA DE CAMPOS -->
                        <input type="text" id="codigo" name="codigo" required class="col-span-2" value="<?= isset($producto) ? htmlspecialchars($producto['codigo']) : '' ?>">
                        <input type="text" id="nombre" name="nombre" required class="col-span-4" value="<?= isset($producto) ? htmlspecialchars($producto['nombre']) : '' ?>" >
                    </div>

                    <div class="grid-form">
                        <!-- FILA DE ETIQUETAS -->
                        <label for="stock" class="col-span-2">Stock Inicial</label>
                        <label for="categoria" class="col-span-4">Categoría</label>
            
                        <!-- FILA DE CAMPOS -->
                        <input type="number" id="stock" name="stock" required class="col-span-2" value="<?= isset($producto) ? htmlspecialchars($producto['stock']) : '' ?>">
                        <select id="categoria" name="categoria" class="col-span-4">
                            <option value="">Seleccione una categoría</option>
                                <?php foreach ($categorias as $c): ?>
                                    <option value="<?= $c['id'] ?>" 
                                        <?= (isset($producto) && $producto['id_categoria'] == $c['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="grid-form">
                        <!-- FILA DE ETIQUETAS -->
                        <label for="precio_venta" class="col-span-3">Precio Venta</label>
                        <label for="precio_compra"class="col-span-3">Precio Compra</label>
            
                        <!-- FILA DE CAMPOS -->
                        <input type="number" id="precio_venta" name="precio_venta" step="0.01" required class="col-span-3" value="<?= isset($producto) ? htmlspecialchars($producto['precio_venta']) : '' ?>">
                        <input type="number" id="precio_compra" name="precio_compra" step="0.01" required class="col-span-3" value="<?= isset($producto) ? htmlspecialchars($producto['precio_compra']) : '' ?>">
                    </div>

                    <div class="grid-form">
                        <!-- FILA DE ETIQUETAS -->
                        <label for="fecha_ingreso" class="col-span-3">Fecha de Ingreso</label>
                        <label class="col-span-3">Origen</label>

                        <!-- FILA DE CAMPOS -->
                        <input type="date" id="fecha_ingreso" name="fecha_ingreso" required class="col-span-3" value="<?= isset($producto) ? htmlspecialchars($producto['fecha_ingreso']) : '' ?>">

                        <div class="col-span-3" style="display: flex; gap: 1.3rem; align-items: center;">
                            <label style="display: flex; align-items: center; gap: 0.4rem;">
                                <input type="radio" name="origen" value="nacional">
                                Nacional
                            </label>

                            <label style="display: flex; align-items: center; gap: 0.2rem;">
                                <input type="radio" name="origen" value="importado" checked>
                                Importado
                            </label>
                        </div>
                    </div>
            
                    <div class="submit-row">
                        <button type="submit" class="btn"><?= isset($producto) ? 'Actualizar' : 'Registrar' ?></button>
                    </div>
            </form>

        </section>

        <section class="conteiner-right">

            <h1>VER REGISTROS</h1>
            
            <!-- TABLA DE PRODUCTOS -->

            <table class="tabla-productos" id="tabla-productos">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio de Venta</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($Productos as $p): ?>
                            <tr>
                                <td><?= $p['codigo'] ?></td>
                                <td><?= $p['nombre'] ?></td>
                                <td><?= $p['nombre_categoria'] ?></td>
                                <td><?= $p['precio_venta'] ?></td>
                                <td><?= $p['stock'] ?></td>
                                
                                <td>
                                    <button 
                                        class="btn btn-editar btn-ver-producto"
                                        data-id="<?= $p['id'] ?>"
                                        data-codigo="<?= $p['codigo'] ?>"
                                        data-nombre="<?= $p['nombre'] ?>"
                                        data-categoria="<?= $p['nombre_categoria'] ?>"
                                        data-precio-venta="<?= $p['precio_venta'] ?>"
                                        data-precio-compra="<?= $p['precio_compra'] ?>"
                                        data-stock="<?= $p['stock'] ?>"
                                        data-fecha="<?= $p['fecha_ingreso'] ?>"
                                        data-origen="<?= $p['origen'] ?>">
                                        <i class='bx bx-show'></i>
                                    </button>

                                    
                                    <a href="index.php?accion=editarProducto&id=<?= $p['id'] ?>" class="btn btn-editar">
                                       <i class='bx bx-edit'></i>
                                    </a>
                                    
                                    <a href="index.php?accion=eliminarProducto&id=<?= $p['id'] ?>"
                                        class="btn btn-eliminar" 
                                        onclick="return confirm('¿Estás seguro de eliminar este producto?');">
                                        <i class='bx bx-trash'></i>
                                    </a> 
                                </td>

                            </tr>
                        <?php endforeach; ?>
                </tbody>
            </table>
            
        </section>

<!-- MODAL -->
<div class="modal fade" id="verProductoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalles del Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p><strong>Código:</strong> <span id="modal-codigo"></span></p>
        <p><strong>Nombre:</strong> <span id="modal-nombre"></span></p>
        <p><strong>Categoría:</strong> <span id="modal-categoria"></span></p>
        <p><strong>Precio Venta:</strong> <span id="modal-precio-venta"></span></p>
        <p><strong>Precio Compra:</strong> <span id="modal-precio-compra"></span></p>
        <p><strong>Stock:</strong> <span id="modal-stock"></span></p>
        <p><strong>Fecha de Ingreso:</strong> <span id="modal-fecha"></span></p>
        <p><strong>Origen:</strong> <span id="modal-origen"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

</main>



<?php include 'layout/footer.php'; ?>