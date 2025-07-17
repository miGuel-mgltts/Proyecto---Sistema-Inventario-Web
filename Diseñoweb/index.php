<?php
session_start();
require_once __DIR__ . '/controlador/LoginControlador.php';
require_once __DIR__ . '/controlador/ProductoControlador.php';
require_once __DIR__ . '/controlador/ProveedorControlador.php';
require_once __DIR__ . '/controlador/VentasControlador.php';
require_once __DIR__ . '/controlador/DevolucionControlador.php';

$loginControl = new LoginControlador();
$ProductoControlador = new ProductoControlador();
$ProveedorControlador = new ProveedorControlador();
$ventasControl = new VentasControlador();
$DevolucionControlador = new DevolucionControlador();

$accion = $_GET['accion'] ?? 'default';

switch ($accion) {
    // SESION
    case 'login':
        $loginControl->login();
        break;
    case 'logout':
        $loginControl->logout();
        break;

    // NAVEGACIÓN
    case 'Productos':
        include 'vista/Productos.php';
        break;
    case 'Proveedores':
        include 'vista/Proveedores.php';
        break;
    case 'Ventas':
        include 'vista/Ventas.php';
        break;
    case 'Devoluciones':
        include 'vista/Devoluciones.php';
        break;

    // PRODUCTOS
    case 'guardarProducto':
        $ProductoControlador->guardarProducto();
        break;
    case 'eliminarProducto':
        $ProductoControlador->eliminarProducto($_GET['id']);
        break;
    case 'editarProducto':
        $producto = $ProductoControlador->getProductoPorId($_GET['id']);
        $categorias = $ProductoControlador->getCategorias();
        include 'vista/Productos.php';
        break;
    case 'actualizarProducto':
        $ProductoControlador->actualizarProducto();
        break;

    // PROVEEDOR
    case 'guardarProveedor':
        $ProveedorControlador->guardarProveedor();
        break;
    case 'eliminarProveedor':
        $ProveedorControlador->eliminarProveedor($_GET['id']);
        break;
    case 'editarProveedor':
        $proveedor = $ProveedorControlador->getProveedorPorId($_GET['id']);
        $productos = $ProductoControlador->getProductos();
        include 'vista/Proveedores.php';
        break;
    case 'actualizarProveedor':
        $ProveedorControlador->actualizarProveedor();
        break;

    // VENTAS
    case 'guardarVenta':
        $ventasControl->guardarVenta();
        break;

    case 'verFacturas':
        if (!empty($_GET['cliente']) || !empty($_GET['identificacion']) || !empty($_GET['fecha'])) {
            $ventas = $ventasControl->filtrarFacturas();
        } else {
            $ventas = $ventasControl->listarVentas();
        }
        include 'vista/ListadoVentas.php';
        break;

    case 'eliminarVenta':
        $ventasControl->eliminarVenta();
        break;

    case 'obtenerFactura':
        $controlador = new VentasControlador();
        $controlador->obtenerFacturaJSON();
        break;
    case 'editarVenta':
        $venta = $ventasControl->obtenerVentaPorId($_GET['id']);
        include 'vista/EditarVenta.php';
        break;

    case 'actualizarVenta':
        $ventasControl->actualizarVenta();
        break;


    // devoluciones
    case 'guardarDevolucion':
        $DevolucionControlador->guardarDevolucion();
        break;
    case 'eliminarDevolucion':
        $DevolucionControlador->eliminarDevolucion($_GET['id']);
        break;
    case 'editarDevolucion':
        $devolucion = $DevolucionControlador->getDevolucionPorId($_GET['id']);
        $productos = $ProductoControlador->getProductos();
        include 'vista/Devoluciones.php';
        break;
    case 'actualizarDevolucion':
        $DevolucionControlador->actualizarDevolucion();
        break;



    default:
        $loginControl->login();
        break;
}
