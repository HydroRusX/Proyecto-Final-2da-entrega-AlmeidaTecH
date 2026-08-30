
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
// OBTENER PREGUNTAS
// ===============================

$sqlPreguntas = "
    SELECT Id_pregunta
    FROM Pregunta
    WHERE Id_Encuesta = ?
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


$resultado =
    $stmtPreguntas->get_result();


$idsPreguntas = [];


while ($pregunta = $resultado->fetch_assoc()) {

    $idsPreguntas[] =
        $pregunta['Id_pregunta'];

}


$stmtPreguntas->close();


// ===============================
// ELIMINAR RESPUESTAS
// ===============================

$sqlRespuesta = "
    DELETE FROM Respuesta
    WHERE Id_pregunta = ?
";


$stmtRespuesta =
    $conexion->prepare(
        $sqlRespuesta
    );


foreach ($idsPreguntas as $id_pregunta) {

    $stmtRespuesta->bind_param(
        "i",
        $id_pregunta
    );

    $stmtRespuesta->execute();

}


$stmtRespuesta->close();


// ===============================
// ELIMINAR PREGUNTAS
// ===============================

$sqlEliminarPreguntas = "
    DELETE FROM Pregunta
    WHERE Id_Encuesta = ?
";


$stmtEliminarPreguntas =
    $conexion->prepare(
        $sqlEliminarPreguntas
    );


$stmtEliminarPreguntas->bind_param(
    "i",
    $id
);


$stmtEliminarPreguntas->execute();


$stmtEliminarPreguntas->close();


// ===============================
// ELIMINAR ENCUESTA
// ===============================

$sqlEncuesta = "
    DELETE FROM Encuesta
    WHERE Id_encuesta = ?
";


$stmtEncuesta =
    $conexion->prepare(
        $sqlEncuesta
    );


$stmtEncuesta->bind_param(
    "i",
    $id
);


if (!$stmtEncuesta->execute()) {

    die(
        "Error al eliminar la encuesta: " .
        $stmtEncuesta->error
    );

}


$stmtEncuesta->close();


$conexion->close();


header("Location: CRUD_encuestas.php");

exit;

?>

