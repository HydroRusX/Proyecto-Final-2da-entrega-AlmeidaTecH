
<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php?error=sesion");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Crear encuesta</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>


<body>


<div class="container py-5">


    <div class="card shadow">


        <div class="card-header">

            <h2>
                Crear encuesta
            </h2>

        </div>


        <div class="card-body">


            <form
                action="procesar_crear_encuesta.php"
                method="POST">


                <!-- TITULO -->

                <div class="mb-3">

                    <label class="form-label">
                        Titulo
                    </label>

                    <input
                        type="text"
                        name="titulo"
                        class="form-control"
                        required>

                </div>


                <!-- DESCRIPCION -->

                <div class="mb-3">

                    <label class="form-label">
                        Descripcion
                    </label>

                    <textarea
                        name="descripcion"
                        class="form-control"
                        rows="4"
                        required></textarea>

                </div>


                <!-- SEGMENTADA -->

                <div class="mb-3">

                    <label class="form-label">
                        ¿Es una encuesta segmentada?
                    </label>


                    <select
                        name="segmentada"
                        class="form-select"
                        required>

                        <option value="0">
                            No
                        </option>

                        <option value="1">
                            Si
                        </option>

                    </select>

                </div>


                <!-- PREGUNTAS -->

                <div class="mb-3">

                    <label class="form-label">
                        Preguntas
                    </label>


                    <div id="preguntas">


                        <div class="input-group mb-2">

                            <input
                                type="text"
                                name="preguntas[]"
                                class="form-control"
                                placeholder="Escribe una pregunta"
                                required>

                        </div>


                    </div>


                    <button
                        type="button"
                        class="btn btn-secondary btn-sm"
                        onclick="agregarPregunta()">

                        + Agregar pregunta

                    </button>

                </div>


                <!-- BOTONES -->

                <button
                    type="submit"
                    class="btn btn-primary">

                    Crear encuesta

                </button>


                <a
                    href="CRUD_encuestas.php"
                    class="btn btn-secondary">

                    Cancelar

                </a>


            </form>


        </div>


    </div>


</div>


<script>

function agregarPregunta() {

    const contenedor =
        document.getElementById("preguntas");


    const div =
        document.createElement("div");


    div.className =
        "input-group mb-2";


    div.innerHTML = `
        <input
            type="text"
            name="preguntas[]"
            class="form-control"
            placeholder="Escribe una pregunta"
            required>

        <button
            type="button"
            class="btn btn-danger"
            onclick="this.parentElement.remove()">

            X

        </button>
    `;


    contenedor.appendChild(div);

}

</script>


</body>

</html>

