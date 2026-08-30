
<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php?error=sesion");
    exit;
}

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


$titulo =
    trim($_POST['titulo'] ?? '');


$descripcion =
    trim($_POST['descripcion'] ?? '');


$segmentada =
    intval(
        $_POST['segmentada'] ?? 0
    );


$id_preguntas =
    $_POST['id_preguntas'] ?? [];


$preguntas =
    $_POST['preguntas'] ?? [];


// ===============================
// VALIDACIONES
// ===============================

if (!$id_encuesta) {
    die("Error: encuesta no valida.");
}


if ($titulo === '') {
    die("Error: debes ingresar un titulo.");
}


if ($descripcion === '') {
    die("Error: debes ingresar una descripcion.");
}


if (count($preguntas) == 0) {
    die("Error: debes tener al menos una pregunta.");
}


// ===============================
// ACTUALIZAR ENCUESTA
// ===============================

$sql = "
    UPDATE Encuesta

    SET
        Titulo = ?,
        Descripcion = ?,
        Segmentada = ?

    WHERE Id_encuesta = ?
";


$stmt =
    $conexion->prepare($sql);


if (!$stmt) {
    die(
        "Error al preparar la encuesta: " .
        $conexion->error
    );
}


$stmt->bind_param(
    "ssii",
    $titulo,
    $descripcion,
    $segmentada,
    $id_encuesta
);


if (!$stmt->execute()) {
    die(
        "Error al actualizar la encuesta: " .
        $stmt->error
    );
}


$stmt->close();


// ===============================
// ACTUALIZAR / CREAR PREGUNTAS
// ===============================

$sqlActualizar = "
    UPDATE Pregunta

    SET Texto = ?

    WHERE Id_pregunta = ?
    AND Id_Encuesta = ?
";


$stmtActualizar =
    $conexion->prepare(
        $sqlActualizar
    );


$sqlNueva = "
    INSERT INTO Pregunta
    (
        Texto,
        Id_Encuesta
    )

    VALUES (?, ?)
";


$stmtNueva =
    $conexion->prepare(
        $sqlNueva
    );


for (
    $i = 0;
    $i < count($preguntas);
    $i++
) {


    $texto =
        trim($preguntas[$i]);


    if ($texto === '') {
        continue;
    }


    $id_pregunta =
        isset($id_preguntas[$i])
        ? intval($id_preguntas[$i])
        : 0;


    if ($id_pregunta > 0) {


        $stmtActualizar->bind_param(
            "sii",
            $texto,
            $id_pregunta,
            $id_encuesta
        );


        $stmtActualizar->execute();


    } else {


        $stmtNueva->bind_param(
            "si",
            $texto,
            $id_encuesta
        );


        $stmtNueva->execute();

    }

}


$stmtActualizar->close();
$stmtNueva->close();


$conexion->close();


header("Location: CRUD_encuestas.php");

exit;

?>

