<?php
require_once __DIR__ . '/../Modelo/ProductoModelo.php';

class ProductoControlador {


    public function guardarProducto() {
        $productoModelo = new productoModelo();

        $Producto = [
            'codigo'        => $_POST['codigo'] ?? '',
            'nombre'        => $_POST['nombre'] ?? '',
            'stock'         => isset($_POST['stock']) ? (int)$_POST['stock'] : 0,
            'id_categoria'  => isset($_POST['categoria']) ? (int)$_POST['categoria'] : 0,
            'precio_venta'  => isset($_POST['precio_venta']) ? (float)$_POST['precio_venta'] : 0.0,
            'precio_compra' => isset($_POST['precio_compra']) ? (float)$_POST['precio_compra'] : 0.0,
            'fecha_ingreso' => $_POST['fecha_ingreso'] ?? date('Y-m-d'),
            'origen'        => $_POST['origen'] ?? 'importado',
            'estado'        => 1 
        ];


        $resultado = $productoModelo -> registrarProducto($Producto);

        if ($resultado) {
            header("Location: index.php?accion=Productos&mensaje=registrado");
            exit;
        } else {
            header("Location: index.php?accion=Productos&mensaje=error");
            exit;
        }


    }

    public function eliminarProducto($id) {
        $productoModelo = new ProductoModelo();
        $filasAfectadas = $productoModelo->eliminarProducto($id);

        if ($filasAfectadas > 0) {
            header("Location: index.php?accion=Productos&mensaje=eliminado");
        } else {
            header("Location: index.php?accion=Productos&mensaje=error");
        }
        exit;
    }

    public function actualizarProducto() {
    $productoModelo = new ProductoModelo();

    $Producto = [
        'id'            => $_POST['id'],
        'codigo'        => $_POST['codigo'] ?? '',
        'nombre'        => $_POST['nombre'] ?? '',
        'stock'         => isset($_POST['stock']) ? (int)$_POST['stock'] : 0,
        'id_categoria'  => isset($_POST['categoria']) ? (int)$_POST['categoria'] : 0,
        'precio_venta'  => isset($_POST['precio_venta']) ? (float)$_POST['precio_venta'] : 0.0,
        'precio_compra' => isset($_POST['precio_compra']) ? (float)$_POST['precio_compra'] : 0.0,
        'fecha_ingreso' => $_POST['fecha_ingreso'] ?? date('Y-m-d'),
        'origen'        => $_POST['origen'] ?? 'importado',
        'estado'        => 1
    ];

    $filasAfectadas = $productoModelo->actualizarProducto($Producto);

    if ($filasAfectadas > 0) {
        header("Location: index.php?accion=Productos&mensaje=actualizado");
    } else {
        header("Location: index.php?accion=Productos&mensaje=sin_cambios");
    }
    exit;
}

    public function getProductoPorId($id) {
        $productoModelo = new ProductoModelo();
        return $productoModelo->getProductoPorId($id);
    }

    public function getCategorias(){
        $productoModelo = new productoModelo();
        return $productoModelo->getCategoria();
    }

    public function getProductos(){
        $productoModelo = new productoModelo();
        return $productoModelo->getProductos();
    }

}