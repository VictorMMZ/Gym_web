<?php
require_once __DIR__ . '/../config/db.php';

// Función para registrar un usuario
function registrarUsuario($correo, $contraseña, $repetirContraseña, $nombre, $apellidos, $telefono) {
    global $pdo;

    // Validar que las contraseñas coincidan
    if ($contraseña !== $repetirContraseña) {
        return "Las contraseñas no coinciden.";
    }


    // Validar longitud mínima
    if (strlen($contraseña) < 6) {
        return "La contraseña debe tener al menos 6 caracteres.";
    }

    // Verificar si el correo ya existe
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    if ($stmt->fetch()) {
        return "El correo ya está registrado.";
    }

    // Hashear la contraseña
    $hashedPassword = password_hash($contraseña, PASSWORD_DEFAULT);

    // Insertar el usuario
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellidos, correo, telefono, contraseña, rol) VALUES (?, ?, ?, ?, ?, 'usuario')");
    if ($stmt->execute([$nombre, $apellidos, $correo, $telefono, $hashedPassword])) {
        return true; // Registro exitoso
    } else {
        return "Error al registrar el usuario.";
    }
}

// Función para iniciar sesión
function iniciarSesion($correo, $contraseña) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT id_usuario, nombre, contraseña, rol FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($contraseña, $user['contraseña'])) {
        // Iniciar sesión (si no existe una sesión activa)
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['correo'] = $correo;
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];
        return true;
    } else {
        return "Correo o contraseña incorrectos.";
    }
}

// Función para verificar si el usuario está logueado
function estaLogueado() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    return isset($_SESSION['user_id']);
}

// Función para cerrar sesión
function cerrarSesion() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    session_destroy();
}

// Función para obtener el rol del usuario
function obtenerRol() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    return $_SESSION['rol'] ?? null;
}
?>