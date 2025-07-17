<?php
require_once __DIR__ . '/../modelo/LoginModelo.php';

class LoginControlador {

    public function login() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $clave = $_POST['clave'] ?? '';

            $modelo = neW LoginModelo();
            $usuarioData = $modelo -> autenticar($usuario, $clave);

            if ($usuarioData) {
                session_start();
                $_SESSION['usuario'] = $usuarioData;
                header('Location: index.php?accion=Productos');
                exit;
            } else {
                $error = "Credenciales incorrectas";
                $this -> mostrarFormulario($error);
            }
        } else {
             $this -> mostrarFormulario();
        }
    }

    public function logout() {
        session_start();
        session_unset(); 
        session_destroy();
        header("Location: index.php?accion=login");
        exit;
    }

    public function mostrarFormulario($error = '') {
        require_once __DIR__ . '/../vista/login.php';
    }
}
