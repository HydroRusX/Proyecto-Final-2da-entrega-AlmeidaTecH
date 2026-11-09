<?php
require_once "sesion.php";

$logueado = isset($_SESSION['usuario']);

if ($logueado) {
    $usuario = $_SESSION['usuario'];
    $tipo_usuario = $_SESSION['tipo_usuario'];
} else {
    $usuario = "Guest";
    $tipo_usuario = "";
}

require_once "../vistas/inicio.php";
