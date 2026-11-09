<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar encuesta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container py-5">

    <div class="card shadow">

        <div class="card-header">
            <h2>Editar encuesta</h2>
        </div>

        <div class="card-body">

            <form action="encuesta_controlador.php?accion=actualizar" method="POST" novalidate onsubmit="return validarEncuesta()">

                <div id="errores" class="alert alert-danger d-none"></div>

                <input type="hidden" name="id_encuesta" value="<?php echo (int) $encuesta['Id_encuesta']; ?>">

                <!-- TITULO -->
                <div class="mb-3">
                    <label class="form-label">Titulo</label>
                    <input
                        type="text"
                        id="titulo" name="titulo"
                        class="form-control"
                        maxlength="25"
                        value="<?php echo htmlspecialchars($encuesta['Titulo']); ?>"
                        required>
                </div>

                <!-- DESCRIPCION -->
                <div class="mb-3">
                    <label class="form-label">Descripcion</label>
                    <textarea
                        id="descripcion" name="descripcion"
                        class="form-control"
                        rows="4"
                        maxlength="100"
                        required><?php echo htmlspecialchars($encuesta['Descripcion']); ?></textarea>
                </div>

                <!-- SEGMENTADA -->
                <div class="mb-3">
                    <label class="form-label">¿Es una encuesta segmentada?</label>

                    <select name="segmentada" class="form-select">
                        <option value="0" <?php if ($encuesta['Segmentada'] == 0) { echo "selected"; } ?>>No</option>
                        <option value="1" <?php if ($encuesta['Segmentada'] == 1) { echo "selected"; } ?>>Si</option>
                    </select>
                </div>

                <!-- PREGUNTAS -->
                <div class="mb-3">
                    <label class="form-label">Preguntas</label>

                    <div id="preguntas">

                        <?php foreach ($preguntas as $pregunta): ?>

                            <div class="input-group mb-2">

                                <input type="hidden" name="id_preguntas[]" value="<?php echo (int) $pregunta['Id_pregunta']; ?>">

                                <input
                                    type="text"
                                    name="preguntas[]"
                                    class="form-control"
                                    maxlength="100"
                                    value="<?php echo htmlspecialchars($pregunta['Texto']); ?>"
                                    required>

                                <button type="button" class="btn btn-danger" onclick="eliminarPregunta(this)">
                                    Eliminar
                                </button>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <button type="button" class="btn btn-secondary btn-sm" onclick="agregarPregunta()">
                        + Agregar pregunta
                    </button>
                </div>

                <button type="submit" class="btn btn-primary">Guardar cambios</button>

                <a href="encuesta_controlador.php?accion=listar" class="btn btn-secondary">Cancelar</a>

            </form>

        </div>

    </div>

</div>


<script>

function agregarPregunta() {

    const contenedor = document.getElementById("preguntas");

    const div = document.createElement("div");
    div.className = "input-group mb-2";

    div.innerHTML = `
        <input type="hidden" name="id_preguntas[]" value="0">

        <input
            type="text"
            name="preguntas[]"
            class="form-control"
            placeholder="Nueva pregunta"
            maxlength="100"
            required>

        <button type="button" class="btn btn-danger" onclick="eliminarPregunta(this)">
            Eliminar
        </button>
    `;

    contenedor.appendChild(div);
}

// Elimina la pregunta de la pantalla (se borra de la base de datos al guardar)
function eliminarPregunta(boton) {
    boton.parentElement.remove();
}

</script>

<?php require __DIR__ . "/validaciones_js.php"; ?>

</body>
</html>
