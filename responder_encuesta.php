
<?php

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

$sql = "
    SELECT
        Id_encuesta,
        Titulo,
        Descripcion

    FROM Encuesta

    WHERE Id_encuesta = ?
";


$stmt =
    $conexion->prepare($sql);


$stmt->bind_param(
    "i",
    $id
);


$stmt->execute();


$resultado =
    $stmt->get_result();


if ($resultado->num_rows == 0) {

    die("Error: la encuesta no existe.");

}


$encuesta =
    $resultado->fetch_assoc();


$stmt->close();


// ===============================
// OBTENER PREGUNTAS
// ===============================

$sqlPreguntas = "
    SELECT
        Id_pregunta,
        Texto

    FROM Pregunta

    WHERE Id_Encuesta = ?

    ORDER BY Id_pregunta
";


$stmtPreguntas =
    $conexion->prepare(
        $sqlPreguntas
    );


$stmtPreguntas->bind_param(
    "i",
    $id
);


$stmtPreguntas->execute();


$preguntas =
    $stmtPreguntas->get_result();


if ($preguntas->num_rows == 0) {

    die("Esta encuesta no tiene preguntas.");

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
</header>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($encuesta['Titulo']); ?>
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>


<body>


<div class="container py-5">


    <div class="card shadow">


        <div class="card-header">

            <h1>

                <?php

                echo htmlspecialchars(
                    $encuesta['Titulo']
                );

                ?>

            </h1>


            <p class="mb-0 text-secondary">

                <?php

                echo htmlspecialchars(
                    $encuesta['Descripcion']
                );

                ?>

            </p>

        </div>


        <div class="card-body">


            <form
                action="procesar_respuesta.php"
                method="POST">


                <input
                    type="hidden"
                    name="id_encuesta"
                    value="<?php echo $encuesta['Id_encuesta']; ?>">


                <?php

                $numero = 1;

                ?>


                <?php while ($pregunta = $preguntas->fetch_assoc()): ?>


                    <div class="card mb-4">


                        <div class="card-body">


                            <h5>

                                <?php

                                echo $numero .
                                    ". " .
                                    htmlspecialchars(
                                        $pregunta['Texto']
                                    );

                                ?>

                            </h5>


                            <input
                                type="hidden"
                                name="preguntas[]"
                                value="<?php echo $pregunta['Id_pregunta']; ?>">


                            <div class="mt-3">


                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="respuesta[<?php echo $pregunta['Id_pregunta']; ?>]"
                                        value="1"
                                        required>

                                    <label class="form-check-label">

                                        1 - Muy insatisfecho

                                    </label>

                                </div>


                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="respuesta[<?php echo $pregunta['Id_pregunta']; ?>]"
                                        value="2">

                                    <label class="form-check-label">

                                        2 - Insatisfecho

                                    </label>

                                </div>


                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="respuesta[<?php echo $pregunta['Id_pregunta']; ?>]"
                                        value="3">

                                    <label class="form-check-label">

                                        3 - Neutral

                                    </label>

                                </div>


                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="respuesta[<?php echo $pregunta['Id_pregunta']; ?>]"
                                        value="4">

                                    <label class="form-check-label">

                                        4 - Satisfecho

                                    </label>

                                </div>


                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="respuesta[<?php echo $pregunta['Id_pregunta']; ?>]"
                                        value="5">

                                    <label class="form-check-label">

                                        5 - Muy satisfecho

                                    </label>

                                </div>


                            </div>


                        </div>


                    </div>


                <?php

                $numero++;

                endwhile;

                ?>


                <button
                    type="submit"
                    class="btn btn-primary">

                    Enviar respuestas

                </button>


                <a
                    href="encuestas.php"
                    class="btn btn-secondary">

                    Cancelar

                </a>


            </form>


        </div>


    </div>


</div>


</body>

</html>

<?php

$stmtPreguntas->close();

$conexion->close();

?>

