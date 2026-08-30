
<?php

session_start();

// Eliminar todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Sesión cerrada</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

    <div class="container">

        <div class="text-center mt-5">

            <div class="alert alert-success">

                <h4 class="alert-heading">
                    Sesión cerrada correctamente
                </h4>

                <p class="mb-3">
                    Tu sesión se cerró correctamente.
                </p>

                <a
                    href="index.php"
                    class="btn btn-primary">

                    Volver al inicio

                </a>

            </div>

        </div>

    </div>

</body>

</html>
