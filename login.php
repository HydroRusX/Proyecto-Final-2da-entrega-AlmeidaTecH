
<?php

session_start();

// Si ya inició sesión, lo mando al inicio
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once "conexion.php";

    $email = trim($_POST["email"]);
    $contrasena = $_POST["contrasena"];

    // Buscar usuario por email
    $sql = "SELECT Id_usuario, Nombre, Apellido, Email, Contraseña, Tipo_usuario
            FROM Usuario
            WHERE Email = ?";

    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        die("Error en la consulta: " . $conexion->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {

        $usuario = $resultado->fetch_assoc();

        /*
         * POR AHORA:
         * Comparamos la contraseña directamente.
         *
         * Más adelante podemos cambiar esto a password_hash()
         * y password_verify() para hacerlo más seguro.
         */

        if ($contrasena === $usuario["Contraseña"]) {

            // Crear sesión
            $_SESSION["usuario"] = $usuario["Nombre"];
            $_SESSION["id_usuario"] = $usuario["Id_usuario"];
            $_SESSION["tipo_usuario"] = $usuario["Tipo_usuario"];
            $_SESSION["email"] = $usuario["Email"];

            // Volver al inicio
            header("Location: index.php");
            exit;

        } else {

            $error = "El email o la contraseña son incorrectos.";

        }

    } else {

        $error = "El email o la contraseña son incorrectos.";

    }

    $stmt->close();
    $conexion->close();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión</title>

<!-- Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
 
          <link rel="stylesheet" href="../CSS/login.css">


</head>

<body>

<div class="login-container">

    <div class="login-card">

        <!-- LOGO -->

        <div class="logo">
            <img src="../Imagenes/Clinicas.jpg" alt="Logo Clínicas">
        </div>


        <!-- TÍTULO -->

        <div class="titulo">

            <h1>Iniciar sesión</h1>

            <p>Sistema hospitalario</p>

        </div>


        <!-- ERROR -->

        <?php if ($error != ""): ?>

            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php endif; ?>


        <!-- FORMULARIO -->

        <form method="POST" action="login.php">

            <!-- EMAIL -->

            <div class="mb-3">

                <label for="email" class="form-label">
                    Email
                </label>

                <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    placeholder="Ingrese su email"
                    required
                >

            </div>


            <!-- CONTRASEÑA -->

            <div class="mb-3">

                <label for="contrasena" class="form-label">
                    Contraseña
                </label>

                <input
                    type="password"
                    class="form-control"
                    id="contrasena"
                    name="contrasena"
                    placeholder="Ingrese su contraseña"
                    required
                >

            </div>


            <!-- BOTÓN -->

            <div class="d-grid">

                <button
                    type="submit"
                    class="btn btn-primary">
                    Iniciar sesión
                </button>

            </div>

        </form>


        <!-- VOLVER -->

        <div class="text-center mt-3">

            <a href="index.php"
               class="text-decoration-none">
                Volver al inicio
            </a>

        </div>

    </div>

</div>

</body>

</html>

