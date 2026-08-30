
<?php
session_start();
 
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php?error=sesion");
    exit;
}

require_once "conexion.php";

?>

<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" href="../CSS/index.css">
<head>


<header>

   <div class="logo">

        <img
            src="../Imagenes/Clinicas.jpg"
            alt="Logo Clínicas">

    </div>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Funcion en desarrollo</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</header>
</head>


<body>


<div class="container py-5">


    <div class="row justify-content-center">


        <div class="col-md-8 col-lg-6">


            <div class="card shadow text-center">


                <div class="card-body p-5">


                    <div class="mb-4">

                        <div
                            class="display-1">
                            🏗️
                        </div>

                    </div>


                    <h1 class="mb-3">

                        ¡UPS!

                    </h1>


                    <h3 class="mb-4">

                        Esta pagina aun esta en desarrollo

                    </h3>


                    <p class="text-secondary mb-4">

                        Mantenete alerta cuando esta funcion
                        sea implementada.

                    </p>


                    <a
                        href="index.php"
                        class="btn btn-primary">

                        Volver al inicio

                    </a>


                </div>


            </div>


        </div>


    </div>


</div>


</body>

</html>

