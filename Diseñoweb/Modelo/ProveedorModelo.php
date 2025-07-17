<?php
require_once __DIR__ . '/../config/conexion.php';

class ProveedorModelo {

    public function registrarProveedor($Proveedor) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'INSERT INTO proveedor 
                (nombre_compania, nombre_contacto, cedula, telefono, email, fecha_integracion, producto_relacionado, tipo_contrato, estado)
                VALUES (:nombre_compania, :nombre_contacto, :cedula, :telefono, :email, :fecha_integracion, :producto_relacionado, :tipo_contrato, :estado)';
        
        $stmt = $conn->prepare($sql);

        return $stmt->execute([
            ':nombre_compania'     => $Proveedor['nombre_compania'],
            ':nombre_contacto'     => $Proveedor['nombre_contacto'],
            ':cedula'              => $Proveedor['cedula'],
            ':telefono'            => $Proveedor['telefono'],
            ':email'               => $Proveedor['email'],
            ':fecha_integracion'   => $Proveedor['fecha_integracion'],
            ':producto_relacionado'=> $Proveedor['producto_relacionado'],
            ':tipo_contrato'       => $Proveedor['tipo_contrato'],
            ':estado'              => 1 
        ]);
    }

    public function eliminarProveedor($id) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'UPDATE proveedor SET estado = 0 WHERE id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function actualizarProveedor($Proveedor) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'UPDATE proveedor SET 
                    nombre_compania = :nombre_compania,
                    nombre_contacto = :nombre_contacto,
                    cedula = :cedula,
                    telefono = :telefono,
                    email = :email,
                    fecha_integracion = :fecha_integracion,
                    producto_relacionado = :producto_relacionado,
                    tipo_contrato = :tipo_contrato,
                    estado = :estado
                WHERE id = :id';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':nombre_compania'     => $Proveedor['nombre_compania'],
            ':nombre_contacto'     => $Proveedor['nombre_contacto'],
            ':cedula'              => $Proveedor['cedula'],
            ':telefono'            => $Proveedor['telefono'],
            ':email'               => $Proveedor['email'],
            ':fecha_integracion'   => $Proveedor['fecha_integracion'],
            ':producto_relacionado'=> $Proveedor['producto_relacionado'],
            ':tipo_contrato'       => $Proveedor['tipo_contrato'],
            ':estado'              => $Proveedor['estado'],
            ':id'        => $Proveedor['id']
        ]);

        return $stmt->rowCount();
    }

    public function getProveedores() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'SELECT p.*, pr.nombre AS nombre_producto 
                FROM proveedor p
                LEFT JOIN producto pr ON p.producto_relacionado = pr.id
                WHERE p.estado = 1';

        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProveedorPorId($id) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'SELECT p.*, pr.nombre AS nombre_producto 
                FROM proveedor p
                LEFT JOIN producto pr ON p.producto_relacionado = pr.id
                WHERE p.id = :id';

        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>