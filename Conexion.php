<?php
	$host = "localhost";
	$usuario = "root";
	$contra = "";
	$bd = "projecto";
	
	// Crear conexión
	$conexion= new mysqli($host, $usuario, $contra, $bd);
    
//Prueba de conexion
//echo "PHP funciona correctamente";
	
	// Verificar conexion
	if ($conexion->connect_errno) {
		die("Error con la conexión: " . $conexion->connect_error);
	}
