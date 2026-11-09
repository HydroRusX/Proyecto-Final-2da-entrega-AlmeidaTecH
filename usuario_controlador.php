<?php
require_once "sesion.php";
require_once "../modelos/usuario.php";

$accion = $_GET['accion'] ?? 'login';

try {

    switch ($accion) {

        // ---------------------------------
        // Iniciar sesion
        // ---------------------------------
        case 'login':

            // Si ya inicio sesion, lo mando al inicio
            if (isset($_SESSION['usuario'])) {
                header("Location: inicio_controlador.php");
                exit;
            }

            $error = "";

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $email = trim((string) ($_POST['email'] ?? ''));
                $contrasena = (string) ($_POST['contrasena'] ?? '');

                // Validacion de los datos del formulario
                if ($email === '' || $contrasena === '') {

                    $error = "Debes ingresar el email y la contraseña.";

                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {

                    $error = "El email no tiene un formato valido.";

                } else {

                    $modeloUsuario = new Usuario();
                    $usuario = $modeloUsuario->buscarPorEmail($email);

                    // password_verify compara la contraseña escrita con el hash guardado en la base de datos
                    if ($usuario && password_verify($contrasena, $usuario['Contraseña'])) {

                        session_regenerate_id(true);

                        $_SESSION['usuario'] = $usuario['Nombre'];
                        $_SESSION['id_usuario'] = $usuario['Id_usuario'];
                        $_SESSION['tipo_usuario'] = $usuario['Tipo_usuario'];
                        $_SESSION['email'] = $usuario['Email'];

                        header("Location: inicio_controlador.php");
                        exit;
                    }

                    $error = "El email o la contraseña son incorrectos.";
                }
            }

            require_once "../vistas/login.php";
            break;

        // ---------------------------------
        // Cerrar sesion
        // ---------------------------------
        case 'logout':

            $_SESSION = array();
            session_destroy();

            require_once "../vistas/sesion_cerrada.php";
            break;

        default:
            header("Location: usuario_controlador.php?accion=login");
            exit;
    }

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
