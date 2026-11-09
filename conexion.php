<?php
// Clase que crea la conexion con la base de datos usando PDO
class Conexion {

    public static function obtener() {
        $bd = new PDO(
            'mysql:host=localhost;dbname=projecto;charset=utf8mb4',
            'root',
            '',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $bd;
    }
}
