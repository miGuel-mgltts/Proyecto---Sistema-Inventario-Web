<?php
require_once __DIR__ . '/../config/conexion.php';

class VentasModelo
{
    public function registrarVenta($venta, $detalles)
    {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        try {
            $conn->beginTransaction();

            // Insertar cabecera 
            $sqlVenta = 'INSERT INTO ventas (cliente, identificacion, telefono, correo, subtotal, iva, total, importe_recibido, cambio, id_forma_pago, estado) 
                         VALUES (:cliente, :ident, :tel, :correo, :sub, :iva, :total, :recibido, :cambio, :fpago, :estado)';

            $stmtVenta = $conn->prepare($sqlVenta);
            $stmtVenta->execute([
                ':cliente'   => $venta['cliente'],
                ':ident'     => $venta['identificacion'],
                ':tel'       => $venta['telefono'],
                ':correo'    => $venta['correo'],
                ':sub'       => $venta['subtotal'],
                ':iva'       => $venta['iva'],
                ':total'     => $venta['total'],
                ':recibido'  => $venta['importe_recibido'],
                ':cambio'    => $venta['cambio'],
                ':fpago'     => $venta['id_forma_pago'],
                ':estado'    => 1
            ]);

            if ($stmtVenta->rowCount() === 0) {
                throw new Exception("No se pudo insertar la cabecera de la venta.");
            }

            $idVenta = $conn->lastInsertId();

            // Insertar detalle de venta
            $sqlDetalle = 'INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, total_linea)
                           VALUES (:id_venta, :id_producto, :cantidad, :precio_unitario, :total_linea)';

            $stmtDetalle = $conn->prepare($sqlDetalle);

            foreach ($detalles as $detalle) {
                $stmtDetalle->execute([
                    ':id_venta'       => $idVenta,
                    ':id_producto'    => $detalle['id_producto'],
                    ':cantidad'       => $detalle['cantidad'],
                    ':precio_unitario' => $detalle['precio_unitario'],
                    ':total_linea'    => $detalle['total_linea']
                ]);

                if ($stmtDetalle->rowCount() === 0) {
                    throw new Exception("No se pudo insertar el detalle de producto ID: " . $detalle['id_producto']);
                }
                // Descontar stock
                $sqlUpdateStock = 'UPDATE producto SET stock = stock - :cantidad WHERE id = :id_producto';
                $stmtStock = $conn->prepare($sqlUpdateStock);
                $stmtStock->execute([
                    ':cantidad'    => $detalle['cantidad'],
                    ':id_producto' => $detalle['id_producto']
                ]);
                if ($stmtStock->rowCount() === 0) {
                    throw new Exception("No se pudo actualizar el stock del producto ID: " . $detalle['id_producto']);
                }
            }

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Error en registrarVenta: " . $e->getMessage());
            return false;
        }
    }

    public function getFormasPago()
    {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'SELECT id, descripcion FROM formas_pago';
        $stmt = $conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductosActivos()
    {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'SELECT id, nombre, precio_venta, stock FROM producto WHERE estado = 1';
        $stmt = $conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerVentasActivas()
    {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = "SELECT v.id, v.cliente, v.identificacion, v.total, v.fecha_venta, f.descripcion AS forma_pago
                FROM ventas v
                INNER JOIN formas_pago f ON v.id_forma_pago = f.id
                WHERE v.estado = 1
                ORDER BY v.fecha_venta DESC";

        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarVentaLogica($id)
    {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = "UPDATE ventas SET estado = 0 WHERE id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    public function obtenerFacturaPorId($idVenta)
    {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        // cabecera
        $sqlVenta = "SELECT v.*, f.descripcion AS forma_pago 
                 FROM ventas v 
                 INNER JOIN formas_pago f ON v.id_forma_pago = f.id 
                 WHERE v.id = :id";

        $stmtVenta = $conn->prepare($sqlVenta);
        $stmtVenta->execute([':id' => $idVenta]);
        $venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

        // detalles
        $sqlDetalles = "SELECT d.*, p.nombre 
                    FROM detalle_venta d 
                    INNER JOIN producto p ON d.id_producto = p.id 
                    WHERE d.id_venta = :id";

        $stmtDetalles = $conn->prepare($sqlDetalles);
        $stmtDetalles->execute([':id' => $idVenta]);
        $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

        return ['venta' => $venta, 'detalles' => $detalles];
    }
    
    public function filtrarFacturas($identificacion = '', $formaPago = '')
    {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = "SELECT v.id, v.cliente, v.identificacion, v.total, v.fecha_venta, f.descripcion AS forma_pago
            FROM ventas v
            INNER JOIN formas_pago f ON v.id_forma_pago = f.id
            WHERE v.estado = 1";

        $parametros = [];

        if (!empty($identificacion)) {
            $sql .= " AND v.identificacion LIKE :identificacion";
            $parametros[':identificacion'] = "%$identificacion%";
        }

        if (!empty($formaPago)) {
            $sql .= " AND f.descripcion LIKE :formaPago";
            $parametros[':formaPago'] = "%$formaPago%";
        }

        $sql .= " ORDER BY v.fecha_venta DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute($parametros);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function actualizarVenta($venta, $detalles)
    {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        try {
            $conn->beginTransaction();

            // Actualizar cabecera
            $sqlUpdateVenta = "UPDATE ventas SET cliente = :cliente, identificacion = :ident, telefono = :tel, correo = :correo,
                       subtotal = :sub, iva = :iva, total = :total, importe_recibido = :recibido, cambio = :cambio, 
                       id_forma_pago = :fpago WHERE id = :id";

            $stmt = $conn->prepare($sqlUpdateVenta);
            $stmt->execute([
                ':cliente'   => $venta['cliente'],
                ':ident'     => $venta['identificacion'],
                ':tel'       => $venta['telefono'],
                ':correo'    => $venta['correo'],
                ':sub'       => $venta['subtotal'],
                ':iva'       => $venta['iva'],
                ':total'     => $venta['total'],
                ':recibido'  => $venta['importe_recibido'],
                ':cambio'    => $venta['cambio'],
                ':fpago'     => $venta['id_forma_pago'],
                ':id'        => $venta['id']
            ]);

            if ($stmt->rowCount() === 0) {
                throw new Exception("No se actualizó la factura de la venta.");
            }

            // Revertir stock anterior y eliminar detalles
            $stmtOldDetalles = $conn->prepare("SELECT id_producto, cantidad FROM detalle_venta WHERE id_venta = :id");
            $stmtOldDetalles->execute([':id' => $venta['id']]);
            $oldDetalles = $stmtOldDetalles->fetchAll(PDO::FETCH_ASSOC);

            foreach ($oldDetalles as $item) {
                $stmtStock = $conn->prepare("UPDATE producto SET stock = stock + :cantidad WHERE id = :id");
                $stmtStock->execute([':cantidad' => $item['cantidad'], ':id' => $item['id_producto']]);

                if ($stmtStock->rowCount() === 0) {
                    throw new Exception("No se pudo revertir el stock del producto ID: " . $item['id_producto']);
                }
            }

            $stmtDelete = $conn->prepare("DELETE FROM detalle_venta WHERE id_venta = :id");
            $stmtDelete->execute([':id' => $venta['id']]);
            if ($stmtDelete->rowCount() === 0) {
                throw new Exception("No se eliminaron los detalles anteriores.");
            }

            // Insertar nuevos detalles
            $stmtDetalle = $conn->prepare("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, total_linea)
                                   VALUES (:id_venta, :id_producto, :cantidad, :precio_unitario, :total_linea)");

            foreach ($detalles as $detalle) {
                $stmtDetalle->execute([
                    ':id_venta'       => $venta['id'],
                    ':id_producto'    => $detalle['id_producto'],
                    ':cantidad'       => $detalle['cantidad'],
                    ':precio_unitario' => $detalle['precio_unitario'],
                    ':total_linea'    => $detalle['total_linea']
                ]);

                if ($stmtDetalle->rowCount() === 0) {
                    throw new Exception("No se pudo insertar el detalle de factura para el producto ID: " . $detalle['id_producto']);
                }

                $stmtStock = $conn->prepare("UPDATE producto SET stock = stock - :cantidad WHERE id = :id");
                $stmtStock->execute([':cantidad' => $detalle['cantidad'], ':id' => $detalle['id_producto']]);

                if ($stmtStock->rowCount() === 0) {
                    throw new Exception("No se pudo actualizar el stock del producto ID: " . $detalle['id_producto']);
                }
            }

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Error en actualizarVenta: " . $e->getMessage());
            return false;
        }
    }
}
