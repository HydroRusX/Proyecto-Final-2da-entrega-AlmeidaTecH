<?php
// Opciones de respuesta (valor => texto)
$opciones = [
    1 => 'Muy insatisfecho',
    2 => 'Insatisfecho',
    3 => 'Neutral',
    4 => 'Satisfecho',
    5 => 'Muy satisfecho'
];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($encuesta['Titulo']); ?></title>
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

    <div class="card shadow">

        <div class="card-header">
            <h1><?php echo htmlspecialchars($encuesta['Titulo']); ?></h1>
            <p class="mb-0 text-secondary"><?php echo htmlspecialchars($encuesta['Descripcion']); ?></p>
        </div>

        <div class="card-body">

            <form action="encuesta_controlador.php?accion=enviar" method="POST" id="formResponder" novalidate onsubmit="return validarRespuestas()">

                <div id="errores" class="alert alert-danger d-none"></div>

                <input type="hidden" name="id_encuesta" value="<?php echo (int) $encuesta['Id_encuesta']; ?>">

                <?php $numero = 1; ?>

                <?php foreach ($preguntas as $pregunta): ?>

                    <?php $idPregunta = (int) $pregunta['Id_pregunta']; ?>

                    <div class="card mb-4">

                        <div class="card-body">

                            <h5><?php echo $numero . ". " . htmlspecialchars($pregunta['Texto']); ?></h5>

                            <div class="mt-3">

                                <?php foreach ($opciones as $valor => $texto): ?>

                                    <div class="form-check">

                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            id="p<?php echo $idPregunta; ?>_<?php echo $valor; ?>"
                                            name="respuesta[<?php echo $idPregunta; ?>]"
                                            value="<?php echo $valor; ?>"
                                            required>

                                        <label class="form-check-label" for="p<?php echo $idPregunta; ?>_<?php echo $valor; ?>">
                                            <?php echo $valor . " - " . $texto; ?>
                                        </label>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        </div>

                    </div>

                    <?php $numero++; ?>

                <?php endforeach; ?>

                <button type="submit" class="btn btn-primary">Enviar respuestas</button>

                <a href="encuesta_controlador.php?accion=publicas" class="btn btn-secondary">Cancelar</a>

            </form>

        </div>

    </div>

</div>

<?php require __DIR__ . "/validaciones_js.php"; ?>

</body>
</html>
