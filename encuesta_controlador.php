<?php
require_once "sesion.php";
require_once "../modelos/encuesta.php";

$accion = $_GET['accion'] ?? 'listar';

// Pagina a la que se vuelve despues de cada accion
$volver = "encuesta_controlador.php?accion=listar";

// Lee y valida el id que llega por la URL
function obtenerIdGet() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        die("Error: encuesta no valida.");
    }
    return $id;
}

try {

    $modeloEncuesta = new Encuesta();

    switch ($accion) {

        // ---------------------------------
        // Administracion de encuestas
        // ---------------------------------
        case 'listar':

            requerirSesion();

            $esFuncionario = esFuncionario();
            $sinPermisos = (($_GET['error'] ?? '') == 'sin_permisos');
            $encuestas = $modeloEncuesta->obtenerTodas();

            require_once "../vistas/encuestas_admin.php";
            break;

        // ---------------------------------
        // Formulario para crear una encuesta
        // ---------------------------------
        case 'crear':

            requerirSesion();
            requerirAdministrador($volver);

            require_once "../vistas/encuesta_crear.php";
            break;

        // ---------------------------------
        // Guardar la encuesta nueva
        // ---------------------------------
        case 'guardar':

            requerirSesion();
            requerirAdministrador($volver);

            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                header("Location: " . $volver);
                exit;
            }

            $titulo = trim($_POST['titulo'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $segmentada = (intval($_POST['segmentada'] ?? 0) == 1) ? 1 : 0;
            $preguntas = $_POST['preguntas'] ?? [];

            if (!is_array($preguntas)) {
                $preguntas = [];
            }

            if ($titulo == '') {
                die("El titulo de la encuesta es obligatorio.");
            }

            if (mb_strlen($titulo) > 25) {
                die("Error: el titulo no puede superar los 25 caracteres.");
            }

            if ($descripcion == '') {
                die("Error: debes ingresar una descripcion.");
            }

            if (mb_strlen($descripcion) > 100) {
                die("Error: la descripcion no puede superar los 100 caracteres.");
            }

            // Me quedo solo con las preguntas que no estan vacias
            $preguntasValidas = [];

            foreach ($preguntas as $pregunta) {
                $pregunta = trim((string) $pregunta);
                if ($pregunta != '') {
                    if (mb_strlen($pregunta) > 100) {
                        die("Error: cada pregunta puede tener hasta 100 caracteres.");
                    }
                    $preguntasValidas[] = $pregunta;
                }
            }

            if (count($preguntasValidas) == 0) {
                die("Debes agregar al menos una pregunta.");
            }

            $modeloEncuesta->crear($titulo, $descripcion, $segmentada, $preguntasValidas);

            header("Location: " . $volver);
            exit;

        // ---------------------------------
        // Formulario para editar una encuesta
        // ---------------------------------
        case 'editar':

            requerirSesion();
            requerirAdministrador($volver);

            $id = obtenerIdGet();

            $encuesta = $modeloEncuesta->obtenerPorId($id);

            if (!$encuesta) {
                die("Error: la encuesta no existe.");
            }

            $preguntas = $modeloEncuesta->obtenerPreguntas($id);

            require_once "../vistas/encuesta_editar.php";
            break;

        // ---------------------------------
        // Guardar los cambios de una encuesta
        // ---------------------------------
        case 'actualizar':

            requerirSesion();
            requerirAdministrador($volver);

            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                header("Location: " . $volver);
                exit;
            }

            $id = filter_input(INPUT_POST, 'id_encuesta', FILTER_VALIDATE_INT);
            $titulo = trim($_POST['titulo'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $segmentada = (intval($_POST['segmentada'] ?? 0) == 1) ? 1 : 0;
            $idsPreguntas = $_POST['id_preguntas'] ?? [];
            $preguntas = $_POST['preguntas'] ?? [];

            if (!is_array($idsPreguntas)) {
                $idsPreguntas = [];
            }

            if (!is_array($preguntas)) {
                $preguntas = [];
            }

            if (!$id) {
                die("Error: encuesta no valida.");
            }

            if ($titulo === '') {
                die("Error: debes ingresar un titulo.");
            }

            if (mb_strlen($titulo) > 25) {
                die("Error: el titulo no puede superar los 25 caracteres.");
            }

            if ($descripcion === '') {
                die("Error: debes ingresar una descripcion.");
            }

            if (mb_strlen($descripcion) > 100) {
                die("Error: la descripcion no puede superar los 100 caracteres.");
            }

            // Debe quedar al menos una pregunta con texto y ninguna puede pasar los 100 caracteres
            $hayPreguntas = false;

            foreach ($preguntas as $textoPregunta) {

                $textoPregunta = trim((string) $textoPregunta);

                if ($textoPregunta !== '') {

                    $hayPreguntas = true;

                    if (mb_strlen($textoPregunta) > 100) {
                        die("Error: cada pregunta puede tener hasta 100 caracteres.");
                    }
                }
            }

            if (!$hayPreguntas) {
                die("Error: debes tener al menos una pregunta.");
            }

            $modeloEncuesta->actualizar($id, $titulo, $descripcion, $segmentada, $idsPreguntas, $preguntas);

            header("Location: " . $volver);
            exit;

        // ---------------------------------
        // Eliminar encuesta
        // ---------------------------------
        case 'eliminar':

            requerirSesion();
            requerirAdministrador($volver);

            $modeloEncuesta->eliminar(obtenerIdGet());

            header("Location: " . $volver);
            exit;

        // ---------------------------------
        // Resultados de una encuesta
        // ---------------------------------
        case 'resultados':

            requerirSesion();

            $id = obtenerIdGet();

            $encuesta = $modeloEncuesta->obtenerPorId($id);

            if (!$encuesta) {
                die("Error: la encuesta no existe.");
            }

            $resultados = $modeloEncuesta->obtenerResultados($id);

            // Promedio general = promedio de los promedios de cada pregunta
            $sumaPromedios = 0;
            $cantidadPreguntas = 0;

            foreach ($resultados as $fila) {
                if ($fila['promedio'] !== null) {
                    $sumaPromedios += $fila['promedio'];
                    $cantidadPreguntas++;
                }
            }

            $promedioGeneral = ($cantidadPreguntas > 0) ? $sumaPromedios / $cantidadPreguntas : 0;

            require_once "../vistas/encuesta_resultados.php";
            break;

        // ---------------------------------
        // Encuestas disponibles para responder (publico)
        // ---------------------------------
        case 'publicas':

            $encuestas = $modeloEncuesta->obtenerTodas();

            require_once "../vistas/encuestas_publicas.php";
            break;

        // ---------------------------------
        // Formulario para responder una encuesta (publico)
        // ---------------------------------
        case 'responder':

            $id = obtenerIdGet();

            $encuesta = $modeloEncuesta->obtenerPorId($id);

            if (!$encuesta) {
                die("Error: la encuesta no existe.");
            }

            $preguntas = $modeloEncuesta->obtenerPreguntas($id);

            if (count($preguntas) == 0) {
                die("Esta encuesta no tiene preguntas.");
            }

            require_once "../vistas/encuesta_responder.php";
            break;

        // ---------------------------------
        // Guardar las respuestas (publico)
        // ---------------------------------
        case 'enviar':

            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                header("Location: encuesta_controlador.php?accion=publicas");
                exit;
            }

            $id = filter_input(INPUT_POST, 'id_encuesta', FILTER_VALIDATE_INT);
            $respuestas = $_POST['respuesta'] ?? [];

            if (!$id) {
                die("Error: encuesta no valida.");
            }

            if (!is_array($respuestas) || count($respuestas) == 0) {
                die("Error: no se recibieron respuestas.");
            }

            $preguntas = $modeloEncuesta->obtenerPreguntas($id);

            if (count($preguntas) == 0) {
                die("Error: la encuesta no tiene preguntas.");
            }

            // Compruebo que se respondieron todas las preguntas con un valor de 1 a 5
            $valores = [];

            foreach ($preguntas as $pregunta) {

                $idPregunta = $pregunta['Id_pregunta'];

                if (!isset($respuestas[$idPregunta])) {
                    die("Error: debes responder todas las preguntas.");
                }

                $valor = intval($respuestas[$idPregunta]);

                if ($valor < 1 || $valor > 5) {
                    die("Error: valor de satisfaccion no valido.");
                }

                $valores[$idPregunta] = $valor;
            }

            $modeloEncuesta->guardarRespuestas($valores);

            header("Location: encuesta_controlador.php?accion=gracias");
            exit;

        // ---------------------------------
        // Pantalla de agradecimiento
        // ---------------------------------
        case 'gracias':

            require_once "../vistas/respuesta_enviada.php";
            break;

        default:
            header("Location: " . $volver);
            exit;
    }

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
