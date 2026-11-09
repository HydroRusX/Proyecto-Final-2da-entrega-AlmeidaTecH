<?php
require_once __DIR__ . "/conexion.php";

class Documento {

    private $bd;

    public function __construct() {
        $this->bd = Conexion::obtener();
    }

    // Todos los documentos (activos y dados de baja), para la administracion
    public function obtenerTodos() {
        return $this->listar(false);
    }

    // Solo los documentos activos, para el repositorio publico
    public function obtenerActivos() {
        return $this->listar(true);
    }

    // Arma la lista de documentos con el usuario que los subio y sus categorias
    private function listar($soloActivos) {
        $sql = "SELECT d.Id_documento, d.Titulo, d.Archivo, d.Fecha_carga, d.Activo,
                       u.Nombre, u.Apellido
                FROM documento d
                LEFT JOIN usuario u ON d.Id_usuario = u.Id_usuario";

        if ($soloActivos) {
            $sql .= " WHERE d.Activo = 1";
        }

        $sql .= " ORDER BY d.Id_documento DESC";

        $documentos = $this->bd->query($sql)->fetchAll();

        // Categorias de todos los documentos, agrupadas por documento
        $sqlCategorias = "SELECT dc.Id_documento, c.Nombre_categoria
                          FROM documento_categoria dc
                          INNER JOIN categoria c ON dc.Id_categoria = c.Id_categoria";

        $porDocumento = [];
        foreach ($this->bd->query($sqlCategorias) as $fila) {
            $porDocumento[$fila['Id_documento']][] = $fila['Nombre_categoria'];
        }

        foreach ($documentos as $i => $documento) {
            $documentos[$i]['categorias'] = $porDocumento[$documento['Id_documento']] ?? [];
        }

        return $documentos;
    }

    // Devuelve un documento (false si no existe)
    public function obtenerPorId($id) {
        $sql = "SELECT Id_documento, Titulo, Archivo, Activo
                FROM documento
                WHERE Id_documento = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Devuelve los ids de las categorias de un documento
    public function obtenerIdsCategorias($id) {
        $sql = "SELECT Id_categoria FROM documento_categoria WHERE Id_documento = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->execute([$id]);
        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    // Crea el documento y sus categorias
    public function crear($titulo, $archivo, $idUsuario, $categorias) {
        try {
            $this->bd->beginTransaction();

            $sql = "INSERT INTO documento (Titulo, Archivo, Fecha_carga, Id_usuario)
                    VALUES (?, ?, NOW(), ?)";
            $stmt = $this->bd->prepare($sql);
            $stmt->execute([$titulo, $archivo, $idUsuario]);

            $idDocumento = $this->bd->lastInsertId();
            $this->guardarCategorias($idDocumento, $categorias);

            $this->bd->commit();
            return $idDocumento;

        } catch (Exception $e) {
            if ($this->bd->inTransaction()) {
                $this->bd->rollBack();
            }
            throw $e;
        }
    }

    // Actualiza titulo, archivo y categorias de un documento
    public function actualizar($id, $titulo, $archivo, $categorias) {
        try {
            $this->bd->beginTransaction();

            $sql = "UPDATE documento SET Titulo = ?, Archivo = ? WHERE Id_documento = ?";
            $stmt = $this->bd->prepare($sql);
            $stmt->execute([$titulo, $archivo, $id]);

            $stmt = $this->bd->prepare("DELETE FROM documento_categoria WHERE Id_documento = ?");
            $stmt->execute([$id]);

            $this->guardarCategorias($id, $categorias);

            $this->bd->commit();

        } catch (Exception $e) {
            if ($this->bd->inTransaction()) {
                $this->bd->rollBack();
            }
            throw $e;
        }
    }

    // Baja logica (activo = 0) o reactivacion (activo = 1)
    public function cambiarEstado($id, $activo) {
        $sql = "UPDATE documento SET Activo = ? WHERE Id_documento = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->execute([$activo, $id]);
    }

    // Baja fisica: borra el documento y sus categorias de la base de datos
    public function eliminar($id) {
        try {
            $this->bd->beginTransaction();

            $stmt = $this->bd->prepare("DELETE FROM documento_categoria WHERE Id_documento = ?");
            $stmt->execute([$id]);

            $stmt = $this->bd->prepare("DELETE FROM documento WHERE Id_documento = ?");
            $stmt->execute([$id]);

            $this->bd->commit();

        } catch (Exception $e) {
            if ($this->bd->inTransaction()) {
                $this->bd->rollBack();
            }
            throw $e;
        }
    }

    // Inserta las categorias de un documento
    private function guardarCategorias($idDocumento, $categorias) {
        $sql = "INSERT INTO documento_categoria (Id_documento, Id_categoria) VALUES (?, ?)";
        $stmt = $this->bd->prepare($sql);

        foreach (array_unique(array_map('intval', $categorias)) as $idCategoria) {
            $stmt->execute([$idDocumento, $idCategoria]);
        }
    }
}
