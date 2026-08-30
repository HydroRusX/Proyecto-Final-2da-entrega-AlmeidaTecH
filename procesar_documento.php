
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

$titulo =
    trim($_POST['titulo']);

$id_usuario =
    $_SESSION['id_usuario'];

$categorias =
    $_POST['categorias'] ?? [];


// ===============================
// COMPROBAR ARCHIVO
// ===============================

if (
    !isset($_FILES['archivo']) ||
    $_FILES['archivo']['error'] != UPLOAD_ERR_OK
) {

    die("Error al subir el archivo.");

}


$archivo =
    $_FILES['archivo'];


// ===============================
// EXTENSION
// ===============================

$extension =
    strtolower(
        pathinfo(
            $archivo['name'],
            PATHINFO_EXTENSION
        )
    );


// ===============================
// FORMATOS PERMITIDOS
// ===============================

$permitidos = [
    'pdf',
    'doc',
    'docx',
    'xls',
    'xlsx',
    'ppt',
    'pptx'
];


if (
    !in_array(
        $extension,
        $permitidos
    )
) {

    die("Tipo de archivo no permitido.");

}


// ===============================
// CARPETA
// ===============================

$carpeta =
    "documentos/";


if (!is_dir($carpeta)) {

    mkdir(
        $carpeta,
        0777,
        true
    );

}


// ===============================
// NOMBRE UNICO
// ===============================

$nombreArchivo =
    uniqid("doc_") .
    "." .
    $extension;


$ruta =
    $carpeta .
    $nombreArchivo;


// ===============================
// GUARDAR ARCHIVO
// ===============================

if (
    !move_uploaded_file(
        $archivo['tmp_name'],
        $ruta
    )
) {

    die(
        "No se pudo guardar el archivo."
    );

}


// ===============================
// GUARDAR EN MYSQL
// ===============================

$sql = "
    INSERT INTO Documento
    (
        Titulo,
        Archivo,
        Fecha_Carga,
        Id_usuario
    )

    VALUES
    (
        ?,
        ?,
        NOW(),
        ?
    )
";


$stmt =
    $conexion->prepare($sql);


$stmt->bind_param(
    "ssi",
    $titulo,
    $ruta,
    $id_usuario
);


if (!$stmt->execute()) {

    unlink($ruta);

    die(
        "Error al guardar el documento: " .
        $stmt->error
    );

}


$id_documento =
    $stmt->insert_id;


$stmt->close();


// ===============================
// GUARDAR CATEGORIAS
// ===============================

if (count($categorias) > 0) {

    $sqlCategoria = "
        INSERT INTO Documento_categoria
        (
            Id_documento,
            Id_categoria
        )

        VALUES (?, ?)
    ";


    $stmtCategoria =
        $conexion->prepare(
            $sqlCategoria
        );


    foreach ($categorias as $id_categoria) {

        $id_categoria =
            intval($id_categoria);


        $stmtCategoria->bind_param(
            "ii",
            $id_documento,
            $id_categoria
        );


        $stmtCategoria->execute();

    }


    $stmtCategoria->close();

}


$conexion->close();


// ===============================
// VOLVER
// ===============================

header(
    "Location: CRUD.php"
);

exit;

?>

