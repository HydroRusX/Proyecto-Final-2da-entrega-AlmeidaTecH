<?php

session_start();



require_once "conexion.php";


// ===============================
// OBTENER DOCUMENTOS ACTIVOS
// ===============================

$sql = "
    SELECT
        d.Id_documento,
        d.Titulo,
        d.Archivo,
        d.Fecha_Carga,
        u.Nombre,
        u.Apellido

    FROM Documento d

    LEFT JOIN Usuario u
        ON d.Id_usuario = u.Id_usuario

    WHERE d.Activo = 1

    ORDER BY d.Id_documento DESC
";


$resultado =
    $conexion->query($sql);


if (!$resultado) {

    die(
        "Error al obtener los documentos: " .
        $conexion->error
    );

}

?>

<!DOCTYPE html>
<html lang="es">











<link rel="stylesheet" href="../CSS/index.css">
<head>


<header>

   <div class="logo">

        <img
            src="../Imagenes/Clinicas.jpg"
            alt="Logo Clínicas">

    </div>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Repositorio de documentos</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>


<body>


<div class="container-fluid py-4">


    <!-- TITULO -->

    <div class="mb-4">

        <h1>
            Repositorio de documentos
        </h1>

        <p class="text-secondary">
            Documentos disponibles en el sistema hospitalario
        </p>

    </div>


    <!-- TABLA -->

    <div class="table-responsive">

        <table class="table table-bordered table-hover align-middle">


            <thead class="table-dark">

                <tr>

                    <th>ID</th>

                    <th>Titulo</th>

                    <th>Archivo</th>

                    <th>Fecha de carga</th>

                    <th>Usuario</th>

                    <th>Categorias</th>

                </tr>

            </thead>


            <tbody>


            <?php if ($resultado->num_rows > 0): ?>


                <?php while ($documento = $resultado->fetch_assoc()): ?>


                    <tr>


                        <!-- ID -->

                        <td>

                            <?php
                            echo $documento['Id_documento'];
                            ?>

                        </td>


                        <!-- TITULO -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $documento['Titulo']
                            );

                            ?>

                        </td>


                        <!-- ARCHIVO -->

                        <td>

                            <a
                                href="<?php echo htmlspecialchars($documento['Archivo']); ?>"
                                target="_blank"
                                class="btn btn-sm btn-primary">

                                Ver documento

                            </a>

                        </td>


                        <!-- FECHA -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $documento['Fecha_Carga']
                            );

                            ?>

                        </td>


                        <!-- USUARIO -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                ($documento['Nombre'] ?? '') .
                                " " .
                                ($documento['Apellido'] ?? '')
                            );

                            ?>

                        </td>


                        <!-- CATEGORIAS -->

                        <td>

                            <?php

                            $id_documento =
                                $documento['Id_documento'];


                            $sqlCategorias = "
                                SELECT
                                    c.Nombre_categoria

                                FROM Documento_categoria dc

                                INNER JOIN Categoria c
                                    ON dc.Id_categoria = c.Id_categoria

                                WHERE dc.Id_documento = ?
                            ";


                            $stmtCat =
                                $conexion->prepare(
                                    $sqlCategorias
                                );


                            if ($stmtCat) {


                                $stmtCat->bind_param(
                                    "i",
                                    $id_documento
                                );


                                if ($stmtCat->execute()) {


                                    $categorias =
                                        $stmtCat->get_result();


                                    if ($categorias->num_rows > 0) {


                                        while (
                                            $categoria =
                                            $categorias->fetch_assoc()
                                        ) {


                                            echo '<span class="badge bg-info text-dark me-1">';

                                            echo htmlspecialchars(
                                                $categoria['Nombre_categoria']
                                            );

                                            echo '</span>';

                                        }


                                    } else {


                                        echo '<span class="text-secondary">';
                                        echo 'Sin categoria';
                                        echo '</span>';

                                    }

                                }


                                $stmtCat->close();


                            } else {


                                echo "Error al obtener categorias.";

                            }

                            ?>

                        </td>


                    </tr>


                <?php endwhile; ?>


            <?php else: ?>


                <tr>

                    <td
                        colspan="6"
                        class="text-center">

                        No hay documentos disponibles.

                    </td>

                </tr>


            <?php endif; ?>


            </tbody>


        </table>

    </div>


    <a
        href="index.php"
        class="btn btn-secondary">

        Volver al inicio

    </a>


</div>


</body>

</html>


<?php

$conexion->close();

?>

