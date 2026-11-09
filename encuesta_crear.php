<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear encuesta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container py-5">

    <div class="card shadow">

        <div class="card-header">
            <h2>Crear encuesta</h2>
        </div>

        <div class="card-body">

            <form action="encuesta_controlador.php?accion=guardar" method="POST" novalidate onsubmit="return validarEncuesta()">

                <div id="errores" class="alert alert-danger d-none"></div>

                <!-- TITULO -->
                <div class="mb-3">
                    <label class="form-label">Titulo</label>
                    <input type="text" id="titulo" name="titulo" class="form-control" maxlength="25" required>
                </div>

                <!-- DESCRIPCION -->
                <div class="mb-3">
                    <label class="form-label">Descripcion</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" rows="4" maxlength="100" required></textarea>
                </div>

                <!-- SEGMENTADA -->
                <div class="mb-3">
                    <label class="form-label">¿Es una encuesta segmentada?</label>

                    <select name="segmentada" class="form-select" required>
                        <option value="0">No</option>
                        <option value="1">Si</option>
                    </select>
                </div>

                <!-- PREGUNTAS -->
                <div class="mb-3">
                    <label class="form-label">Preguntas</label>

                    <div id="preguntas">

                        <div class="input-group mb-2">
                            <input
                                type="text"
                                name="preguntas[]"
                                class="form-control"
                                placeholder="Escribe una pregunta"
                                maxlength="100"
                                required>
                        </div>

                    </div>

                    <button type="button" class="btn btn-secondary btn-sm" onclick="agregarPregunta()">
                        + Agregar pregunta
                    </button>
                </div>

                <!-- BOTONES -->
                <button type="submit" class="btn btn-primary">Crear encuesta</button>

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
        <input
            type="text"
            name="preguntas[]"
            class="form-control"
            placeholder="Escribe una pregunta"
            maxlength="100"
            required>

        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">
            Eliminar
        </button>
    `;

    contenedor.appendChild(div);
}

</script>

<?php require __DIR__ . "/validaciones_js.php"; ?>

</body>
</html>
