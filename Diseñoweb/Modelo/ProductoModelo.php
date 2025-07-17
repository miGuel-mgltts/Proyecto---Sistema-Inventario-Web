<?php
require_once __DIR__ . '/../config/conexion.php';

class ProductoModelo {

    public function registrarProducto($Producto) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'INSERT INTO "producto" ("codigo", "nombre", "stock", "id_categoria", "precio_venta", "precio_compra", "fecha_ingreso", "origen", "estado") 
                        VALUES (:codigo, :nombre, :stock, :id_categoria, :precio_venta, :precio_compra, :fecha_ingreso, :origen, :estado)';
        $stmt = $conn->prepare($sql);

        return $stmt->execute([
            ':codigo'       => $Producto['codigo'],
            ':nombre'       => $Producto['nombre'],
            ':stock'        => $Producto['stock'],
            ':id_categoria' => $Producto['id_categoria'],
            ':precio_venta' => $Producto['precio_venta'],
            ':precio_compra'=> $Producto['precio_compra'],
            ':fecha_ingreso'=> $Producto['fecha_ingreso'],
            ':origen'       => $Producto['origen'],
            ':estado'       => $Producto['estado']
        ]);

    }

    public function eliminarProducto($id) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'UPDATE producto SET estado = 0 WHERE id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function actualizarProducto($Producto) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'UPDATE "producto" SET 
                    "codigo" = :codigo, 
                    "nombre" = :nombre, 
                    "stock" = :stock, 
                    "id_categoria" = :id_categoria, 
                    "precio_venta" = :precio_venta, 
                    "precio_compra" = :precio_compra, 
                    "fecha_ingreso" = :fecha_ingreso, 
                    "origen" = :origen, 
                    "estado" = :estado
                WHERE "id" = :id';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':codigo'       => $Producto['codigo'],
            ':nombre'       => $Producto['nombre'],
            ':stock'        => $Producto['stock'],
            ':id_categoria' => $Producto['id_categoria'],
            ':precio_venta' => $Producto['precio_venta'],
            ':precio_compra'=> $Producto['precio_compra'],
            ':fecha_ingreso'=> $Producto['fecha_ingreso'],
            ':origen'       => $Producto['origen'],
            ':estado'       => $Producto['estado'],
            ':id'           => $Producto['id']
        ]);

        return $stmt->rowCount();
    }




    public function getCategoria() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'SELECT "id" , "nombre" FROM "categorias" ORDER BY "id"';
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getProductos() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'SELECT p.id, p.codigo, p.nombre, p.stock, p.precio_venta, p.precio_compra, p.fecha_ingreso, p.origen, p.estado,
                    c.nombre AS nombre_categoria
                FROM producto p
                LEFT JOIN categorias c ON p.id_categoria = c.id
                WHERE p.estado = 1';

        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductoPorId($id) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $sql = 'SELECT p.*, c.nombre AS nombre_categoria FROM producto p 
                LEFT JOIN categorias c ON p.id_categoria = c.id
                WHERE p.id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}
?>

