<?php
class Conexion {

    public function conectar(){

        $host = "MIGUEL\SQLEXPRESS";
        $db = "InventarioDB";
        $user = "sa";
        $pass = "2004";

        try {
            $pdo = new PDO("sqlsrv:Server=$host;Database=$db", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}

?>
