
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

$sql = "
    SELECT
        Id_encuesta,
        Titulo,
        Descripcion,
        Segmentada

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

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Editar encuesta</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>


<body>


<div class="container py-5">


    <div class="card shadow">


        <div class="card-header">

            <h2>
                Editar encuesta
            </h2>

        </div>


        <div class="card-body">


            <form
                action="procesar_editar_encuesta.php"
                method="POST">


                <input
                    type="hidden"
                    name="id_encuesta"
                    value="<?php echo $encuesta['Id_encuesta']; ?>">


                <!-- TITULO -->

                <div class="mb-3">

                    <label class="form-label">
                        Titulo
                    </label>

                    <input
                        type="text"
                        name="titulo"
                        class="form-control"
                        value="<?php echo htmlspecialchars($encuesta['Titulo']); ?>"
                        required>

                </div>


                <!-- DESCRIPCION -->

                <div class="mb-3">

                    <label class="form-label">
                        Descripcion
                    </label>

                    <textarea
                        name="descripcion"
                        class="form-control"
                        rows="4"
                        required><?php echo htmlspecialchars($encuesta['Descripcion']); ?></textarea>

                </div>


                <!-- SEGMENTADA -->

                <div class="mb-3">

                    <label class="form-label">
                        ¿Es una encuesta segmentada?
                    </label>


                    <select
                        name="segmentada"
                        class="form-select">

                        <option
                            value="0"
                            <?php
                            if ($encuesta['Segmentada'] == 0) {
                                echo "selected";
                            }
                            ?>>

                            No

                        </option>


                        <option
                            value="1"
                            <?php
                            if ($encuesta['Segmentada'] == 1) {
                                echo "selected";
                            }
                            ?>>

                            Si

                        </option>

                    </select>

                </div>


                <!-- PREGUNTAS -->

                <div class="mb-3">

                    <label class="form-label">
                        Preguntas
                    </label>


                    <div id="preguntas">


                        <?php while ($pregunta = $preguntas->fetch_assoc()): ?>


                            <div class="input-group mb-2">


                                <input
                                    type="hidden"
                                    name="id_preguntas[]"
                                    value="<?php echo $pregunta['Id_pregunta']; ?>">


                                <input
                                    type="text"
                                    name="preguntas[]"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($pregunta['Texto']); ?>"
                                    required>


                            </div>


                        <?php endwhile; ?>


                    </div>


                    <button
                        type="button"
                        class="btn btn-secondary btn-sm"
                        onclick="agregarPregunta()">

                        + Agregar pregunta

                    </button>

                </div>


                <button
                    type="submit"
                    class="btn btn-primary">

                    Guardar cambios

                </button>


                <a
                    href="CRUD_encuestas.php"
                    class="btn btn-secondary">

                    Cancelar

                </a>


            </form>


        </div>


    </div>


</div>


<script>

function agregarPregunta() {

    const contenedor =
        document.getElementById("preguntas");


    const div =
        document.createElement("div");


    div.className =
        "input-group mb-2";


    div.innerHTML = `
        <input
            type="hidden"
            name="id_preguntas[]"
            value="0">

        <input
            type="text"
            name="preguntas[]"
            class="form-control"
            placeholder="Nueva pregunta"
            required>
    `;


    contenedor.appendChild(div);

}

</script>


</body>

</html>

<?php

$stmtPreguntas->close();
$conexion->close();

?>

