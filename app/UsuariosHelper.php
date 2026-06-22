<?php
// Clase UsuariosHelper: gestiona las operaciones de usuarios 
require_once __DIR__ . '/../config/db.php';

class UsuariosHelper {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function obtenerUsuarios() {
        $stmt = $this->pdo->query("SELECT * FROM usuarios ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUsuarioPorId($id_usuario) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

     // Crear un nuevo usuario en la base de datos
    public function crearUsuario($id_usuario, $nombre, $apellidos, $correo, $telefono, $contraseña, $rol,$fecha_registro, $id_plan) {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (id_usuario, nombre, apellidos, correo, telefono, contraseña, rol, fecha_registro, id_plan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$id_usuario, $nombre, $apellidos, $correo, $telefono, $contraseña, $rol, $fecha_registro, $id_plan]);
    }

    // Actualizar los datos de un usuario existente
    public function actualizarUsuario($id_usuario, $nombre, $apellidos, $correo, $telefono, $contraseña, $rol, $fecha_registro, $id_plan) {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET nombre = ?, apellidos = ?, correo = ?, telefono = ?, contraseña = ?, rol = ?, fecha_registro = ?, id_plan = ? WHERE id_usuario = ?");
        return $stmt->execute([$nombre, $apellidos, $correo, $telefono, $contraseña, $rol, $fecha_registro, $id_plan, $id_usuario]);
    }

    // Eliminar usuario por id
    public function eliminarUsuario($id_usuario) {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        return $stmt->execute([$id_usuario]);
    }
}