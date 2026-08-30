<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php?error=sesion");
    exit;
}


require_once "conexion.php";


$sql = "
    SELECT
        Id_categoria,
        Nombre_categoria

    FROM Categoria

    ORDER BY Nombre_categoria
";

$categorias =
    $conexion->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Subir documento</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

<div class="container py-5">

    <div class="card shadow">

        <div class="card-header">

            <h2>
                Subir documento
            </h2>

        </div>


        <div class="card-body">

            <form
                action="procesar_documento.php"
                method="POST"
                enctype="multipart/form-data">


                <!-- TITULO -->

                <div class="mb-3">

                    <label class="form-label">

                        Titulo

                    </label>

                    <input
                        type="text"
                        name="titulo"
                        class="form-control"
                        required>

                </div>


                <!-- ARCHIVO -->

                <div class="mb-3">

                    <label class="form-label">

                        Archivo

                    </label>

                    <input
                        type="file"
                        name="archivo"
                        class="form-control"
                        required>

                    <div class="form-text">

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
                        size="5">


                        <?php while (
                            $categoria =
                            $categorias->fetch_assoc()
                        ): ?>

                            <option
                                value="<?php echo $categoria['Id_categoria']; ?>">

                                <?php

                                echo htmlspecialchars(
                                    $categoria[
                                        'Nombre_categoria'
                                    ]
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


                <!-- BOTONES -->

                <button
                    type="submit"
                    class="btn btn-primary">

                    Subir documento

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