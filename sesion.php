<?php
// Archivo comun a todos los controladores: inicia la sesion y define los permisos
session_start();

// Si no hay sesion iniciada, manda al login
function requerirSesion() {
    if (!isset($_SESSION['usuario'])) {
        header("Location: usuario_controlador.php?accion=login&error=sesion");
        exit;
    }
}

// Indica si el usuario que inicio sesion es funcionario (solo puede consultar)
function esFuncionario() {
    return isset($_SESSION['tipo_usuario']) &&
           strtolower(trim($_SESSION['tipo_usuario'])) == 'funcionario';
}

// Si el usuario es funcionario, lo devuelve a la pagina indicada con un error de permisos
function requerirAdministrador($destino) {
    if (esFuncionario()) {
        header("Location: " . $destino . "&error=sin_permisos");
        exit;
    }
}
