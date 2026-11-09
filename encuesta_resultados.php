<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de encuesta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/index.css">
</head>

<body>

<header>
    <div class="logo">
        <img src="../Imagenes/Clinicas.jpg" alt="Logo Clínicas">
    </div>
</header>


<div class="container py-5">

    <div class="mb-4">
        <h1>Resultados de encuesta</h1>
        <h3><?php echo htmlspecialchars($encuesta['Titulo']); ?></h3>
        <p class="text-secondary"><?php echo htmlspecialchars($encuesta['Descripcion']); ?></p>
    </div>

    <div class="table-responsive">

        <table class="table table-bordered table-hover">

            <thead class="table-dark">
                <tr>
                    <th>Pregunta</th>
                    <th>Respuestas</th>
                    <th>Promedio</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach ($resultados as $fila): ?>

                <tr>

                    <td><?php echo htmlspecialchars($fila['Texto']); ?></td>

                    <td><?php echo (int) $fila['cantidad_respuestas']; ?></td>

                    <td>
                        <?php
                        if ($fila['promedio'] !== null) {
                            echo number_format($fila['promedio'], 2) . " / 5";
                        } else {
                            echo "Sin respuestas";
                        }
                        ?>
                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

    <div class="card shadow mt-4">

        <div class="card-body text-center">

            <h4>Promedio general de satisfaccion</h4>

            <?php if ($cantidadPreguntas > 0): ?>

                <div class="display-5">
                    <?php echo number_format($promedioGeneral, 2); ?> / 5
                </div>

            <?php else: ?>

                <p class="text-secondary">Todavia no hay respuestas.</p>

            <?php endif; ?>

        </div>

    </div>

    <div class="mt-4">
        <a href="encuesta_controlador.php?accion=listar" class="btn btn-secondary">Volver a encuestas</a>
    </div>

</div>

</body>
</html>
