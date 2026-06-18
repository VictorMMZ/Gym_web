<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'gimnasio_ges';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Ejecutar setup solo si alguna tabla necesaria falta.
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
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>