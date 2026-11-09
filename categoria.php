<?php
require_once __DIR__ . "/conexion.php";

class Categoria {

    private $bd;

    public function __construct() {
        $this->bd = Conexion::obtener();
    }

    // Devuelve todas las categorias ordenadas por nombre
    public function obtenerTodas() {
        $sql = "SELECT Id_categoria, Nombre_categoria
                FROM categoria
                ORDER BY Nombre_categoria";
        return $this->bd->query($sql)->fetchAll();
    }
}
