
<?php

require_once "conexion.php";


// ===============================
// OBTENER ENCUESTAS
// ===============================

$sql = "
    SELECT
        Id_encuesta,
        Titulo,
        Descripcion,
        Segmentada
    FROM Encuesta
    ORDER BY Id_encuesta DESC
";

$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error al obtener las encuestas: " . $conexion->error);
}

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

    <title>Encuestas</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>


<body>


<div class="container py-5">


    <!-- ===============================
         TITULO
    ================================ -->

    <div class="mb-4">

        <h1>
            Encuestas
        </h1>

        <p class="text-secondary">
            Encuestas disponibles para responder
        </p>

    </div>


    <!-- ===============================
         LISTADO DE ENCUESTAS
    ================================ -->

    <?php if ($resultado->num_rows > 0): ?>


        <div class="row">


            <?php while ($encuesta = $resultado->fetch_assoc()): ?>


                <div class="col-md-6 col-lg-4 mb-4">


                    <div class="card shadow h-100">


                        <div class="card-body">


                            <h5 class="card-title">

                                <?php

                                echo htmlspecialchars(
                                    $encuesta['Titulo']
                                );

                                ?>

                            </h5>


                            <p class="card-text">

                                <?php

                                echo htmlspecialchars(
                                    $encuesta['Descripcion']
                                );

                                ?>

                            </p>


                            <!-- ===============================
                                 TIPO DE ENCUESTA
                            ================================ -->

                            <?php if ($encuesta['Segmentada'] == 1): ?>

                                <span class="badge bg-warning text-dark mb-3">

                                    Encuesta segmentada

                                </span>

                            <?php else: ?>

                                <span class="badge bg-success mb-3">

                                    Encuesta general

                                </span>

                            <?php endif; ?>


                            <br>


                            <!-- ===============================
                                 BOTON RESPONDER
                            ================================ -->

                            <a
                                href="responder_encuesta.php?id=<?php echo $encuesta['Id_encuesta']; ?>"
                                class="btn btn-primary">

                                Responder encuesta

                            </a>


                        </div>


                    </div>


                </div>


            <?php endwhile; ?>


        </div>


    <?php else: ?>


        <!-- ===============================
             NO HAY ENCUESTAS
        ================================ -->

        <div class="alert alert-info">

            No hay encuestas disponibles en este momento.

        </div>


    <?php endif; ?>


    <!-- ===============================
         BOTON VOLVER AL INICIO
    ================================ -->

    <a
        href="index.php"
        class="btn btn-secondary mt-4">

        ← Volver al inicio

    </a>


</div>


</body>

</html>


<?php

$conexion->close();

?>