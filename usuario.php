<?php
require_once __DIR__ . "/conexion.php";

class Usuario {

    private $bd;

    public function __construct() {
        $this->bd = Conexion::obtener();
    }

    // Busca un usuario por su email (devuelve false si no existe)
    public function buscarPorEmail($email) {
        $sql = "SELECT Id_usuario, Nombre, Apellido, Email, Contraseña, Tipo_usuario
                FROM usuario
                WHERE Email = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
}
