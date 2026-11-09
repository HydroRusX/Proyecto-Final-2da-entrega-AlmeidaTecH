<?php
// Tarjetas del panel principal (las protegidas necesitan sesion iniciada)
$tarjetas = [
    [
        'url' => 'documento_controlador.php?accion=listar',
        'icono' => 'Documento.png',
        'alt' => 'Documentos',
        'titulo' => 'Administrar Documentos',
        'texto' => 'Administre los documentos subidos',
        'protegida' => true
    ],
    [
        'url' => '../CodigoQR.html',
        'icono' => 'Repositorio.png',
        'alt' => 'Repositorio',
        'titulo' => 'Repositorio',
        'texto' => 'Acceda al repositorio',
        'protegida' => false
    ],
    [
        'url' => 'encuesta_controlador.php?accion=listar',
        'icono' => 'Encuesta.png',
        'alt' => 'Encuestas',
        'titulo' => 'Gestionar Encuestas',
        'texto' => 'Gestione las encuestas creadas y los resultados de las mismas',
        'protegida' => true
    ],
    [
        'url' => 'encuesta_controlador.php?accion=publicas',
        'icono' => 'Encuesta_anonima.png',
        'alt' => 'Encuesta anónima',
        'titulo' => 'Encuesta anónima',
        'texto' => 'Responda a una encuesta de satisfacción',
        'protegida' => false
    ],
    [
        'url' => 'desarrollo_controlador.php',
        'icono' => 'Traslado.png',
        'alt' => 'Traslados',
        'titulo' => 'Traslados',
        'texto' => 'Gestione los traslados',
        'protegida' => true
    ],
    [
        'url' => 'desarrollo_controlador.php',
        'icono' => 'usuario.png',
        'alt' => 'Usuarios',
        'titulo' => 'Usuarios',
        'texto' => 'Administre los usuarios del sistema',
        'protegida' => true
    ]
];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Hospitalario</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS propio -->
    <link rel="stylesheet" href="../CSS/index.css">
</head>

<body>

<!-- ========================= HEADER ========================= -->

<header class="d-flex align-items-center justify-content-between px-3">

    <div class="logo">
        <img src="../Imagenes/Clinicas.jpg" alt="Logo Clínicas">
    </div>

    <div class="d-flex align-items-center gap-2">

        <?php if ($logueado): ?>

            <span class="usuario"><?php echo htmlspecialchars($usuario); ?></span>

            <button
                type="button"
                class="btn btn-outline-danger btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#modalCerrarSesion">
                Cerrar sesión
            </button>

        <?php else: ?>

            <span class="usuario">Invitado</span>

            <a href="usuario_controlador.php?accion=login" class="btn btn-outline-primary btn-sm">
                Iniciar sesión
            </a>

        <?php endif; ?>

    </div>

</header>


<!-- ========================= BOTON DE AYUDA ========================= -->

<div class="boton-ayuda">
    <button onclick="iniciarGuia()" title="¿Cómo utilizar la aplicación?">
        <img src="../Imagenes/Duda.png" alt="Ayuda">
    </button>
</div>


<!-- ========================= GUIA DE USO ========================= -->

<div class="guia" id="guia">
    <div class="guia-contenido">

        <div class="guia-pasos" id="guiaPaso">Paso 1 de 6</div>

        <h3 id="guiaTitulo">Inicio de sesion</h3>

        <p id="guiaTexto">
            Inicia sesion para acceder a las funciones administrativas del sistema.
        </p>

        <div class="guia-botones">
            <button id="guiaAnterior" class="btn btn-secondary" onclick="pasoAnterior()">Anterior</button>
            <button id="guiaSiguiente" class="btn btn-primary" onclick="pasoSiguiente()">Siguiente</button>
        </div>

        <button class="btn btn-link mt-2" onclick="cerrarGuia()">Cerrar</button>

    </div>
</div>


<!-- ========================= CONTENIDO ========================= -->

<main class="container py-4">

    <!-- MARCA DE AGUA -->
    <div class="AlmeidaTech">
        <img src="../Imagenes/AlmeidaTechPlateado.png" alt="AlmeidaTech">
    </div>

    <!-- TITULO -->
    <div class="text-center mb-4">
        <h1 class="titulo">Panel de Administración</h1>
        <h2 class="subtitulo">Sistema hospitalario</h2>
    </div>

    <!-- FUNCIONES -->
    <div class="row g-3">

        <?php foreach ($tarjetas as $tarjeta): ?>

            <?php $permitida = (!$tarjeta['protegida'] || $logueado); ?>

            <div class="col-12 col-md-6">

                <?php if ($permitida): ?>
                    <a href="<?php echo $tarjeta['url']; ?>" class="tarjeta">
                <?php else: ?>
                    <div class="tarjeta bloqueada" onclick="mostrarMensajeSesion()">
                <?php endif; ?>

                    <div class="card p-4 text-center">

                        <div class="icono">
                            <img src="../Imagenes/<?php echo $tarjeta['icono']; ?>" alt="<?php echo $tarjeta['alt']; ?>">
                        </div>

                        <h3><?php echo $tarjeta['titulo']; ?></h3>

                        <p class="text-secondary mb-0">
                            <?php
                            if ($permitida) {
                                echo $tarjeta['texto'];
                            } else {
                                echo '🔒 Inicia sesión para acceder';
                            }
                            ?>
                        </p>

                    </div>

                <?php if ($permitida): ?>
                    </a>
                <?php else: ?>
                    </div>
                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    </div>

</main>


<!-- ========================= MODAL CERRAR SESION ========================= -->

<div class="modal fade" id="modalCerrarSesion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Cerrar sesión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body text-center">
                <p class="mb-0">¿Estás seguro de que querés cerrar sesión?</p>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="usuario_controlador.php?accion=logout" class="btn btn-danger">Sí, cerrar sesión</a>
            </div>

        </div>
    </div>
</div>


<!-- ========================= MODAL ACCESO RESTRINGIDO ========================= -->

<div class="modal fade" id="modalSesion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Acceso restringido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                <p>No tienes una sesion iniciada para poder acceder a esta funcion.</p>
                <p class="text-secondary">Inicie sesion para continuar.</p>
            </div>

            <div class="modal-footer">
                <a href="usuario_controlador.php?accion=login" class="btn btn-primary">Iniciar sesión</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

/* ============ MENSAJE DE SESION ============ */

function mostrarMensajeSesion() {
    const modal = new bootstrap.Modal(document.getElementById("modalSesion"));
    modal.show();
}


/* ============ GUIA DE USO ============ */

let pasoActual = 0;

const pasos = [
    {
        titulo: "Inicio de sesion",
        texto: "Si eres un usuario registrado, inicia sesion para acceder a las funciones administrativas del sistema."
    },
    {
        titulo: "Documentos",
        texto: "Desde esta seccion puedes administrar los documentos del hospital, incluyendo su creacion, modificacion y eliminacion."
    },
    {
        titulo: "Repositorio",
        texto: "Aqui puedes consultar los documentos disponibles. Tambien puedes acceder a un documento directamente mediante su codigo QR."
    },
    {
        titulo: "Encuesta anonima",
        texto: "Esta seccion permite responder las encuestas sin necesidad de iniciar sesion."
    },
    {
        titulo: "Traslados",
        texto: "Desde aqui puedes gestionar los traslados de pacientes, incluyendo los vehiculos, conductores y enfermeros correspondientes."
    },
    {
        titulo: "Usuarios",
        texto: "Esta seccion permite administrar y ver los usuarios registrados en el sistema."
    }
];

function iniciarGuia() {
    pasoActual = 0;
    document.getElementById("guia").style.display = "flex";
    mostrarPaso();
}

function mostrarPaso() {

    const paso = pasos[pasoActual];

    document.getElementById("guiaPaso").textContent = "Paso " + (pasoActual + 1) + " de " + pasos.length;
    document.getElementById("guiaTitulo").textContent = paso.titulo;
    document.getElementById("guiaTexto").textContent = paso.texto;

    /* BOTON ANTERIOR */
    if (pasoActual === 0) {
        document.getElementById("guiaAnterior").style.display = "none";
    } else {
        document.getElementById("guiaAnterior").style.display = "block";
    }

    /* BOTON SIGUIENTE */
    if (pasoActual === pasos.length - 1) {
        document.getElementById("guiaSiguiente").textContent = "Finalizar";
    } else {
        document.getElementById("guiaSiguiente").textContent = "Siguiente";
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
    document.getElementById("guia").style.display = "none";
}

</script>

</body>
</html>
