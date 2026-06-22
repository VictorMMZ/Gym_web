<?php
// auth.php: funciones de autenticación y gestión de sesión para Gym_Web
require_once __DIR__ . '/../config/db.php';

// Validar los datos de registro antes de intentar guardar el usuario
function validarDatosRegistro($correo, $contraseña, $repetirContraseña, $nombre, $apellidos, $telefono) {
    $errores = [];

    // Validación del nombre: solo letras y espacios
    if ($nombre === '' || !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s]+$/u', $nombre)) {
        $errores['user-name'] = 'El nombre solo puede contener letras y espacios.';
    }

    // Validación de apellidos: solo letras y espacios
    if ($apellidos === '' || !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü\s]+$/u', $apellidos)) {
        $errores['user-lastname'] = 'Los apellidos solo pueden contener letras y espacios.';
    }

    // Validación de teléfono: opcional, pero si existe debe comenzar con 6 o 7 y tener 9 dígitos
    if ($telefono !== '' && !preg_match('/^[67][0-9]{8}$/', $telefono)) {
        $errores['user-phone'] = 'El teléfono debe tener 9 dígitos y empezar con 6 o 7.';
    }

    // Validación del correo electrónico
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores['correo'] = 'Ingresa un correo electrónico válido.';
    }

    // Validación de contraseña: mínimo 8 caracteres, al menos una mayúscula y un carácter especial
    if (
        strlen($contraseña) < 8 ||
        !preg_match('/[A-Z]/', $contraseña) ||
        !preg_match('/[^A-Za-z0-9]/', $contraseña)
    ) {
        $errores['contraseña'] = 'La contraseña debe tener mínimo 8 caracteres, una mayúscula y un carácter especial.';
    }

    // Validar coincidencia de contraseñas
    if ($contraseña !== $repetirContraseña) {
        $errores['repetir_contraseña'] = 'Las contraseñas no coinciden.';
    }

    return $errores;
}

// Registrar un nuevo usuario en la base de datos
function registrarUsuario($correo, $contraseña, $repetirContraseña, $nombre, $apellidos, $telefono, $plan_id) {
    global $pdo;

    $errores = validarDatosRegistro($correo, $contraseña, $repetirContraseña, $nombre, $apellidos, $telefono);
    if (!empty($errores)) {
        return ['ok' => false, 'errors' => $errores];
    }

    try {
        // Verificar si el correo ya existe en la tabla usuarios
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        if ($stmt->fetch()) {
            return ['ok' => false, 'errors' => ['correo' => 'El correo ya está registrado.']];
        }

        // Hashear la contraseña con un algoritmo seguro
        $hashedPassword = password_hash($contraseña, PASSWORD_DEFAULT);

        try {
            $pdo->beginTransaction();

            // Insertar el usuario con rol de 'usuario' y plan seleccionado
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellidos, correo, telefono, contraseña, rol, id_plan) VALUES (?, ?, ?, ?, ?, 'usuario', ?)");
            $stmt->execute([$nombre, $apellidos, $correo, $telefono, $hashedPassword, $plan_id]);
            $idUsuario = (int)$pdo->lastInsertId();

            // Crear un registro de facturación inicial para el nuevo usuario
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
        // Manejar errores de duplicado o fallos generales de la base de datos
        if (strpos($e->getMessage(), 'Duplicate entry') !== false || strpos($e->getMessage(), 'UNIQUE') !== false) {
            return ['ok' => false, 'errors' => ['correo' => 'El correo ya está registrado.']];
        }

        return ['ok' => false, 'errors' => ['general' => 'No se pudo completar el registro. Inténtalo nuevamente.']];
    }
}

// Iniciar sesión de usuario usando correo y contraseña
function iniciarSesion($correo, $contraseña) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT id_usuario, nombre, contraseña, rol, id_plan FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($contraseña, $user['contraseña'])) {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['correo'] = $correo;
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];
        $_SESSION['id_plan'] = $user['id_plan'] ?? null;
        return true;
    } else {
        return "Correo o contraseña incorrectos.";
    }
}

// Verificar si hay un usuario autenticado en la sesión
function estaLogueado() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    return isset($_SESSION['user_id']);
}

// Cerrar la sesión actual y eliminar los datos almacenados en $_SESSION
function cerrarSesion() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    session_destroy();
}

// Devolver el rol del usuario actual almacenado en la sesión
function obtenerRol() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    return $_SESSION['rol'] ?? null;
}

// Guardar mensajes de log en un archivo dentro de la carpeta logs
function log_message($mensaje, $archivo = 'app.log') {
    $fecha = date('Y-m-d H:i:s');
    $linea = "[$fecha] $mensaje" . PHP_EOL;
    file_put_contents(__DIR__ . "/logs/$archivo", $linea, FILE_APPEND | LOCK_EX);
}

?>