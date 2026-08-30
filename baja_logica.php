
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
    die("Error: documento no valido.");
}


$sql = "
    UPDATE Documento
    SET Activo = 0
    WHERE Id_documento = ?
";


$stmt = $conexion->prepare($sql);


if (!$stmt) {
    die(
        "Error al preparar la baja logica: " .
        $conexion->error
    );
}


$stmt->bind_param(
    "i",
    $id
);


if (!$stmt->execute()) {
    die(
        "Error al realizar la baja logica: " .
        $stmt->error
    );
}


$stmt->close();

$conexion->close();


header("Location: CRUD.php");

exit;

?>

