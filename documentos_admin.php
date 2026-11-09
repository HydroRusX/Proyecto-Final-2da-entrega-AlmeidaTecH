<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administracion de documentos</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS propio del proyecto -->
    <link rel="stylesheet" href="../CSS/index.css">
</head>

<body>

<!-- Encabezado y logo -->
<header>
    <div class="logo">
        <img src="../Imagenes/Clinicas.jpg" alt="Logo Clinicas">
    </div>
</header>


<div class="container-fluid py-4">

    <!-- Titulo y usuario -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div>
            <h1>Administracion de documentos</h1>
            <p class="text-secondary">Gestion de documentos del sistema hospitalario</p>
        </div>

        <div class="d-flex align-items-center flex-wrap gap-2">

            <span class="me-2">
                Usuario: <?php echo htmlspecialchars($_SESSION['usuario']); ?>
            </span>

            <button
                type="button"
                class="btn btn-danger"
                data-bs-toggle="modal"
                data-bs-target="#modalCerrarSesion">
                Cerrar sesion
            </button>

        </div>

    </div>


    <!-- Modal para confirmar el cierre de sesion -->
    <div class="modal fade" id="modalCerrarSesion" tabindex="-1" aria-labelledby="tituloCerrarSesion" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="tituloCerrarSesion">Cerrar sesion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body text-center">
                    Estas seguro de que queres cerrar sesion?
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="usuario_controlador.php?accion=logout" class="btn btn-danger">Si, cerrar sesion</a>
                </div>

            </div>
        </div>
    </div>


    <!-- Mensaje para los funcionarios -->
    <?php if ($esFuncionario): ?>
        <div class="alert alert-info">
            <strong>Modo funcionario:</strong>
            Solo podes consultar los documentos. No tenes permisos para modificarlos.
        </div>
    <?php endif; ?>


    <!-- Mensaje de error por falta de permisos -->
    <?php if ($sinPermisos): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            No tenes permisos para realizar esta accion.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>


    <!-- Boton para subir un documento -->
    <?php if (!$esFuncionario): ?>
        <div class="mb-4">
            <a href="documento_controlador.php?accion=subir" class="btn btn-primary">+ Subir documento</a>
        </div>
    <?php endif; ?>


    <!-- Tabla de documentos -->
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
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

            <?php if (count($documentos) > 0): ?>

                <?php foreach ($documentos as $documento): ?>

                    <?php $id = (int) $documento['Id_documento']; ?>

                    <tr>

                        <td><?php echo $id; ?></td>

                        <td><?php echo htmlspecialchars($documento['Titulo'] ?? ''); ?></td>

                        <td>
                            <a
                                href="../<?php echo htmlspecialchars($documento['Archivo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-sm btn-outline-primary">
                                Ver archivo
                            </a>
                        </td>

                        <td><?php echo htmlspecialchars($documento['Fecha_carga'] ?? ''); ?></td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                ($documento['Nombre'] ?? '') . " " . ($documento['Apellido'] ?? '')
                            );
                            ?>
                        </td>

                        <td>
                            <?php if (count($documento['categorias']) > 0): ?>
                                <?php foreach ($documento['categorias'] as $nombreCategoria): ?>
                                    <span class="badge bg-info text-dark me-1">
                                        <?php echo htmlspecialchars($nombreCategoria); ?>
                                    </span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-secondary">Sin categoria</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($documento['Activo'] == 1): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Dado de baja</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="d-flex flex-wrap gap-2">

                                <?php if (!$esFuncionario): ?>

                                    <a
                                        href="documento_controlador.php?accion=editar&id=<?php echo $id; ?>"
                                        class="btn btn-sm btn-warning">
                                        Editar
                                    </a>

                                    <?php if ($documento['Activo'] == 1): ?>

                                        <a
                                            href="documento_controlador.php?accion=baja_logica&id=<?php echo $id; ?>"
                                            class="btn btn-sm btn-secondary"
                                            onclick="return confirm('Estas seguro de que queres realizar la baja logica de este documento?');">
                                            Baja logica
                                        </a>

                                    <?php else: ?>

                                        <a
                                            href="documento_controlador.php?accion=reactivar&id=<?php echo $id; ?>"
                                            class="btn btn-sm btn-success"
                                            onclick="return confirm('Estas seguro de que queres reactivar este documento?');">
                                            Reactivar
                                        </a>

                                    <?php endif; ?>

                                    <a
                                        href="documento_controlador.php?accion=baja_fisica&id=<?php echo $id; ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('ATENCION: esto eliminara definitivamente el documento. Estas seguro de continuar?');">
                                        Baja fisica
                                    </a>

                                <?php endif; ?>

                            </div>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="8" class="text-center">No hay documentos registrados.</td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>


    <!-- Boton para volver al inicio -->
    <a href="inicio_controlador.php" class="btn btn-secondary mt-4">&larr; Volver al inicio</a>

</div>


<!-- JavaScript de Bootstrap (necesario para los modales) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
