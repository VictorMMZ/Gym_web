<?php
// Clase ClaseHelper: gestiona las operaciones de clases e inscripciones para Gym_Web
require_once __DIR__ . '/../config/db.php';

class ClaseHelper {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;

        // Asegurar que el campo estado de inscripciones soporte el valor 'finalizado'
        $this->asegurarEnumFinalizado();

        // Actualizar inscripciones de clases antiguas a estado finalizado
        $this->finalizarClasesPasadas();
    }

    // Comprueba la definición de la columna estado y agrega 'finalizado' si falta
    private function asegurarEnumFinalizado() {
        $stmt = $this->pdo->query("SHOW COLUMNS FROM inscripciones LIKE 'estado'");
        $column = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($column && strpos($column['Type'], 'finalizado') === false) {
            $this->pdo->exec("ALTER TABLE inscripciones MODIFY estado ENUM('activa','cancelada','finalizado') DEFAULT 'activa'");
        }
    }

    // Pone en estado finalizado todas las inscripciones activas de clases cuyo horario ya pasó
    public function finalizarClasesPasadas() {
        $stmt = $this->pdo->prepare(
            "UPDATE inscripciones i
             JOIN clases c ON i.id_clase = c.id_clase
             SET i.estado = 'finalizado'
             WHERE i.estado = 'activa' AND c.fecha_hora < NOW()"
        );
        return $stmt->execute();
    }

    // Obtener todas las clases ordenadas por fecha
    public function obtenerClases() {
        $stmt = $this->pdo->query("SELECT * FROM clases ORDER BY fecha_hora");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una clase concreta por su id
    public function obtenerClasePorId($id_clase) {
        $stmt = $this->pdo->prepare("SELECT * FROM clases WHERE id_clase = ?");
        $stmt->execute([$id_clase]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear una nueva clase en la base de datos
    public function crearClase($id_gimnasio, $nombre, $descripcion, $fecha_hora, $capacidad) {
        $stmt = $this->pdo->prepare("INSERT INTO clases (id_gimnasio, nombre, descripcion, fecha_hora, capacidad) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$id_gimnasio, $nombre, $descripcion, $fecha_hora, $capacidad]);
    }

    // Actualizar los datos de una clase existente
    public function actualizarClase($id_clase, $id_gimnasio, $nombre, $descripcion, $fecha_hora, $capacidad) {
        $stmt = $this->pdo->prepare("UPDATE clases SET id_gimnasio = ?, nombre = ?, descripcion = ?, fecha_hora = ?, capacidad = ? WHERE id_clase = ?");
        return $stmt->execute([$id_gimnasio, $nombre, $descripcion, $fecha_hora, $capacidad, $id_clase]);
    }

    // Eliminar clase por id
    public function eliminarClase($id_clase) {
        $stmt = $this->pdo->prepare("DELETE FROM clases WHERE id_clase = ?");
        return $stmt->execute([$id_clase]);
    }

    // Inscribir un usuario en una clase si hay cupo disponible y no está ya inscrito
    public function inscribirUsuario($id_usuario, $id_clase) {
        $clase = $this->obtenerClasePorId($id_clase);
        if (!$clase) return "Clase no encontrada.";
        if ($clase['cupos'] >= $clase['capacidad']) return "Clase llena.";

        $stmt = $this->pdo->prepare("SELECT id_inscripcion FROM inscripciones WHERE id_usuario = ? AND id_clase = ? AND estado = 'activa'");
        $stmt->execute([$id_usuario, $id_clase]);
        if ($stmt->fetch()) return "Ya inscrito en esta clase.";

        $stmt = $this->pdo->prepare("INSERT INTO inscripciones (id_usuario, id_clase) VALUES (?, ?)");
        if ($stmt->execute([$id_usuario, $id_clase])) {
            $this->pdo->prepare("UPDATE clases SET cupos = cupos + 1 WHERE id_clase = ?")->execute([$id_clase]);
            return true;
        }
        return "Error al inscribir.";
    }

    // Cancelar una inscripción activa y ajustar el número de cupos
    public function cancelarInscripcion($id_usuario, $id_clase) {
        $stmt = $this->pdo->prepare("DELETE FROM inscripciones WHERE id_usuario = ? AND id_clase = ? AND estado = 'activa'");
        if ($stmt->execute([$id_usuario, $id_clase])) {
            $this->pdo->prepare("UPDATE clases SET cupos = cupos - 1 WHERE id_clase = ?")->execute([$id_clase]);
            return true;
        }
        return "Error al cancelar.";
    }

    // Obtener las clases activas en las que está inscrito el usuario
    public function obtenerClasesUsuario($id_usuario) {
        $stmt = $this->pdo->prepare(
            "SELECT c.* FROM clases c
            JOIN inscripciones i ON c.id_clase = i.id_clase
            WHERE i.id_usuario = ? AND i.estado = 'activa'
            ORDER BY c.fecha_hora"
        );
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
