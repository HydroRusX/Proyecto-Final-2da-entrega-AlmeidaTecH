<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php?error=sesion");
    exit;
}

require_once "conexion.php";


// ===============================
// DATOS DE LA ENCUESTA
// ===============================

$titulo = trim($_POST['titulo'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$segmentada = isset($_POST['segmentada']) ? intval($_POST['segmentada']) : 0;

$preguntas = $_POST['preguntas'] ?? [];


// ===============================
// VALIDAR TITULO
// ===============================

if ($titulo == '') {
    die("El titulo de la encuesta es obligatorio.");
}


// ===============================
// VALIDAR PREGUNTAS
// ===============================

$preguntasValidas = [];

foreach ($preguntas as $pregunta) {

    $pregunta = trim($pregunta);

    if ($pregunta != '') {
        $preguntasValidas[] = $pregunta;
    }
}

if (count($preguntasValidas) == 0) {
    die("Debes agregar al menos una pregunta.");
}


// ===============================
// CREAR ENCUESTA
// ===============================

$sql = "
    INSERT INTO Encuesta
    (
        Titulo,
        Descripcion,
        Segmentada
    )
    VALUES (?, ?, ?)
";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error al preparar la encuesta: " . $conexion->error);
}

$stmt->bind_param(
    "ssi",
    $titulo,
    $descripcion,
    $segmentada
);

if (!$stmt->execute()) {
    die("Error al crear la encuesta: " . $stmt->error);
}


// ===============================
// OBTENER ID DE LA ENCUESTA
// ===============================

$id_encuesta = $stmt->insert_id;

$stmt->close();


// ===============================
// CREAR PREGUNTAS
// ===============================

$sqlPregunta = "
    INSERT INTO Pregunta
    (
        Texto,
        Id_Encuesta
    )
    VALUES (?, ?)
";

$stmtPregunta = $conexion->prepare($sqlPregunta);

if (!$stmtPregunta) {
    die("Error al preparar las preguntas: " . $conexion->error);
}


foreach ($preguntasValidas as $textoPregunta) {

    $stmtPregunta->bind_param(
        "si",
        $textoPregunta,
        $id_encuesta
    );

    if (!$stmtPregunta->execute()) {

        $stmtPregunta->close();
        $conexion->close();

        die("Error al guardar una pregunta: " . $stmtPregunta->error);
    }
}


$stmtPregunta->close();

$conexion->close();


// ===============================
// VOLVER
// ===============================

header("Location: CRUD_encuestas.php");

exit;

?>