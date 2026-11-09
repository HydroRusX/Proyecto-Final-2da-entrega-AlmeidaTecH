<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administracion de encuestas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/index.css">
</head>

<body>

<header>
    <div class="logo">
        <img src="../Imagenes/Clinicas.jpg" alt="Logo Clínicas">
    </div>
</header>


<div class="container-fluid py-4">

    <!-- TITULO -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Administracion de encuestas</h1>
            <p class="text-secondary">Gestion de encuestas del sistema hospitalario</p>
        </div>
    </div>

    <!-- MENSAJE FUNCIONARIO -->
    <?php if ($esFuncionario): ?>
        <div class="alert alert-info">
            Estas ingresando como funcionario. Solo podes consultar los resultados de las encuestas.
        </div>
    <?php endif; ?>

    <!-- MENSAJE DE ERROR POR FALTA DE PERMISOS -->
    <?php if ($sinPermisos): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            No tenes permisos para realizar esta accion.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <!-- CREAR ENCUESTA -->
    <?php if (!$esFuncionario): ?>
        <div class="mb-4">
            <a href="encuesta_controlador.php?accion=crear" class="btn btn-primary">+ Crear encuesta</a>
        </div>
    <?php endif; ?>

    <!-- TABLA -->
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

            <?php if (count($encuestas) > 0): ?>

                <?php foreach ($encuestas as $encuesta): ?>

                    <?php $id = (int) $encuesta['Id_encuesta']; ?>

                    <tr>

                        <td><?php echo $id; ?></td>

                        <td><?php echo htmlspecialchars($encuesta['Titulo']); ?></td>

                        <td><?php echo htmlspecialchars($encuesta['Descripcion']); ?></td>

                        <td>
                            <?php if ($encuesta['Segmentada'] == 1): ?>
                                <span class="badge bg-warning text-dark">Segmentada</span>
                            <?php else: ?>
                                <span class="badge bg-success">General</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="d-flex flex-wrap gap-2">

                                <!-- VER RESULTADOS (todos los usuarios) -->
                                <a
                                    href="encuesta_controlador.php?accion=resultados&id=<?php echo $id; ?>"
                                    class="btn btn-sm btn-primary">
                                    Ver resultados
                                </a>

                                <!-- SOLO ADMINISTRADOR -->
                                <?php if (!$esFuncionario): ?>

                                    <a
                                        href="encuesta_controlador.php?accion=editar&id=<?php echo $id; ?>"
                                        class="btn btn-sm btn-warning">
                                        Editar
                                    </a>

                                    <a
                                        href="encuesta_controlador.php?accion=eliminar&id=<?php echo $id; ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('ATENCION: estas seguro de que queres eliminar esta encuesta?');">
                                        Eliminar
                                    </a>

                                <?php endif; ?>

                            </div>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="5" class="text-center">No hay encuestas registradas.</td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

    <!-- VOLVER AL INICIO -->
    <a href="inicio_controlador.php" class="btn btn-secondary mt-4">← Volver al inicio</a>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
