<?php
require_once __DIR__ . '/../Modelo/ProveedorModelo.php';

class ProveedorControlador {

    public function guardarProveedor() {
        $proveedorModelo = new ProveedorModelo();

        $Proveedor = [
            'nombre_compania'      => $_POST['nombre_compania'] ?? '',
            'nombre_contacto'      => $_POST['nombre_contacto'] ?? '',
            'cedula'               => $_POST['cedula'] ?? '',
            'telefono'             => $_POST['telefono'] ?? '',
            'email'                => $_POST['email'] ?? '',
            'fecha_integracion'    => $_POST['fecha_integracion'] ?? date('Y-m-d'),
            'producto_relacionado' => isset($_POST['producto_relacionado']) ? (int)$_POST['producto_relacionado'] : null,
            'tipo_contrato'        => $_POST['tipo_contrato'] ?? 'Por Proyecto',
            'estado'               => 1
        ];

        $resultado = $proveedorModelo->registrarProveedor($Proveedor);

        if ($resultado) {
            header("Location: index.php?accion=Proveedores&mensaje=registrado");
            exit;
        } else {
            header("Location: index.php?accion=Proveedores&mensaje=error");
            exit;
        }
    }

    public function eliminarProveedor($id) {
        $proveedorModelo = new ProveedorModelo();
        $filasAfectadas = $proveedorModelo->eliminarProveedor($id);

        if ($filasAfectadas > 0) {
            header("Location: index.php?accion=Proveedores&mensaje=eliminado");
        } else {
            header("Location: index.php?accion=Proveedores&mensaje=error");
        }
        exit;
    }

    public function actualizarProveedor() {
        $proveedorModelo = new ProveedorModelo();

        $Proveedor = [
            'id'                    => $_POST['id'],
            'nombre_compania'      => $_POST['nombre_compania'] ?? '',
            'nombre_contacto'      => $_POST['nombre_contacto'] ?? '',
            'cedula'               => $_POST['cedula'] ?? '',
            'telefono'             => $_POST['telefono'] ?? '',
            'email'                => $_POST['email'] ?? '',
            'fecha_integracion'    => $_POST['fecha_integracion'] ?? date('Y-m-d'),
            'producto_relacionado' => isset($_POST['producto_relacionado']) ? (int)$_POST['producto_relacionado'] : null,
            'tipo_contrato'        => $_POST['tipo_contrato'] ?? 'Por Proyecto',
            'estado'               => 1
        ];

        $filasAfectadas = $proveedorModelo->actualizarProveedor($Proveedor);

        if ($filasAfectadas > 0) {
            header("Location: index.php?accion=Proveedores&mensaje=actualizado");
        } else {
            header("Location: index.php?accion=Proveedores&mensaje=sin_cambios");
        }
        exit;
    }

    public function getProveedorPorId($id) {
        $proveedorModelo = new ProveedorModelo();
        return $proveedorModelo->getProveedorPorId($id);
    }

    public function getProveedores() {
        $proveedorModelo = new ProveedorModelo();
        return $proveedorModelo->getProveedores();
    }

}
