<?php

require_once __DIR__ . '/../config/conexion.php';

class LoginModelo {

    public function autenticar($usuario, $clave) {
        try {
            $conexion = new Conexion();
            $conn = $conexion->conectar();
            $sql = "SELECT usuario, clave 
                    FROM usuarios 
                    WHERE usuario = :usuario AND clave = :clave";

            $stmt = $conn->prepare($sql);
            $params = ['usuario' => $usuario, 'clave' => $clave];
            $stmt->execute($params);

            return $stmt->fetch(PDO::FETCH_ASSOC);
       
        } catch (PDOException $e) {
            error_log("Error en LoginModelo -> autenticar:" . $e->getMessage());
            
            return false;
        }
    }

    
}
?>
