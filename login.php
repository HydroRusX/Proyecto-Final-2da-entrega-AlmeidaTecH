<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../CSS/login.css">
</head>

<body>

<div class="login-container">

    <div class="login-card">

        <!-- LOGO -->
        <div class="logo">
            <img src="../Imagenes/Clinicas.jpg" alt="Logo Clínicas">
        </div>

        <!-- TITULO -->
        <div class="titulo">
            <h1>Iniciar sesión</h1>
            <p>Sistema hospitalario</p>
        </div>

        <!-- ERROR -->
        <?php if ($error != ""): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- FORMULARIO -->
        <form method="POST" action="usuario_controlador.php?accion=login" id="formLogin" novalidate onsubmit="return validarLogin()">

            <div id="errores" class="alert alert-danger d-none"></div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    placeholder="Ingrese su email"
                    required>
            </div>

            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input
                    type="password"
                    class="form-control"
                    id="contrasena"
                    name="contrasena"
                    placeholder="Ingrese su contraseña"
                    required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Iniciar sesión</button>
            </div>

        </form>

        <!-- VOLVER -->
        <div class="text-center mt-3">
            <a href="inicio_controlador.php" class="text-decoration-none">Volver al inicio</a>
        </div>

    </div>

</div>

<?php require __DIR__ . "/validaciones_js.php"; ?>

</body>
</html>
