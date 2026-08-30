
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


// ===============================
// OBTENER ARCHIVO
// ===============================

$sql = "
    SELECT Archivo
    FROM Documento
    WHERE Id_documento = ?
";


$stmt = $conexion->prepare($sql);


if (!$stmt) {
    die(
        "Error al preparar la consulta: " .
        $conexion->error
    );
}


$stmt->bind_param(
    "i",
    $id
);


$stmt->execute();


$resultado = $stmt->get_result();


if ($resultado->num_rows == 0) {

    $stmt->close();
    $conexion->close();

    die("Error: el documento no existe.");

}


$documento =
    $resultado->fetch_assoc();


$archivo =
    $documento['Archivo'];


$stmt->close();


// ===============================
// ELIMINAR CATEGORIAS
// ===============================

$sqlCategorias = "
    DELETE FROM Documento_categoria
    WHERE Id_documento = ?
";


$stmtCat =
    $conexion->prepare(
        $sqlCategorias
    );


if (!$stmtCat) {
    die(
        "Error al preparar la eliminacion de categorias: " .
        $conexion->error
    );
}


$stmtCat->bind_param(
    "i",
    $id
);


if (!$stmtCat->execute()) {

    $stmtCat->close();
    $conexion->close();

    die(
        "Error al eliminar las categorias: " .
        $stmtCat->error
    );

}


$stmtCat->close();


// ===============================
// ELIMINAR DOCUMENTO
// ===============================

$sqlDocumento = "
    DELETE FROM Documento
    WHERE Id_documento = ?
";


$stmtDoc =
    $conexion->prepare(
        $sqlDocumento
    );


if (!$stmtDoc) {
    die(
        "Error al preparar la eliminacion del documento: " .
        $conexion->error
    );
}


$stmtDoc->bind_param(
    "i",
    $id
);


if (!$stmtDoc->execute()) {

    $stmtDoc->close();
    $conexion->close();

    die(
        "Error al eliminar el documento: " .
        $stmtDoc->error
    );

}


$stmtDoc->close();


// ===============================
// ELIMINAR ARCHIVO FISICO
// ===============================

if (!empty($archivo) && file_exists($archivo)) {

    unlink($archivo);

}


$conexion->close();


// ===============================
// VOLVER
// ===============================

header("Location: CRUD.php");

exit;

?>

