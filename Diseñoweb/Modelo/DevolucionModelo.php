<?php
require_once __DIR__ . '/../config/conexion.php';

class DevolucionModelo {

    public function registrarDevolucion($devolucion) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'INSERT INTO devolucion_producto 
                (codigo, id_producto, cantidad_devuelta, motivo_id, fecha_devolucion, responsable)
                VALUES (:codigo, :id_producto, :cantidad_devuelta, :motivo_id, :fecha_devolucion, :responsable)';

        $stmt = $conn->prepare($sql);

        return $stmt->execute([
            ':codigo'           => $devolucion['codigo'],
            ':id_producto'      => $devolucion['id_producto'],
            ':cantidad_devuelta'=> $devolucion['cantidad_devuelta'],
            ':motivo_id'        => $devolucion['motivo_id'],
            ':fecha_devolucion' => $devolucion['fecha_devolucion'],
            ':responsable'      => $devolucion['responsable']
        ]);
    }

    public function eliminarDevolucion($id) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'DELETE FROM devolucion_producto WHERE id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function actualizarDevolucion($devolucion) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'UPDATE devolucion_producto SET 
                    codigo = :codigo,
                    id_producto = :id_producto,
                    cantidad_devuelta = :cantidad_devuelta,
                    motivo_id = :motivo_id,
                    fecha_devolucion = :fecha_devolucion,
                    responsable = :responsable
                WHERE id = :id';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':codigo'           => $devolucion['codigo'],
            ':id_producto'      => $devolucion['id_producto'],
            ':cantidad_devuelta'=> $devolucion['cantidad_devuelta'],
            ':motivo_id'        => $devolucion['motivo_id'],
            ':fecha_devolucion' => $devolucion['fecha_devolucion'],
            ':responsable'      => $devolucion['responsable'],
            ':id'               => $devolucion['id']
        ]);

        return $stmt->rowCount();
    }

    public function getDevoluciones() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'SELECT dp.*, p.nombre AS nombre_producto, m.nombre AS motivo_nombre
                FROM devolucion_producto dp
                LEFT JOIN producto p ON dp.id_producto = p.id
                LEFT JOIN motivo_devolucion m ON dp.motivo_id = m.id';

        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDevolucionPorId($id) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'SELECT dp.*, p.nombre AS nombre_producto, m.nombre AS motivo_nombre
                FROM devolucion_producto dp
                LEFT JOIN producto p ON dp.id_producto = p.id
                LEFT JOIN motivo_devolucion m ON dp.motivo_id = m.id
                WHERE dp.id = :id';

        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMotivos() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'SELECT id, nombre FROM motivo_devolucion ORDER BY id';
        $stmt = $conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
