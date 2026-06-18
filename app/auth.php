<?php
require_once __DIR__ . '/../config/db.php';

function validarDatosRegistro($correo, $contraseña, $repetirContraseña, $nombre, $apellidos, $telefono) {
    $errores = [];

    if ($nombre === '' || !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s]+$/u', $nombre)) {
        $errores['user-name'] = 'El nombre solo puede contener letras y espacios.';
    }

    if ($apellidos === '' || !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s]+$/u', $apellidos)) {
        $errores['user-lastname'] = 'Los apellidos solo pueden contener letras y espacios.';
    }

    if ($telefono !== '' && !preg_match('/^[67][0-9]{8}$/', $telefono)) {
        $errores['user-phone'] = 'El teléfono debe tener 9 dígitos y empezar con 6 o 7.';
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores['correo'] = 'Ingresa un correo electrónico válido.';
    }

    if (
        strlen($contraseña) < 8 ||
        !preg_match('/[A-Z]/', $contraseña) ||
        !preg_match('/[^A-Za-z0-9]/', $contraseña)
    ) {
        $errores['contraseña'] = 'La contraseña debe tener mínimo 8 caracteres, una mayúscula y un carácter especial.';
    }

    if ($contraseña !== $repetirContraseña) {
        $errores['repetir_contraseña'] = 'Las contraseñas no coinciden.';
    }

    return $errores;
}

// Función para registrar un usuario
function registrarUsuario($correo, $contraseña, $repetirContraseña, $nombre, $apellidos, $telefono, $plan_id) {
    global $pdo;

    $errores = validarDatosRegistro($correo, $contraseña, $repetirContraseña, $nombre, $apellidos, $telefono);
    if (!empty($errores)) {
        return ['ok' => false, 'errors' => $errores];
    }

    try {
        // Verificar si el correo ya existe
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        if ($stmt->fetch()) {
            return ['ok' => false, 'errors' => ['correo' => 'El correo ya está registrado.']];
        }

        // Hashear la contraseña
        $hashedPassword = password_hash($contraseña, PASSWORD_DEFAULT);

        try {
            $pdo->beginTransaction();

            // Insertar el usuario con el plan elegido
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellidos, correo, telefono, contraseña, rol, id_plan) VALUES (?, ?, ?, ?, ?, 'usuario', ?)");
            $stmt->execute([$nombre, $apellidos, $correo, $telefono, $hashedPassword, $plan_id]);
            $idUsuario = (int)$pdo->lastInsertId();

            // Crear automáticamente el registro de facturación para el nuevo usuario
            $stmtFacturacion = $pdo->prepare("INSERT INTO facturacion (id_usuario, id_plan, facturado) VALUES (?, ?, 1)");
            $stmtFacturacion->execute([$idUsuario, $plan_id]);

            $pdo->commit();
            return ['ok' => true];
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false || strpos($e->getMessage(), 'UNIQUE') !== false) {
            return ['ok' => false, 'errors' => ['correo' => 'El correo ya está registrado.']];
        }

        return ['ok' => false, 'errors' => ['general' => 'No se pudo completar el registro. Inténtalo nuevamente.']];
    }
}

// Función para iniciar sesión
function iniciarSesion($correo, $contraseña) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT id_usuario, nombre, contraseña, rol, id_plan FROM usuarios WHERE correo = ?");
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
        $_SESSION['id_plan'] = $user['id_plan'] ?? null; // Guardar el plan_id en la sesión
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