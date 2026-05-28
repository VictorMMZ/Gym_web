<?php
require_once '../../app/auth.php';

if (!estaLogueado() || !in_array(obtenerRol(), ['admin', 'entrenador'])) {
    header("Location: ../login.php");
    exit;
}

include '../includes/head_sidebar_admin.php'
?>

<?php
    // Obtener métricas desde la base de datos
    global $pdo;
    try {
        $usuarios_stmt = $pdo->query("SELECT COUNT(*) FROM usuarios where rol = 'usuario'");
        $usuarios_count = $usuarios_stmt->fetchColumn();

        $clases_stmt = $pdo->prepare("SELECT COUNT(*) FROM clases WHERE fecha_hora >= NOW()");
        $clases_stmt->execute();
        $clases_activas = $clases_stmt->fetchColumn();

        $asistencias_stmt = $pdo->prepare("SELECT COUNT(*) FROM inscripciones i JOIN clases c ON i.id_clase = c.id_clase WHERE i.estado = 'finalizado' AND MONTH(c.fecha_hora) = MONTH(CURRENT_DATE()) AND YEAR(c.fecha_hora) = YEAR(CURRENT_DATE())");
        $asistencias_stmt->execute();
        $asistencias_mes = $asistencias_stmt->fetchColumn();
    } catch (Exception $e) {
        $usuarios_count = 0;
        $clases_activas = 0;
        $asistencias_mes = 0;
    }
?>

<div class="content">
    <h2 class="mb-4" style="color:tomato">Panel de Administración</h2>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5>Usuarios registrados</h5>
                    <p class="display-6"><?php echo htmlspecialchars($usuarios_count); ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5>Clases activas</h5>
                    <p class="display-6"><?php echo htmlspecialchars($clases_activas); ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5>Asistencias este mes</h5>
                    <p class="display-6"><?php echo htmlspecialchars($asistencias_mes); ?></p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
   
     include '../includes/scripts.php';

    ?>
</body>
</html>