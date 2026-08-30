
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
// OBTENER DOCUMENTO
// ===============================

$sql = "
    SELECT
        Id_documento,
        Titulo,
        Archivo,
        Activo

    FROM Documento

    WHERE Id_documento = ?
";


$stmt =
    $conexion->prepare($sql);


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


$resultado =
    $stmt->get_result();


if ($resultado->num_rows == 0) {

    $stmt->close();
    $conexion->close();

    die("Error: el documento no existe.");

}


$documento =
    $resultado->fetch_assoc();


$stmt->close();


// ===============================
// OBTENER CATEGORIAS
// ===============================

$sqlCategorias = "
    SELECT
        Id_categoria,
        Nombre_categoria

    FROM Categoria

    ORDER BY Nombre_categoria
";


$categorias =
    $conexion->query($sqlCategorias);


if (!$categorias) {
    die(
        "Error al obtener las categorias: " .
        $conexion->error
    );
}


// ===============================
// CATEGORIAS DEL DOCUMENTO
// ===============================

$sqlCategoriasDocumento = "
    SELECT
        Id_categoria

    FROM Documento_categoria

    WHERE Id_documento = ?
";


$stmtCatDoc =
    $conexion->prepare(
        $sqlCategoriasDocumento
    );


$stmtCatDoc->bind_param(
    "i",
    $id
);


$stmtCatDoc->execute();


$resultadoCatDoc =
    $stmtCatDoc->get_result();


$categoriasSeleccionadas = [];


while (
    $cat =
    $resultadoCatDoc->fetch_assoc()
) {

    $categoriasSeleccionadas[] =
        $cat['Id_categoria'];

}


$stmtCatDoc->close();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Editar documento</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>


<body>


<div class="container py-5">


    <div class="card shadow">


        <div class="card-header">

            <h2>
                Editar documento
            </h2>

        </div>


        <div class="card-body">


            <form
                action="procesar_editar_documento.php"
                method="POST"
                enctype="multipart/form-data">


                <!-- ID -->

                <input
                    type="hidden"
                    name="id_documento"
                    value="<?php echo $documento['Id_documento']; ?>">


                <!-- TITULO -->

                <div class="mb-3">

                    <label class="form-label">

                        Titulo

                    </label>


                    <input
                        type="text"
                        name="titulo"
                        class="form-control"
                        value="<?php echo htmlspecialchars($documento['Titulo']); ?>"
                        required>

                </div>


                <!-- ARCHIVO ACTUAL -->

                <div class="mb-3">

                    <label class="form-label">

                        Archivo actual

                    </label>


                    <div>

                        <a
                            href="<?php echo htmlspecialchars($documento['Archivo']); ?>"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary">

                            Ver archivo actual

                        </a>

                    </div>

                </div>


                <!-- NUEVO ARCHIVO -->

                <div class="mb-3">

                    <label class="form-label">

                        Reemplazar archivo

                    </label>


                    <input
                        type="file"
                        name="archivo"
                        class="form-control">


                    <div class="form-text">

                        Deja este campo vacio si queres mantener el archivo actual.

                        Formatos permitidos:
                        PDF, DOC, DOCX, XLS, XLSX, PPT y PPTX.

                    </div>

                </div>


                <!-- CATEGORIAS -->

                <div class="mb-3">

                    <label class="form-label">

                        Categorias

                    </label>


                    <select
                        name="categorias[]"
                        class="form-select"
                        multiple
                        size="5"
                        required>


                        <?php while (
                            $categoria =
                            $categorias->fetch_assoc()
                        ): ?>


                            <option
                                value="<?php echo $categoria['Id_categoria']; ?>"
                                <?php

                                if (
                                    in_array(
                                        $categoria['Id_categoria'],
                                        $categoriasSeleccionadas
                                    )
                                ) {

                                    echo "selected";

                                }

                                ?>>

                                <?php

                                echo htmlspecialchars(
                                    $categoria['Nombre_categoria']
                                );

                                ?>

                            </option>


                        <?php endwhile; ?>


                    </select>


                    <div class="form-text">

                        Podes seleccionar varias categorias
                        manteniendo presionado Ctrl.

                    </div>

                </div>


                <!-- ESTADO -->

                <div class="mb-3">

                    <label class="form-label">

                        Estado actual

                    </label>


                    <?php if ($documento['Activo'] == 1): ?>

                        <span class="badge bg-success">
                            Activo
                        </span>

                    <?php else: ?>

                        <span class="badge bg-secondary">
                            Baja logica
                        </span>

                    <?php endif; ?>

                </div>


                <!-- BOTONES -->

                <button
                    type="submit"
                    class="btn btn-primary">

                    Guardar cambios

                </button>


                <a
                    href="CRUD.php"
                    class="btn btn-secondary">

                    Cancelar

                </a>


            </form>


        </div>

    </div>

</div>


</body>

</html>

<?php

$conexion->close();

?>
