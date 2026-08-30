
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
    SET Activo = 1
    WHERE Id_documento = ?
";


$stmt = $conexion->prepare($sql);


if (!$stmt) {
    die(
        "Error al preparar la reactivacion: " .
        $conexion->error
    );
}


$stmt->bind_param(
    "i",
    $id
);


if (!$stmt->execute()) {
    die(
        "Error al reactivar el documento: " .
        $stmt->error
    );
}


$stmt->close();

$conexion->close();


header("Location: CRUD.php");

exit;

?>

