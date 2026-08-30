<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php?error=sesion");
    exit;
}

require_once "conexion.php";


// ===============================
// COMPROBAR TIPO DE USUARIO
// ===============================

$esFuncionario = (
    isset($_SESSION['tipo_usuario']) &&
    strtolower(trim($_SESSION['tipo_usuario'])) == 'funcionario'
);


// ===============================
// OBTENER ENCUESTAS
// ===============================

$sql = "
    SELECT
        Id_encuesta,
        Titulo,
        Descripcion,
        Segmentada
    FROM Encuesta
    ORDER BY Id_encuesta DESC
";

$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error al obtener las encuestas: " . $conexion->error);
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

    <title>Administracion de encuestas</title>

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
                Administracion de encuestas
            </h1>

            <p class="text-secondary">
                Gestion de encuestas del sistema hospitalario
            </p>

        </div>

    </div>


    <!-- ===============================
         MENSAJE FUNCIONARIO
    ================================ -->

    <?php if ($esFuncionario): ?>

        <div class="alert alert-info">

            Estas ingresando como funcionario.
            Solo podes consultar los resultados de las encuestas.

        </div>

    <?php endif; ?>


    <!-- ===============================
         CREAR ENCUESTA
    ================================ -->

    <?php if (!$esFuncionario): ?>

        <div class="mb-4">

            <a
                href="crear_encuesta.php"
                class="btn btn-primary">

                + Crear encuesta

            </a>

        </div>

    <?php endif; ?>


    <!-- ===============================
         TABLA
    ================================ -->

    <div class="table-responsive">

        <table class="table table-bordered table-hover align-middle">

            <thead class="table-dark">

                <tr>

                    <th>ID</th>

                    <th>Titulo</th>

                    <th>Descripcion</th>

                    <th>Tipo</th>

                    <th>Acciones</th>

                </tr>

            </thead>


            <tbody>


            <?php if ($resultado->num_rows > 0): ?>


                <?php while ($encuesta = $resultado->fetch_assoc()): ?>


                    <tr>


                        <!-- ID -->

                        <td>

                            <?php
                            echo $encuesta['Id_encuesta'];
                            ?>

                        </td>


                        <!-- TITULO -->

                        <td>

                            <?php
                            echo htmlspecialchars(
                                $encuesta['Titulo']
                            );
                            ?>

                        </td>


                        <!-- DESCRIPCION -->

                        <td>

                            <?php
                            echo htmlspecialchars(
                                $encuesta['Descripcion']
                            );
                            ?>

                        </td>


                        <!-- TIPO -->

                        <td>

                            <?php if ($encuesta['Segmentada'] == 1): ?>

                                <span class="badge bg-warning text-dark">

                                    Segmentada

                                </span>

                            <?php else: ?>

                                <span class="badge bg-success">

                                    General

                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- ACCIONES -->

                        <td>

                            <div class="d-flex flex-wrap gap-2">


                                <!-- ===============================
                                     VER RESULTADOS
                                ================================ -->

                                <a
                                    href="resultados_encuesta.php?id=<?php echo $encuesta['Id_encuesta']; ?>"
                                    class="btn btn-sm btn-primary">

                                    Ver resultados

                                </a>


                                <!-- ===============================
                                     SOLO ADMINISTRADOR
                                ================================ -->

                                <?php if (!$esFuncionario): ?>


                                    <a
                                        href="editar_encuesta.php?id=<?php echo $encuesta['Id_encuesta']; ?>"
                                        class="btn btn-sm btn-warning">

                                        Editar

                                    </a>


                                    <a
                                        href="baja_encuesta.php?id=<?php echo $encuesta['Id_encuesta']; ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('ATENCION: estas seguro de que queres eliminar esta encuesta?');">

                                        Eliminar

                                    </a>


                                <?php endif; ?>


                            </div>

                        </td>


                    </tr>


                <?php endwhile; ?>


            <?php else: ?>


                <tr>

                    <td
                        colspan="5"
                        class="text-center">

                        No hay encuestas registradas.

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