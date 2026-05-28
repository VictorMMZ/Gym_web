<?php
// Script para configurar la base de datos (asume que gym_web ya existe)
require_once 'db.php';

try {
    // Crear tabla gimnasio
    $sqlGimnasio = "
    CREATE TABLE IF NOT EXISTS gimnasio (
        id_gimnasio INT AUTO_INCREMENT PRIMARY KEY,
        Nombre VARCHAR(45) NOT NULL,
        Horario VARCHAR(45)
    ) ENGINE=InnoDB;
    ";
    $pdo->exec($sqlGimnasio);

    // Crear tabla usuarios
    $sqlUsers = "
    CREATE TABLE IF NOT EXISTS usuarios (
        id_usuario INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(45) NOT NULL,
        apellidos VARCHAR(90),
        correo VARCHAR(90) NOT NULL UNIQUE,
        telefono VARCHAR(20),
        contraseña VARCHAR(255) NOT NULL,
        rol ENUM('admin', 'usuario', 'entrenador') DEFAULT 'usuario',
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_correo (correo)
    ) ENGINE=InnoDB;
    ";
    $pdo->exec($sqlUsers);

    // Crear tabla clases
    $sqlClases = "
    CREATE TABLE IF NOT EXISTS clases (
        id_clase INT AUTO_INCREMENT PRIMARY KEY,
        id_gimnasio INT,
        nombre VARCHAR(45) NOT NULL,
        descripcion VARCHAR(300),
        fecha_hora DATETIME NOT NULL,
        capacidad INT DEFAULT 20,
        cupos INT DEFAULT 0,
        FOREIGN KEY (id_gimnasio) REFERENCES gimnasio(id_gimnasio) ON DELETE CASCADE,
        INDEX idx_fecha_hora (fecha_hora)
    ) ENGINE=InnoDB;
    ";
    $pdo->exec($sqlClases);

    // Crear tabla inscripciones
    $sqlInscripciones = "
    CREATE TABLE IF NOT EXISTS inscripciones (
        id_inscripcion INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT NOT NULL,
        id_clase INT NOT NULL,
        fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        estado ENUM('activa', 'cancelada', 'finalizado') DEFAULT 'activa',
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
        FOREIGN KEY (id_clase) REFERENCES clases(id_clase) ON DELETE CASCADE,
        UNIQUE KEY unique_inscripcion (id_usuario, id_clase),
        INDEX idx_usuario_clase (id_usuario, id_clase)
    ) ENGINE=InnoDB;
    ";
    $pdo->exec($sqlInscripciones);

    // Insertar datos de ejemplo en gimnasio
    $stmt = $pdo->prepare("SELECT id_gimnasio FROM gimnasio LIMIT 1");
    $stmt->execute();
    if (!$stmt->fetch()) {
        $pdo->exec("INSERT INTO gimnasio (nombre, horario) VALUES ('Gym Web', 'Lun-Vie 06:00-22:00')");
    }

    // Insertar usuario admin por defecto si no existe (contraseña: admin123)
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = 'admin@gymweb.com'");
    $stmt->execute();
    if (!$stmt->fetch()) {
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellidos, correo, telefono, contraseña, rol) VALUES ('Administrador', '', 'admin@gymweb.com', '123456789', ?, 'admin')");
        $stmt->execute([$hashedPassword]);
        echo "Usuario admin creado. Correo: admin@gymweb.com, Contraseña: admin123<br>";
    }

   // echo "Base de datos configurada correctamente con tablas: gimnasio, users, clases, inscripciones.";
} catch (PDOException $e) {
   // echo "Error: " . $e->getMessage();
}
?>