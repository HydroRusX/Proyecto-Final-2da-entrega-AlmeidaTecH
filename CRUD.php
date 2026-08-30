<?php

session_start();


// ===============================
// COMPROBAR SESION
// ===============================

if (!isset($_SESSION['usuario'])) {

    header("Location: login.php?error=sesion");
    exit;

}


// ===============================
// COMPROBAR TIPO DE USUARIO
// ===============================

$esFuncionario = (
    isset($_SESSION['tipo_usuario']) &&
    strtolower(trim($_SESSION['tipo_usuario'])) == 'funcionario'
);


require_once "conexion.php";


// ===============================
// OBTENER DOCUMENTOS
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

    ORDER BY d.Id_documento DESC
";


// ===============================
// EJECUTAR CONSULTA
// ===============================

$resultado = $conexion->query($sql);

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
            <img src="../Imagenes/Clinicas.jpg" alt="Logo Clínicas">
        </div>
</header>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Administracion de documentos</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>


<body>


<div class="container-fluid py-4">


    <!-- ===============================
         TITULO
    ================================ -->

    <div class="d-flex justify-content-between align-items-center mb-4">


        <div>

            <h1>
                Administracion de documentos
            </h1>

            <p class="text-secondary">

                Gestion de documentos del sistema hospitalario

            </p>

        </div>


        <!-- ===============================
             USUARIO
        ================================ -->

        <div>

            <span class="me-3">

                Usuario:

                <?php

                echo htmlspecialchars(
                    $_SESSION['usuario']
                );

                ?>

            </span>


            <a
                href="logout.php"
                class="btn btn-outline-danger">

                Cerrar sesion

            </a>

        </div>


    </div>


    <!-- ===============================
         MENSAJE PARA FUNCIONARIO
    ================================ -->

    <?php if ($esFuncionario): ?>

        <div class="alert alert-info">

            <strong>Modo funcionario:</strong>

            Solo podes consultar los documentos.
            No tenes permisos para modificarlos.

        </div>

    <?php endif; ?>


    <!-- ===============================
         BOTON SUBIR DOCUMENTO
    ================================ -->

    <?php if (!$esFuncionario): ?>

        <div class="mb-4">

            <a
                href="subir_documento.php"
                class="btn btn-primary">

                + Subir documento

            </a>

        </div>

    <?php endif; ?>


    <!-- ===============================
         TABLA
    ================================ -->

    <div class="table-responsive">


        <table
            class="table table-bordered table-hover align-middle">


            <thead class="table-dark">

                <tr>

                    <th>ID</th>

                    <th>Titulo</th>

                    <th>Archivo</th>

                    <th>Fecha de carga</th>

                    <th>Usuario</th>

                    <th>Categorias</th>

                    <th>Acciones</th>

                </tr>

            </thead>


            <tbody>


            <?php if ($resultado->num_rows > 0): ?>


                <?php while (
                    $documento =
                    $resultado->fetch_assoc()
                ): ?>


                    <tr>


                        <!-- ===============================
                             ID
                        ================================ -->

                        <td>

                            <?php

                            echo $documento[
                                'Id_documento'
                            ];

                            ?>

                        </td>


                        <!-- ===============================
                             TITULO
                        ================================ -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $documento['Titulo']
                            );

                            ?>

                        </td>


                        <!-- ===============================
                             ARCHIVO
                        ================================ -->

                        <td>

                            <a
                                href="<?php echo htmlspecialchars($documento['Archivo']); ?>"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary">

                                Ver archivo

                            </a>

                        </td>


                        <!-- ===============================
                             FECHA
                        ================================ -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $documento['Fecha_Carga']
                            );

                            ?>

                        </td>


                        <!-- ===============================
                             USUARIO
                        ================================ -->

                        <td>

                            <?php

                            echo htmlspecialchars(

                                ($documento['Nombre'] ?? '') .
                                " " .
                                ($documento['Apellido'] ?? '')

                            );

                            ?>

                        </td>


                        <!-- ===============================
                             CATEGORIAS
                        ================================ -->

                        <td>

                            <?php


                            $id_documento =
                                $documento['Id_documento'];


                            $sqlCategorias = "

                                SELECT
                                    c.Nombre_categoria

                                FROM Documento_categoria dc

                                INNER JOIN Categoria c
                                    ON dc.Id_categoria =
                                       c.Id_categoria

                                WHERE dc.Id_documento = ?

                            ";


                            $stmtCat =
                                $conexion->prepare(
                                    $sqlCategorias
                                );


                            if (!$stmtCat) {

                                die(

                                    "Error al preparar consulta de categorias: " .
                                    $conexion->error

                                );

                            }


                            $stmtCat->bind_param(

                                "i",
                                $id_documento

                            );


                            if (!$stmtCat->execute()) {

                                die(

                                    "Error al obtener categorias: " .
                                    $stmtCat->error

                                );

                            }


                            $categorias =
                                $stmtCat->get_result();


                            if (
                                $categorias->num_rows > 0
                            ):


                                while (

                                    $categoria =
                                    $categorias->fetch_assoc()

                                ):

                            ?>


                                <span
                                    class="badge bg-info text-dark me-1">

                                    <?php

                                    echo htmlspecialchars(
                                        $categoria[
                                            'Nombre_categoria'
                                        ]
                                    );

                                    ?>

                                </span>


                            <?php

                                endwhile;


                            else:

                            ?>


                                <span
                                    class="text-secondary">

                                    Sin categoria

                                </span>


                            <?php

                            endif;


                            $stmtCat->close();

                            ?>


                        </td>


                        <!-- ===============================
                             ACCIONES
                        ================================ -->

                        <td>


                            <div
                                class="d-flex flex-wrap gap-2">


                                <!-- ===============================
                                     VER ARCHIVO
                                ================================ -->

                                <a
                                    href="<?php echo htmlspecialchars($documento['Archivo']); ?>"
                                    target="_blank"
                                    class="btn btn-sm btn-primary">

                                    Ver

                                </a>


                                <!-- ===============================
                                     SOLO ADMINISTRADOR
                                ================================ -->

                                <?php if (!$esFuncionario): ?>


                                    <!-- EDITAR -->

                                    <a
                                        href="editar_documento.php?id=<?php echo $documento['Id_documento']; ?>"
                                        class="btn btn-sm btn-warning">

                                        Editar

                                    </a>


                                    <!-- BAJA FISICA -->

                                    <a
                                        href="baja_fisica.php?id=<?php echo $documento['Id_documento']; ?>"
                                        class="btn btn-sm btn-danger"

                                        onclick="return confirm('ATENCION: esto eliminara definitivamente el documento. ¿Estas seguro de continuar?');">

                                        Baja fisica

                                    </a>


                                <?php endif; ?>


                            </div>


                        </td>


                    </tr>


                <?php endwhile; ?>


            <?php else: ?>


                <tr>

                    <td
                        colspan="7"
                        class="text-center">

                        No hay documentos registrados.

                    </td>

                </tr>


            <?php endif; ?>


            </tbody>


        </table>


    </div>


    <!-- ===============================
         VOLVER AL INICIO
    ================================ -->

    <a
        href="index.php"
        class="btn btn-secondary mt-4">

        ← Volver al inicio

    </a>


</div>


</body>

</html>


<?php

$conexion->close();

?>