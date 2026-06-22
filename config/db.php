<?php
// db.php: configuración de la conexión PDO para Gym_Web
$host = 'localhost';
$dbname = 'gimnasio_ges';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Tablas necesarias para que la aplicación funcione correctamente
    $requiredTables = [
        'gimnasio',
        'planes',
        'usuarios',
        'clases',
        'inscripciones',
        'facturacion',
        'mensajes_usuarios'
    ];

    $stmt = $pdo->query('SHOW TABLES');
    $existingTables = [];
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $existingTables[] = strtolower($row[0]);
    }

    // Comprobar si falta alguna tabla requerida y ejecutar setup si es necesario
    $shouldRunSetup = false;
    foreach ($requiredTables as $table) {
        if (!in_array($table, $existingTables, true)) {
            $shouldRunSetup = true;
            break;
        }
    }

    if ($shouldRunSetup) {
        require_once __DIR__ . '/setup.php';
    }

    // Insertar usuario administrador por defecto si todavía no existe
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE correo = 'admin@gymweb.com'");
    $stmt->execute();
    if (!$stmt->fetch()) {
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellidos, correo, telefono, contraseña, rol) VALUES ('Administrador', '', 'admin@gymweb.com', '123456789', ?, 'admin')");
        $stmt->execute([$hashedPassword]);
        echo "Usuario admin creado. Correo: admin@gymweb.com, Contraseña: admin123<br>";
    }
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>