<?php
require_once __DIR__ . '/../Modelo/DevolucionModelo.php';

class DevolucionControlador {

    public function guardarDevolucion() {
        $devolucionModelo = new DevolucionModelo();

        $devolucion = [
            'codigo'            => $_POST['codigo'] ?? '',
            'id_producto'       => isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : null,
            'cantidad_devuelta' => isset($_POST['cantidad_devuelta']) ? (int)$_POST['cantidad_devuelta'] : 0,
            'motivo_id'         => isset($_POST['motivo']) ? (int)$_POST['motivo'] : null,
            'fecha_devolucion'  => $_POST['fecha_devolucion'] ?? date('Y-m-d'),
            'responsable'       => $_POST['responsable'] ?? ''
        ];

        $resultado = $devolucionModelo->registrarDevolucion($devolucion);

        if ($resultado) {
            header("Location: index.php?accion=Devoluciones&mensaje=registrado");
            exit;
        } else {
            header("Location: index.php?accion=Devoluciones&mensaje=error");
            exit;
        }
    }

    public function eliminarDevolucion($id) {
        $devolucionModelo = new DevolucionModelo();
        $filasAfectadas = $devolucionModelo->eliminarDevolucion($id);

        if ($filasAfectadas > 0) {
            header("Location: index.php?accion=Devoluciones&mensaje=eliminado");
        } else {
            header("Location: index.php?accion=Devoluciones&mensaje=error");
        }
        exit;
    }

    public function actualizarDevolucion() {
        $devolucionModelo = new DevolucionModelo();

        $devolucion = [
            'id'                => $_POST['id'],
            'codigo'            => $_POST['codigo'] ?? '',
            'id_producto'       => isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : null,
            'cantidad_devuelta' => isset($_POST['cantidad_devuelta']) ? (int)$_POST['cantidad_devuelta'] : 0,
            'motivo_id'         => isset($_POST['motivo_id']) ? (int)$_POST['motivo_id'] : null,
            'fecha_devolucion'  => $_POST['fecha_devolucion'] ?? date('Y-m-d'),
            'responsable'       => $_POST['responsable'] ?? ''
        ];

        $filasAfectadas = $devolucionModelo->actualizarDevolucion($devolucion);

        if ($filasAfectadas > 0) {
            header("Location: index.php?accion=Devoluciones&mensaje=actualizado");
        } else {
            header("Location: index.php?accion=Devoluciones&mensaje=sin_cambios");
        }
        exit;
    }

    public function getDevolucionPorId($id) {
        $devolucionModelo = new DevolucionModelo();
        return $devolucionModelo->getDevolucionPorId($id);
    }

    public function getDevoluciones() {
        $devolucionModelo = new DevolucionModelo();
        return $devolucionModelo->getDevoluciones();
    }

    public function getMotivos() {
        $devolucionModelo = new DevolucionModelo();
        return $devolucionModelo->getMotivos();
    }
}
