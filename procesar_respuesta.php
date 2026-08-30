
<?php

require_once "conexion.php";


// ===============================
// DATOS
// ===============================

$id_encuesta =
    filter_input(
        INPUT_POST,
        'id_encuesta',
        FILTER_VALIDATE_INT
    );


$respuestas =
    $_POST['respuesta'] ?? [];


// ===============================
// VALIDAR ENCUESTA
// ===============================

if (!$id_encuesta) {

    die(
        "Error: encuesta no valida."
    );

}


if (count($respuestas) == 0) {

    die(
        "Error: no se recibieron respuestas."
    );

}


// ===============================
// COMPROBAR PREGUNTAS
// ===============================

$sqlPreguntas = "
    SELECT
        Id_pregunta

    FROM Pregunta

    WHERE Id_Encuesta = ?
";


$stmtPreguntas =
    $conexion->prepare(
        $sqlPreguntas
    );


$stmtPreguntas->bind_param(
    "i",
    $id_encuesta
);


$stmtPreguntas->execute();


$resultado =
    $stmtPreguntas->get_result();


$preguntasValidas = [];


while ($pregunta = $resultado->fetch_assoc()) {

    $preguntasValidas[] =
        $pregunta['Id_pregunta'];

}


$stmtPreguntas->close();


if (count($preguntasValidas) == 0) {

    die(
        "Error: la encuesta no tiene preguntas."
    );

}


// ===============================
// INSERTAR RESPUESTAS
// ===============================

$sql = "
    INSERT INTO Respuesta
    (
        Fecha_respuesta,
        Valor_satisfaccion,
        Id_pregunta
    )

    VALUES
    (
        NOW(),
        ?,
        ?
    )
";


$stmt =
    $conexion->prepare($sql);


if (!$stmt) {

    die(
        "Error al preparar las respuestas: " .
        $conexion->error
    );

}


foreach ($preguntasValidas as $id_pregunta) {


    if (!isset($respuestas[$id_pregunta])) {

        die(
            "Error: debes responder todas las preguntas."
        );

    }


    $valor =
        intval(
            $respuestas[$id_pregunta]
        );


    if ($valor < 1 || $valor > 5) {

        die(
            "Error: valor de satisfaccion no valido."
        );

    }


    $stmt->bind_param(
        "ii",
        $valor,
        $id_pregunta
    );


    if (!$stmt->execute()) {

        die(
            "Error al guardar la respuesta: " .
            $stmt->error
        );

    }

}


$stmt->close();

$conexion->close();


// ===============================
// FINALIZAR
// ===============================

header(
    "Location: respuesta_enviada.php"
);

exit;

?>

