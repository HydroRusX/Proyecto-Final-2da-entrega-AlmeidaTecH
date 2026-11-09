<?php
require_once "sesion.php";
require_once "../modelos/documento.php";
require_once "../modelos/categoria.php";

$accion = $_GET['accion'] ?? 'listar';

// Pagina a la que se vuelve despues de cada accion
$volver = "documento_controlador.php?accion=listar";

// Carpeta fisica donde se guardan los archivos (php/documentos/) y formatos permitidos
$raiz = dirname(__DIR__) . "/";
$carpetaDocumentos = $raiz . "documentos/";
$permitidos = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

// Convierte las categorias recibidas en una lista de ids enteros validos
function limpiarCategorias($categorias) {
    if (!is_array($categorias)) {
        return [];
    }
    return array_values(array_unique(array_filter(array_map('intval', $categorias))));
}

// Comprueba el tamaño y que el contenido real del archivo coincida con su extension
function validarArchivo($archivo, $extension) {

    $maximo = 10 * 1024 * 1024; // 10 MB

    if ($archivo['size'] > $maximo) {
        die("Error: el archivo supera el tamaño maximo de 10 MB.");
    }

    // Se leen los primeros bytes del archivo (su "firma")
    $cabecera = '';
    $manejador = fopen($archivo['tmp_name'], 'rb');

    if ($manejador) {
        $cabecera = fread($manejador, 8);
        fclose($manejador);
    }

    if ($extension == 'pdf') {
        // Los PDF empiezan con %PDF
        $valido = (strpos($cabecera, '%PDF') === 0);
    } elseif (in_array($extension, ['docx', 'xlsx', 'pptx'])) {
        // Los formatos nuevos de Office son archivos ZIP (empiezan con PK)
        $valido = (substr($cabecera, 0, 4) === "PK\x03\x04");
    } else {
        // Los formatos viejos de Office (doc, xls, ppt) tienen una firma propia
        $valido = ($cabecera === "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1");
    }

    if (!$valido) {
        die("Error: el contenido del archivo no coincide con su formato.");
    }
}

// Lee y valida el id que llega por la URL
function obtenerIdGet() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        die("Error: documento no valido.");
    }
    return $id;
}

try {

    $modeloDocumento = new Documento();

    switch ($accion) {

        // ---------------------------------
        // Repositorio publico (solo activos)
        // ---------------------------------
        case 'repositorio':

            $documentos = $modeloDocumento->obtenerActivos();

            require_once "../vistas/repositorio.php";
            break;

        // ---------------------------------
        // Administracion de documentos
        // ---------------------------------
        case 'listar':

            requerirSesion();

            $esFuncionario = esFuncionario();
            $sinPermisos = (($_GET['error'] ?? '') == 'sin_permisos');
            $documentos = $modeloDocumento->obtenerTodos();

            require_once "../vistas/documentos_admin.php";
            break;

        // ---------------------------------
        // Formulario para subir un documento
        // ---------------------------------
        case 'subir':

            requerirSesion();
            requerirAdministrador($volver);

            $modeloCategoria = new Categoria();
            $categorias = $modeloCategoria->obtenerTodas();

            require_once "../vistas/documento_subir.php";
            break;

        // ---------------------------------
        // Guardar el documento subido
        // ---------------------------------
        case 'guardar':

            requerirSesion();
            requerirAdministrador($volver);

            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                header("Location: " . $volver);
                exit;
            }

            $titulo = trim($_POST['titulo'] ?? '');
            $categorias = $_POST['categorias'] ?? [];

            $categorias = limpiarCategorias($categorias);

            if ($titulo === '') {
                die("Error: debes ingresar un titulo.");
            }

            if (mb_strlen($titulo) > 45) {
                die("Error: el titulo no puede superar los 45 caracteres.");
            }

            if (empty($categorias)) {
                die("Error: debes seleccionar al menos una categoria.");
            }

            if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] != UPLOAD_ERR_OK) {
                die("Error al subir el archivo.");
            }

            $extension = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));

            if (!in_array($extension, $permitidos)) {
                die("Tipo de archivo no permitido.");
            }

            validarArchivo($_FILES['archivo'], $extension);

            if (!is_dir($carpetaDocumentos)) {
                mkdir($carpetaDocumentos, 0777, true);
            }

            // Nombre unico para el archivo
            $nombreArchivo = uniqid("doc_") . "." . $extension;

            if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $carpetaDocumentos . $nombreArchivo)) {
                die("No se pudo guardar el archivo.");
            }

            // En la base de datos se guarda la ruta relativa: documentos/nombre.ext
            $rutaBD = "documentos/" . $nombreArchivo;

            try {
                $modeloDocumento->crear($titulo, $rutaBD, $_SESSION['id_usuario'], $categorias);
            } catch (Exception $e) {
                // Si falla la base de datos, borro el archivo que acabo de subir
                unlink($carpetaDocumentos . $nombreArchivo);
                throw $e;
            }

            header("Location: " . $volver);
            exit;

        // ---------------------------------
        // Formulario para editar un documento
        // ---------------------------------
        case 'editar':

            requerirSesion();
            requerirAdministrador($volver);

            $id = obtenerIdGet();

            $documento = $modeloDocumento->obtenerPorId($id);

            if (!$documento) {
                die("Error: el documento no existe.");
            }

            $modeloCategoria = new Categoria();
            $categorias = $modeloCategoria->obtenerTodas();
            $seleccionadas = $modeloDocumento->obtenerIdsCategorias($id);

            require_once "../vistas/documento_editar.php";
            break;

        // ---------------------------------
        // Guardar los cambios de un documento
        // ---------------------------------
        case 'actualizar':

            requerirSesion();
            requerirAdministrador($volver);

            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                header("Location: " . $volver);
                exit;
            }

            $id = filter_input(INPUT_POST, 'id_documento', FILTER_VALIDATE_INT);
            $titulo = trim($_POST['titulo'] ?? '');
            $categorias = $_POST['categorias'] ?? [];

            $categorias = limpiarCategorias($categorias);

            if (!$id) {
                die("Error: documento no valido.");
            }

            if ($titulo === '') {
                die("Error: debes ingresar un titulo.");
            }

            if (mb_strlen($titulo) > 45) {
                die("Error: el titulo no puede superar los 45 caracteres.");
            }

            if (empty($categorias)) {
                die("Error: debes seleccionar al menos una categoria.");
            }

            $documentoActual = $modeloDocumento->obtenerPorId($id);

            if (!$documentoActual) {
                die("Error: el documento no existe.");
            }

            $archivoActual = $documentoActual['Archivo'];
            $nuevaRuta = $archivoActual;
            $archivoNuevoSubido = false;

            // Si se eligio un archivo nuevo, lo guardo
            if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] != UPLOAD_ERR_NO_FILE) {

                if ($_FILES['archivo']['error'] != UPLOAD_ERR_OK) {
                    die("Error al subir el nuevo archivo.");
                }

                $extension = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));

                if (!in_array($extension, $permitidos)) {
                    die("Error: el formato del archivo no esta permitido.");
                }

                validarArchivo($_FILES['archivo'], $extension);

                if (!is_dir($carpetaDocumentos)) {
                    mkdir($carpetaDocumentos, 0777, true);
                }

                $nombreArchivo = uniqid("doc_") . "." . $extension;

                if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $carpetaDocumentos . $nombreArchivo)) {
                    die("Error: no se pudo guardar el nuevo archivo.");
                }

                $nuevaRuta = "documentos/" . $nombreArchivo;
                $archivoNuevoSubido = true;
            }

            try {
                $modeloDocumento->actualizar($id, $titulo, $nuevaRuta, $categorias);
            } catch (Exception $e) {
                // Si falla la base de datos, borro el archivo nuevo
                if ($archivoNuevoSubido && file_exists($raiz . $nuevaRuta)) {
                    unlink($raiz . $nuevaRuta);
                }
                throw $e;
            }

            // Solo si todo salio bien, borro el archivo anterior
            if ($archivoNuevoSubido && !empty($archivoActual) && file_exists($raiz . $archivoActual)) {
                unlink($raiz . $archivoActual);
            }

            header("Location: " . $volver);
            exit;

        // ---------------------------------
        // Baja logica
        // ---------------------------------
        case 'baja_logica':

            requerirSesion();
            requerirAdministrador($volver);

            $modeloDocumento->cambiarEstado(obtenerIdGet(), 0);

            header("Location: " . $volver);
            exit;

        // ---------------------------------
        // Reactivar documento
        // ---------------------------------
        case 'reactivar':

            requerirSesion();
            requerirAdministrador($volver);

            $modeloDocumento->cambiarEstado(obtenerIdGet(), 1);

            header("Location: " . $volver);
            exit;

        // ---------------------------------
        // Baja fisica (elimina el documento y su archivo)
        // ---------------------------------
        case 'baja_fisica':

            requerirSesion();
            requerirAdministrador($volver);

            $id = obtenerIdGet();

            $documento = $modeloDocumento->obtenerPorId($id);

            if (!$documento) {
                die("Error: el documento no existe.");
            }

            $modeloDocumento->eliminar($id);

            // Despues de borrar de la base de datos, borro el archivo fisico
            if (!empty($documento['Archivo']) && file_exists($raiz . $documento['Archivo'])) {
                unlink($raiz . $documento['Archivo']);
            }

            header("Location: " . $volver);
            exit;

        default:
            header("Location: " . $volver);
            exit;
    }

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
