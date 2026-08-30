
<?php

session_start();

$logueado = isset($_SESSION['usuario']);

if ($logueado) {
    $usuario = $_SESSION['usuario'];
    $tipo_usuario = $_SESSION['tipo_usuario'];
} else {
    $usuario = "Guest";
    $tipo_usuario = "";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistema Hospitalario</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- CSS propio -->

    <link rel="stylesheet" href="../CSS/index.css">

</head>


<!-- ========================= Pop UP CERRAR SESIÓN ========================= -->

<div
    class="modal fade"
    id="modalCerrarSesion"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Cerrar sesión
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar">
                </button>

            </div>

            <div class="modal-body text-center">

                <p class="mb-0">
                    ¿Estás seguro de que querés cerrar sesión?
                </p>

            </div>

            <div class="modal-footer justify-content-center">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Cancelar

                </button>

                <a
                    href="cerrar_sesion.php"
                    class="btn btn-danger">

                    Sí, cerrar sesión

                </a>

            </div>

        </div>

    </div>

</div>


<!-- Bootstrap JavaScript -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>



<body>


<!-- ========================= HEADER ========================= -->

<header class="d-flex align-items-center justify-content-between px-3">


    <!-- LOGO -->

    <div class="logo">

        <img
            src="../Imagenes/Clinicas.jpg"
            alt="Logo Clínicas">

    </div>



    <!-- USUARIO -->

    <div class="d-flex align-items-center gap-2">

        <?php if ($logueado): ?>

            <span class="usuario">

                <?php echo htmlspecialchars($usuario); ?>

            </span>


           <button
            type="button"
            class="btn btn-outline-danger btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalCerrarSesion">

            Cerrar sesión

            </button>


        <?php else: ?>

            <span class="usuario">

                Invitado

            </span>


            <a
                href="login.php"
                class="btn btn-outline-primary btn-sm">

                Iniciar sesión

            </a>


        <?php endif; ?>

    </div>

</header>



<!-- BOToN DE AYUDA -->

<div class="boton-ayuda">

    <button
        onclick="iniciarGuia()"
        title="¿Cómo utilizar la aplicación?">

        <img
            src="../Imagenes/Duda.png"
            alt="Ayuda">

    </button>

</div>



<!-- GUIA DE USO -->

<div class="guia" id="guia">


    <div class="guia-contenido">


        <div
            class="guia-pasos"
            id="guiaPaso">

            Paso 1 de 6

        </div>


        <h3 id="guiaTitulo">

            Iniciar de sesion

        </h3>


        <p id="guiaTexto">

            Inicia sesion para acceder a las funciones
            administrativas del sistema.

        </p>


        <div class="guia-botones">


            <button
                id="guiaAnterior"
                class="btn btn-secondary"
                onclick="pasoAnterior()">

                Anterior

            </button>


            <button
                id="guiaSiguiente"
                class="btn btn-primary"
                onclick="pasoSiguiente()">

                Siguiente

            </button>


        </div>


        <button
            class="btn btn-link mt-2"
            onclick="cerrarGuia()">

            Cerrar

        </button>


    </div>

</div>



<!-- CONTENIDO -->

<main class="container py-4">


    <!-- MARCA DE AGUA -->

    <div class="AlmeidaTech">

        <img
            src="../Imagenes/AlmeidaTechPlateado.png"
            alt="AlmeidaTech">

    </div>



    <!-- TÍTULO -->

    <div class="text-center mb-4">

        <h1 class="titulo">

            Panel de Administración

        </h1>


        <h2 class="subtitulo">

            Sistema hospitalario

        </h2>

    </div>



    <!-- FUNCIONES -->

    <div class="row g-3">



        <!-- DOCUMENTOS -->

        <div class="col-12 col-md-6">


            <?php if ($logueado): ?>

                <a
                    href="CRUD.php"
                    class="tarjeta">

            <?php else: ?>

                <div
                    class="tarjeta bloqueada"
                    onclick="mostrarMensajeSesion()">

            <?php endif; ?>


                    <div class="card p-4 text-center">


                        <div class="icono">

                            <img
                                src="../Imagenes/Documento.png"
                                alt="Documentos">

                        </div>


                        <h3>

                         Administrar Documentos

                        </h3>


                        <p class="text-secondary mb-0">


                            <?php if ($logueado): ?>

                                Administre los documentos subidos

                            <?php else: ?>

                                🔒 Inicia sesion para acceder

                            <?php endif; ?>


                        </p>


                    </div>


            <?php if ($logueado): ?>

                </a>

            <?php else: ?>

                </div>

            <?php endif; ?>


        </div>



        <!-- REPOSITORIO -->


<div class="col-12 col-md-6">

    <a href="../HTML/CodigoQR.html" class="tarjeta">

        <div class="card p-4 text-center">

            <div class="icono">
                <img
                    src="../Imagenes/Repositorio.png"
                    alt="Repositorio">
            </div>

            <h3>
                Repositorio
            </h3>

            <p class="text-secondary mb-0">
                Acceda al repositorio
            </p>

        </div>

    </a>

</div>





<!-- ========================= ENCUESTAS ========================= -->

        <div class="col-12 col-md-6">


            <?php if ($logueado): ?>

                <a
                    href="CRUD_encuestas.php"
                    class="tarjeta">

            <?php else: ?>

                <div
                    class="tarjeta bloqueada"
                    onclick="mostrarMensajeSesion()">

            <?php endif; ?>


                    <div class="card p-4 text-center">


                        <div class="icono">

                            <img
                                src="../Imagenes/Encuesta.png"
                                alt="Encuestas">

                        </div>


                        <h3>

                            Gestionar Encuestas

                        </h3>


                        <p class="text-secondary mb-0">


                            <?php if ($logueado): ?>

                                Gestione las encuestas creadas y resultados de la mismas 

                            <?php else: ?>

                                🔒 Inicia sesión para acceder

                            <?php endif; ?>


                        </p>


                    </div>


            <?php if ($logueado): ?>

                </a>

            <?php else: ?>

                </div>

            <?php endif; ?>


        </div>



        <!-- ========================= ENCUESTA ANÓNIMA ========================= -->

        <div class="col-12 col-md-6">


            <a
                href="encuestas.php"
                class="tarjeta">


                <div class="card p-4 text-center">


                    <div class="icono">

                        <img
                            src="../Imagenes/Encuesta_anonima.png"
                            alt="Encuesta anónima">

                    </div>


                    <h3>

                        Encuesta anónima

                    </h3>


                    <p class="text-secondary mb-0">

                        Responda a una encuesta de satisfaccion

                    </p>


                </div>


            </a>


        </div>



        <!-- ====================== TRASLADOS ========================= -->

        <div class="col-12 col-md-6">


            <?php if ($logueado): ?>

                <a
                    href="traslados.php"
                    class="tarjeta">

            <?php else: ?>

                <div
                    class="tarjeta bloqueada"
                    onclick="mostrarMensajeSesion()">

            <?php endif; ?>


                    <div class="card p-4 text-center">


                        <div class="icono">

                            <img
                                src="../Imagenes/Traslado.png"
                                alt="Traslados">

                        </div>


                        <h3>

                            Traslados

                        </h3>


                        <p class="text-secondary mb-0">


                            <?php if ($logueado): ?>

                                Gestione los traslados

                            <?php else: ?>

                                🔒 Inicia sesión para acceder

                            <?php endif; ?>


                        </p>


                    </div>


            <?php if ($logueado): ?>

                </a>

            <?php else: ?>

                </div>

            <?php endif; ?>


        </div>



        <!-- ========================= USUARIOS ========================= -->

        <div class="col-12 col-md-6">


            <?php if ($logueado): ?>

                <a
                    href="usuarios.php"
                    class="tarjeta">

            <?php else: ?>

                <div
                    class="tarjeta bloqueada"
                    onclick="mostrarMensajeSesion()">

            <?php endif; ?>


                    <div class="card p-4 text-center">


                        <div class="icono">

                            <img
                                src="../Imagenes/usuario.png"
                                alt="Usuarios">

                        </div>


                        <h3>

                            Usuarios

                        </h3>


                        <p class="text-secondary mb-0">


                            <?php if ($logueado): ?>

                                Administre los usuarios del sistema

                            <?php else: ?>

                                🔒 Inicia sesión para acceder

                            <?php endif; ?>


                        </p>


                    </div>


            <?php if ($logueado): ?>

                </a>

            <?php else: ?>

                </div>

            <?php endif; ?>


        </div>


    </div>


</main>



<!-- ========================= MODAL DE SESION ========================= -->

<div
    class="modal fade"
    id="modalSesion"
    tabindex="-1"
    aria-hidden="true">


    <div class="modal-dialog modal-dialog-centered">


        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">

                    Acceso restringido

                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">

                </button>

            </div>


            <div class="modal-body text-center">

                <p>

                    No tienes una sesion iniciada para
                    poder acceder a esta funcion.

                </p>


                <p class="text-secondary">

                    Inicie sesion para continuar.

                </p>

            </div>


            <div class="modal-footer">


                <a
                    href="login.php"
                    class="btn btn-primary">

                    Iniciar sesión

                </a>


                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Cancelar

                </button>


            </div>


        </div>

    </div>

</div>



<!-- ========================= JAVASCRIPT ========================= -->

<script>


/* ========================================== MENSAJE DE SESION ========================================== */

function mostrarMensajeSesion() {

    const modal =
        new bootstrap.Modal(
            document.getElementById("modalSesion")
        );

    modal.show();

}



/* ========================================== GUIA DE USO ========================================== */

let pasoActual = 0;


const pasos = [

    {
        titulo: "Inicio de sesion",

        texto:
        "Si eres un usuario registrado, inicia sesion para acceder a las funciones administrativas del sistema."
    },


    {
        titulo: "Documentos",

        texto:
        "Desde esta seccion puedes administrar los documentos del hospital, incluyendo su creacion, modificacion y eliminacion."
    },


    {
        titulo: "Repositorio",

        texto:
        "Aqui puedes consultar los documentos disponibles. Tambien puedes acceder a un documento directamente mediante su codigo QR."
    },


    {
        titulo: "Encuesta anonima",

        texto:
        "Esta seccion permite responder las encuestas sin necesidad de iniciar sesion."
    },


    {
        titulo: "Traslados",

        texto:
        "Desde aqui puedes gestionar los traslados de pacientes, incluyendo los vehiculos, conductores y enfermeros correspondientes."
    },


    {
        titulo: "Usuarios",

        texto:
        "Esta seccion permite administrar y ver los usuarios registrados en el sistema."
    }

];



function iniciarGuia() {

    pasoActual = 0;

    document.getElementById("guia").style.display = "flex";

    mostrarPaso();

}



function mostrarPaso() {

    const paso = pasos[pasoActual];


    document.getElementById("guiaPaso").textContent =
        "Paso " + (pasoActual + 1) + " de " + pasos.length;


    document.getElementById("guiaTitulo").textContent =
        paso.titulo;


    document.getElementById("guiaTexto").textContent =
        paso.texto;



    /* BOTÓN ANTERIOR */

    if (pasoActual === 0) {

        document.getElementById("guiaAnterior").style.display =
            "none";

    } else {

        document.getElementById("guiaAnterior").style.display =
            "block";

    }



    /* BOTÓN SIGUIENTE */

    if (pasoActual === pasos.length - 1) {

        document.getElementById("guiaSiguiente").textContent =
            "Finalizar";

    } else {

        document.getElementById("guiaSiguiente").textContent =
            "Siguiente";

    }

}



function pasoSiguiente() {

    if (pasoActual < pasos.length - 1) {

        pasoActual++;

        mostrarPaso();

    } else {

        cerrarGuia();

    }

}



function pasoAnterior() {

    if (pasoActual > 0) {

        pasoActual--;

        mostrarPaso();

    }

}



function cerrarGuia() {

    document.getElementById("guia").style.display =
        "none";

}


</script>



<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>


