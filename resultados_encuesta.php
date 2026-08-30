
<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php?error=sesion");
    exit;
}

require_once "conexion.php";


$id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);


if (!$id) {
    die("Error: encuesta no valida.");
}


// ===============================
// OBTENER ENCUESTA
// ===============================

$sqlEncuesta = "
    SELECT
        Id_encuesta,
        Titulo,
        Descripcion

    FROM Encuesta

    WHERE Id_encuesta = ?
";


$stmt =
    $conexion->prepare(
        $sqlEncuesta
    );


$stmt->bind_param(
    "i",
    $id
);


$stmt->execute();


$resultadoEncuesta =
    $stmt->get_result();


if ($resultadoEncuesta->num_rows == 0) {

    die(
        "Error: la encuesta no existe."
    );

}


$encuesta =
    $resultadoEncuesta->fetch_assoc();


$stmt->close();


// ===============================
// OBTENER RESULTADOS
// ===============================

$sqlResultados = "
    SELECT
        p.Id_pregunta,
        p.Texto,
        COUNT(r.Id_Respuesta) AS cantidad_respuestas,
        AVG(r.Valor_satisfaccion) AS promedio

    FROM Pregunta p

    LEFT JOIN Respuesta r
        ON p.Id_pregunta = r.Id_pregunta

    WHERE p.Id_Encuesta = ?

    GROUP BY
        p.Id_pregunta,
        p.Texto

    ORDER BY p.Id_pregunta
";


$stmtResultados =
    $conexion->prepare(
        $sqlResultados
    );


$stmtResultados->bind_param(
    "i",
    $id
);


$stmtResultados->execute();


$resultados =
    $stmtResultados->get_result();

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

    <title>Resultados de encuesta</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>


<body>


<div class="container py-5">


    <div class="mb-4">

        <h1>

            Resultados de encuesta

        </h1>


        <h3>

            <?php

            echo htmlspecialchars(
                $encuesta['Titulo']
            );

            ?>

        </h3>


        <p class="text-secondary">

            <?php

            echo htmlspecialchars(
                $encuesta['Descripcion']
            );

            ?>

        </p>

    </div>


    <div class="table-responsive">


        <table class="table table-bordered table-hover">


            <thead class="table-dark">

                <tr>

                    <th>Pregunta</th>

                    <th>Respuestas</th>

                    <th>Promedio</th>

                </tr>

            </thead>


            <tbody>


            <?php

            $totalRespuestas = 0;

            $sumaPromedios = 0;

            $cantidadPreguntas = 0;

            ?>


            <?php while ($resultado = $resultados->fetch_assoc()): ?>


                <tr>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $resultado['Texto']
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo $resultado[
                            'cantidad_respuestas'
                        ];

                        ?>

                    </td>


                    <td>


                        <?php

                        if (
                            $resultado['promedio'] !== null
                        ) {

                            echo number_format(
                                $resultado['promedio'],
                                2
                            );

                            echo " / 5";

                            $sumaPromedios +=
                                $resultado['promedio'];

                            $cantidadPreguntas++;

                        } else {

                            echo "Sin respuestas";

                        }

                        ?>


                    </td>


                </tr>


            <?php endwhile; ?>


            </tbody>


        </table>


    </div>


    <?php

    if ($cantidadPreguntas > 0) {

        $promedioGeneral =
            $sumaPromedios /
            $cantidadPreguntas;

    } else {

        $promedioGeneral = 0;

    }

    ?>


    <div class="card shadow mt-4">


        <div class="card-body text-center">


            <h4>
                Promedio general de satisfaccion
            </h4>


            <?php if ($cantidadPreguntas > 0): ?>

                <div class="display-5">

                    <?php

                    echo number_format(
                        $promedioGeneral,
                        2
                    );

                    ?>

                    / 5

                </div>


            <?php else: ?>

                <p class="text-secondary">

                    Todavia no hay respuestas.

                </p>

            <?php endif; ?>


        </div>


    </div>


    <div class="mt-4">


        <a
            href="CRUD_encuestas.php"
            class="btn btn-secondary">

            Volver a encuestas

        </a>


    </div>


</div>


</body>

</html>


<?php

$stmtResultados->close();

$conexion->close();

?>

