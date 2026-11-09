<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repositorio de documentos</title>
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
    <div class="mb-4">
        <h1>Repositorio de documentos</h1>
        <p class="text-secondary">Documentos disponibles en el sistema hospitalario</p>
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

            <?php if (count($documentos) > 0): ?>

                <?php foreach ($documentos as $documento): ?>

                    <tr>

                        <td><?php echo (int) $documento['Id_documento']; ?></td>

                        <td><?php echo htmlspecialchars($documento['Titulo']); ?></td>

                        <td>
                            <a
                                href="../<?php echo htmlspecialchars($documento['Archivo']); ?>"
                                target="_blank"
                                class="btn btn-sm btn-primary">
                                Ver documento
                            </a>
                        </td>

                        <td><?php echo htmlspecialchars($documento['Fecha_carga']); ?></td>

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

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="6" class="text-center">No hay documentos disponibles.</td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

    <a href="inicio_controlador.php" class="btn btn-secondary">Volver al inicio</a>

</div>

</body>
</html>
