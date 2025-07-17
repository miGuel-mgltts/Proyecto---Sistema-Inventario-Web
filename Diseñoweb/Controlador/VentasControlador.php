<?php
require_once __DIR__ . '/../modelo/VentasModelo.php';

class VentasControlador
{
    public function guardarVenta()
    {
        $modelo = new VentasModelo();
        $venta = [
            'cliente'           => $_POST['cliente'] ?? '',
            'identificacion'    => $_POST['identificacion'] ?? '',
            'telefono'          => $_POST['telefono'] ?? '',
            'correo'            => $_POST['correo'] ?? '',
            'subtotal'          => $_POST['subtotal'] ?? 0,
            'iva'               => $_POST['iva'] ?? 0,
            'total'             => $_POST['total'] ?? 0,
            'importe_recibido'  => $_POST['importe_recibido'] ?? 0,
            'cambio'            => $_POST['cambio'] ?? 0,
            'id_forma_pago'     => $_POST['id_forma_pago'] ?? 1,
            'estado'            => 1
        ];

        $detalles = json_decode($_POST['detalles'], true);

        if ($modelo->registrarVenta($venta, $detalles)) {
            header("Location: index.php?accion=Ventas&mensaje=registrado");
        } else {
            header("Location: index.php?accion=Ventas&mensaje=error");
        }
        exit;
    }

    public function getFormasPago()
    {
        $modelo = new VentasModelo();
        return $modelo->getFormasPago();
    }

    public function getProductosDisponibles()
    {
        $modelo = new VentasModelo();
        return $modelo->getProductosActivos();
    }

    public function listarVentas()
    {
        $modelo = new VentasModelo();
        return $modelo->obtenerVentasActivas();
    }

    public function eliminarVenta()
    {
        $modelo = new VentasModelo();
        if (isset($_GET['id'])) {
            $modelo->eliminarVentaLogica($_GET['id']);
        }
        header("Location: index.php?accion=verFacturas&mensaje=eliminado");
        exit;
    }
    public function obtenerFacturaJSON()
    {
        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        $modelo = new VentasModelo();
        $factura = $modelo->obtenerFacturaPorId($_GET['id']);
        echo json_encode($factura);
    }
    public function obtenerVentaPorId($id)
    {
        $modelo = new VentasModelo();
        return $modelo->obtenerFacturaPorId($id); 
    }

    public function actualizarVenta()
    {
        $modelo = new VentasModelo();
        $venta = [
            'id'                => $_POST['id'],
            'cliente'           => $_POST['cliente'],
            'identificacion'    => $_POST['identificacion'],
            'telefono'          => $_POST['telefono'],
            'correo'            => $_POST['correo'],
            'subtotal'          => $_POST['subtotal'],
            'iva'               => $_POST['iva'],
            'total'             => $_POST['total'],
            'importe_recibido'  => $_POST['importe_recibido'],
            'cambio'            => $_POST['cambio'],
            'id_forma_pago'     => $_POST['id_forma_pago'],
        ];

        $detalles = json_decode($_POST['detalles'], true);

        if ($modelo->actualizarVenta($venta, $detalles)) {
            header("Location: index.php?accion=Ventas&mensaje=actualizado");
        } else {
            header("Location: index.php?accion=Ventas&mensaje=sin_cambios");
        }
        exit;
    }
}
