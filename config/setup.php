<?php
// setup.php: crea las tablas necesarias y datos iniciales de Gym_Web
require_once 'db.php';

try {
    // Crear tabla gimnasio si no existe
    $sqlGimnasio = "
    CREATE TABLE IF NOT EXISTS gimnasio (
        id_gimnasio INT AUTO_INCREMENT PRIMARY KEY,
        Nombre VARCHAR(45) NOT NULL,
        Horario VARCHAR(45)
    ) ENGINE=InnoDB;
    ";
    $pdo->exec($sqlGimnasio);

    // Crear tabla planes si no existe
    $sqlPlanes = "
    CREATE TABLE IF NOT EXISTS planes (
        id_plan INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(50) NOT NULL,
        precio DECIMAL(10,2) NOT NULL
    ) ENGINE=InnoDB;
    ";
    $pdo->exec($sqlPlanes);

    // Insertar planes básicos si no hay datos en la tabla
    $stmtPlanes = $pdo->prepare("SELECT COUNT(*) FROM planes");
    $stmtPlanes->execute();
    if ($stmtPlanes->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO planes (id_plan, nombre, precio) VALUES
            (1, 'Básico', 29.99),
            (2, 'Pro', 32.99),
            (3, 'Premium', 35.99)");
    }

    // Crear tabla usuarios si no existe
    $sqlUsers = "
    CREATE TABLE IF NOT EXISTS usuarios (
        id_usuario INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(45) NOT NULL,
        apellidos VARCHAR(90),
        correo VARCHAR(90) NOT NULL UNIQUE,
        telefono VARCHAR(20),
        contraseña VARCHAR(255) NOT NULL,
        rol ENUM('admin', 'usuario', 'entrenador') DEFAULT 'usuario',
        id_plan INT NULL,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (id_plan) REFERENCES planes(id_plan) ON DELETE SET NULL,
        INDEX idx_correo (correo),
        INDEX idx_id_plan (id_plan)
    ) ENGINE=InnoDB;
    ";
    $pdo->exec($sqlUsers);

    // Crear tabla clases si no existe
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

    // Crear tabla inscripciones si no existe
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

    // Crear tabla facturación si no existe
    $sqlFacturacion = "
    CREATE TABLE IF NOT EXISTS facturacion (
        id_facturacion INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT NOT NULL,
        id_plan INT NULL,
        facturado TINYINT(1) DEFAULT 0,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_usuario_facturacion (id_usuario),
        INDEX idx_facturado (facturado)
    ) ENGINE=InnoDB;
    ";
    $pdo->exec($sqlFacturacion);

    // Crear tabla mensajes de usuarios si no existe
    $sqlMensajes = "
    CREATE TABLE IF NOT EXISTS mensajes_usuarios (
        id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
        nombre_usuario VARCHAR(100) NOT NULL,
        correo_usuario VARCHAR(150) NOT NULL,
        mensaje TEXT NOT NULL,
        fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;
    ";
    $pdo->exec($sqlMensajes);

    // Insertar datos de ejemplo del gimnasio si falta el registro
    $stmt = $pdo->prepare("SELECT id_gimnasio FROM gimnasio LIMIT 1");
    $stmt->execute();
    if (!$stmt->fetch()) {
        $pdo->exec("INSERT INTO gimnasio (nombre, horario) VALUES ('Gym Web', 'Lun-Vie 06:00-22:00')");
    }

} catch (PDOException $e) {
    // Error en la creación de tablas: en producción no se muestra el mensaje completo
}
?>