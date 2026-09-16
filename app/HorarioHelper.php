<?php
require_once __DIR__ . '/../config/db.php';

class HorarioHelper {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function obtenerHoraApertura() {
        $stmt = $this->pdo->query("SELECT horario_apertura FROM gimnasio");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerHoraCierre() {
        $stmt = $this->pdo->query("SELECT horario_cierre FROM gimnasio");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarHorario($nuevo_horario_apertura, $nuevo_horario_cierre) {
        $stmt = $this->pdo->prepare("UPDATE gimnasio SET horario_apertura = :nuevo_horario_apertura, horario_cierre = :nuevo_horario_cierre");
        $stmt->bindParam(':nuevo_horario_apertura', $nuevo_horario_apertura);
        $stmt->bindParam(':nuevo_horario_cierre', $nuevo_horario_cierre);
        return $stmt->execute();
    }

} 

?>