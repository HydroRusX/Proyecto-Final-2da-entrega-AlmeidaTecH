<?php
require_once __DIR__ . "/conexion.php";

class Encuesta {

    private $bd;

    public function __construct() {
        $this->bd = Conexion::obtener();
    }

    // Todas las encuestas, la mas nueva primero
    public function obtenerTodas() {
        $sql = "SELECT Id_encuesta, Titulo, Descripcion, Segmentada
                FROM encuesta
                ORDER BY Id_encuesta DESC";
        return $this->bd->query($sql)->fetchAll();
    }

    // Devuelve una encuesta (false si no existe)
    public function obtenerPorId($id) {
        $sql = "SELECT Id_encuesta, Titulo, Descripcion, Segmentada
                FROM encuesta
                WHERE Id_encuesta = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Preguntas de una encuesta
    public function obtenerPreguntas($idEncuesta) {
        $sql = "SELECT Id_pregunta, Texto
                FROM pregunta
                WHERE Id_encuesta = ?
                ORDER BY Id_pregunta";
        $stmt = $this->bd->prepare($sql);
        $stmt->execute([$idEncuesta]);
        return $stmt->fetchAll();
    }

    // Crea la encuesta con sus preguntas
    public function crear($titulo, $descripcion, $segmentada, $preguntas) {
        try {
            $this->bd->beginTransaction();

            $sql = "INSERT INTO encuesta (Titulo, Descripcion, Segmentada) VALUES (?, ?, ?)";
            $stmt = $this->bd->prepare($sql);
            $stmt->execute([$titulo, $descripcion, $segmentada]);

            $idEncuesta = $this->bd->lastInsertId();

            $stmtPregunta = $this->bd->prepare("INSERT INTO pregunta (Texto, Id_encuesta) VALUES (?, ?)");
            foreach ($preguntas as $texto) {
                $stmtPregunta->execute([$texto, $idEncuesta]);
            }

            $this->bd->commit();
            return $idEncuesta;

        } catch (Exception $e) {
            if ($this->bd->inTransaction()) {
                $this->bd->rollBack();
            }
            throw $e;
        }
    }

    // Actualiza la encuesta. Las preguntas que ya no vienen en el formulario se borran
    // (con sus respuestas), las que tienen id se actualizan y las que tienen id 0 se crean.
    public function actualizar($id, $titulo, $descripcion, $segmentada, $idsPreguntas, $textos) {
        try {
            $this->bd->beginTransaction();

            $sql = "UPDATE encuesta SET Titulo = ?, Descripcion = ?, Segmentada = ? WHERE Id_encuesta = ?";
            $stmt = $this->bd->prepare($sql);
            $stmt->execute([$titulo, $descripcion, $segmentada, $id]);

            // Preguntas que existen ahora en la base de datos
            $stmt = $this->bd->prepare("SELECT Id_pregunta FROM pregunta WHERE Id_encuesta = ?");
            $stmt->execute([$id]);
            $idsExistentes = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));

            // Ids que llegaron desde el formulario
            $idsRecibidos = [];
            foreach ($idsPreguntas as $idPregunta) {
                $idPregunta = intval($idPregunta);
                if ($idPregunta > 0) {
                    $idsRecibidos[] = $idPregunta;
                }
            }

            // Borro las preguntas que ya no estan en el formulario
            $borrarRespuestas = $this->bd->prepare("DELETE FROM respuesta WHERE Id_pregunta = ?");
            $borrarPregunta = $this->bd->prepare("DELETE FROM pregunta WHERE Id_pregunta = ? AND Id_encuesta = ?");

            foreach ($idsExistentes as $idExistente) {
                if (!in_array($idExistente, $idsRecibidos)) {
                    $borrarRespuestas->execute([$idExistente]);
                    $borrarPregunta->execute([$idExistente, $id]);
                }
            }

            // Actualizo las preguntas existentes y creo las nuevas
            $actualizar = $this->bd->prepare("UPDATE pregunta SET Texto = ? WHERE Id_pregunta = ? AND Id_encuesta = ?");
            $nueva = $this->bd->prepare("INSERT INTO pregunta (Texto, Id_encuesta) VALUES (?, ?)");

            for ($i = 0; $i < count($textos); $i++) {

                $texto = trim($textos[$i]);

                if ($texto === '') {
                    continue;
                }

                $idPregunta = isset($idsPreguntas[$i]) ? intval($idsPreguntas[$i]) : 0;

                if ($idPregunta > 0) {
                    $actualizar->execute([$texto, $idPregunta, $id]);
                } else {
                    $nueva->execute([$texto, $id]);
                }
            }

            $this->bd->commit();

        } catch (Exception $e) {
            if ($this->bd->inTransaction()) {
                $this->bd->rollBack();
            }
            throw $e;
        }
    }

    // Borra la encuesta, sus preguntas y las respuestas de esas preguntas
    public function eliminar($id) {
        try {
            $this->bd->beginTransaction();

            $sql = "DELETE FROM respuesta
                    WHERE Id_pregunta IN (SELECT Id_pregunta FROM pregunta WHERE Id_encuesta = ?)";
            $stmt = $this->bd->prepare($sql);
            $stmt->execute([$id]);

            $stmt = $this->bd->prepare("DELETE FROM pregunta WHERE Id_encuesta = ?");
            $stmt->execute([$id]);

            $stmt = $this->bd->prepare("DELETE FROM encuesta WHERE Id_encuesta = ?");
            $stmt->execute([$id]);

            $this->bd->commit();

        } catch (Exception $e) {
            if ($this->bd->inTransaction()) {
                $this->bd->rollBack();
            }
            throw $e;
        }
    }

    // Guarda las respuestas de una persona. Recibe un arreglo: id_pregunta => valor (1 a 5)
    public function guardarRespuestas($valores) {
        try {
            $this->bd->beginTransaction();

            $sql = "INSERT INTO respuesta (Fecha_respuesta, Valor_satisfaccion, Id_pregunta)
                    VALUES (NOW(), ?, ?)";
            $stmt = $this->bd->prepare($sql);

            foreach ($valores as $idPregunta => $valor) {
                $stmt->execute([$valor, $idPregunta]);
            }

            $this->bd->commit();

        } catch (Exception $e) {
            if ($this->bd->inTransaction()) {
                $this->bd->rollBack();
            }
            throw $e;
        }
    }

    // Cantidad de respuestas y promedio de satisfaccion por pregunta
    public function obtenerResultados($idEncuesta) {
        $sql = "SELECT p.Id_pregunta, p.Texto,
                       COUNT(r.Id_respuesta) AS cantidad_respuestas,
                       AVG(r.Valor_satisfaccion) AS promedio
                FROM pregunta p
                LEFT JOIN respuesta r ON p.Id_pregunta = r.Id_pregunta
                WHERE p.Id_encuesta = ?
                GROUP BY p.Id_pregunta, p.Texto
                ORDER BY p.Id_pregunta";
        $stmt = $this->bd->prepare($sql);
        $stmt->execute([$idEncuesta]);
        return $stmt->fetchAll();
    }
}
