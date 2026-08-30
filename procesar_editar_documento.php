
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

$id_documento =
    filter_input(
        INPUT_POST,
        'id_documento',
        FILTER_VALIDATE_INT
    );


$titulo =
    trim($_POST['titulo'] ?? '');


$categorias =
    $_POST['categorias'] ?? [];


// ===============================
// VALIDAR DATOS
// ===============================

if (!$id_documento) {

    die(
        "Error: documento no valido."
    );

}


if ($titulo === '') {

    die(
        "Error: debes ingresar un titulo."
    );

}


if (empty($categorias)) {

    die(
        "Error: debes seleccionar al menos una categoria."
    );

}


// ===============================
// OBTENER ARCHIVO ACTUAL
// ===============================

$sqlArchivo = "
    SELECT Archivo
    FROM Documento
    WHERE Id_documento = ?
";


$stmtArchivo =
    $conexion->prepare(
        $sqlArchivo
    );


if (!$stmtArchivo) {

    die(
        "Error al consultar el archivo actual: " .
        $conexion->error
    );

}


$stmtArchivo->bind_param(
    "i",
    $id_documento
);


$stmtArchivo->execute();


$resultadoArchivo =
    $stmtArchivo->get_result();


if ($resultadoArchivo->num_rows == 0) {

    $stmtArchivo->close();
    $conexion->close();

    die(
        "Error: el documento no existe."
    );

}


$documentoActual =
    $resultadoArchivo->fetch_assoc();


$archivoActual =
    $documentoActual['Archivo'];


$stmtArchivo->close();


// ===============================
// ARCHIVO NUEVO
// ===============================

$nuevaRuta =
    $archivoActual;


$archivoNuevoSubido = false;


if (
    isset($_FILES['archivo']) &&
    $_FILES['archivo']['error'] != UPLOAD_ERR_NO_FILE
) {


    if (
        $_FILES['archivo']['error'] != UPLOAD_ERR_OK
    ) {

        die(
            "Error al subir el nuevo archivo."
        );

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

        die(
            "Error: el formato del archivo no esta permitido."
        );

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
    // NUEVO NOMBRE
    // ===============================

    $nombreArchivo =
        uniqid("doc_") .
        "." .
        $extension;


    $nuevaRuta =
        $carpeta .
        $nombreArchivo;


    // ===============================
    // GUARDAR NUEVO ARCHIVO
    // ===============================

    if (
        !move_uploaded_file(
            $archivo['tmp_name'],
            $nuevaRuta
        )
    ) {

        die(
            "Error: no se pudo guardar el nuevo archivo."
        );

    }


    $archivoNuevoSubido = true;

}


// ===============================
// ACTUALIZAR DOCUMENTO
// ===============================

$sql = "
    UPDATE Documento

    SET
        Titulo = ?,
        Archivo = ?

    WHERE Id_documento = ?
";


$stmt =
    $conexion->prepare($sql);


if (!$stmt) {

    if ($archivoNuevoSubido && file_exists($nuevaRuta)) {
        unlink($nuevaRuta);
    }

    die(
        "Error al preparar la actualizacion: " .
        $conexion->error
    );

}


$stmt->bind_param(
    "ssi",
    $titulo,
    $nuevaRuta,
    $id_documento
);


if (!$stmt->execute()) {

    if ($archivoNuevoSubido && file_exists($nuevaRuta)) {
        unlink($nuevaRuta);
    }

    $stmt->close();
    $conexion->close();

    die(
        "Error al actualizar el documento: " .
        $stmt->error
    );

}


$stmt->close();


// ===============================
// ACTUALIZAR CATEGORIAS
// ===============================

$sqlEliminarCategorias = "
    DELETE FROM Documento_categoria
    WHERE Id_documento = ?
";


$stmtEliminar =
    $conexion->prepare(
        $sqlEliminarCategorias
    );


if (!$stmtEliminar) {

    die(
        "Error al preparar las categorias: " .
        $conexion->error
    );

}


$stmtEliminar->bind_param(
    "i",
    $id_documento
);


if (!$stmtEliminar->execute()) {

    $stmtEliminar->close();
    $conexion->close();

    die(
        "Error al actualizar las categorias: " .
        $stmtEliminar->error
    );

}


$stmtEliminar->close();


// ===============================
// INSERTAR NUEVAS CATEGORIAS
// ===============================

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


if (!$stmtCategoria) {

    die(
        "Error al preparar las nuevas categorias: " .
        $conexion->error
    );

}


foreach ($categorias as $id_categoria) {


    $id_categoria =
        intval($id_categoria);


    $stmtCategoria->bind_param(
        "ii",
        $id_documento,
        $id_categoria
    );


    if (!$stmtCategoria->execute()) {

        $stmtCategoria->close();
        $conexion->close();

        die(
            "Error al guardar una categoria: " .
            $stmtCategoria->error
        );

    }

}


$stmtCategoria->close();


// ===============================
// ELIMINAR ARCHIVO ANTERIOR
// ===============================

if (
    $archivoNuevoSubido &&
    !empty($archivoActual) &&
    file_exists($archivoActual)
) {

    unlink($archivoActual);

}


// ===============================
// FINALIZAR
// ===============================

$conexion->close();


header("Location: CRUD.php");

exit;

?>

