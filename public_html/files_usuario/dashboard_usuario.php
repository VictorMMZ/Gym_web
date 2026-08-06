<?php
include '../includes/head_sidebar_user.php';
require_once '../../app/ClaseHelper.php';
require_once '../../app/auth.php';

if (!estaLogueado() || obtenerRol() !== 'usuario') {
    header("Location: ../../login.php");
    exit;
}

$claseHelper = new ClaseHelper();
$id_usuario = $_SESSION['user_id'];
$clases_inscritas = $claseHelper->obtenerClasesUsuario($id_usuario);
$clases_disponibles = $claseHelper->obtenerClases(); // Todas las clases, filtrar las no inscritas
// Filtrar clases: solo hoy o días posteriores
$hoy = strtotime(date('Y-m-d')); // Hoy a las 00:00

$clases_disponibles = array_filter($clases_disponibles, function ($c) use ($hoy) {
    $fechaClase = strtotime(date('Y-m-d', strtotime($c['fecha_hora'])));
    return $fechaClase >= $hoy;
});


// Filtrar clases disponibles (no inscritas)
$ids_inscritas = array_column($clases_inscritas, 'id_clase');
$clases_disponibles = array_filter($clases_disponibles, function ($clase) use ($ids_inscritas) {
    return !in_array($clase['id_clase'], $ids_inscritas);
});

$mensaje = $_GET['mensaje'] ?? '';


// Obtener métricas del usuario desde la BD
global $pdo;
try {
    $user_stmt = $pdo->prepare("SELECT nombre, rol FROM usuarios WHERE id_usuario = ?");
    $user_stmt->execute([$id_usuario]);
    $user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);
    $membresia_tipo = $user_data['rol'] ?? 'Standard';

    $clases_mes_stmt = $pdo->prepare("SELECT COUNT(*) FROM inscripciones i JOIN clases c ON i.id_clase = c.id_clase WHERE i.id_usuario = ? AND i.estado = 'finalizado' AND MONTH(c.fecha_hora) = MONTH(CURRENT_DATE()) AND YEAR(c.fecha_hora) = YEAR(CURRENT_DATE())");
    $clases_mes_stmt->execute([$id_usuario]);
    $clases_mes = $clases_mes_stmt->fetchColumn();

    $clases_total_stmt = $pdo->prepare("SELECT COUNT(*) FROM inscripciones WHERE id_usuario = ? AND estado = 'finalizado'");
    $clases_total_stmt->execute([$id_usuario]);
    $clases_total = $clases_total_stmt->fetchColumn();
} catch (Exception $e) {
    $membresia_tipo = 'Standard';
    $clases_mes = 0;
    $clases_total = 0;
}
?>
<!-- CONTENIDO PRINCIPAL -->
<div class="planes content">
    <h2 class="mb-4" style="color:tomato">Dashboard</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <div class="row g-4 mb-4" data-aos="fade-up" data-aos-delay="200">

        <!-- Membresía -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Membresía actual</h5>
                    <p class="text-muted">Tipo: <strong><?php echo htmlspecialchars($membresia_tipo); ?></strong></p>
                    <p class="text-muted">Renovación: <strong>Gestionar en perfil</strong></p>
                </div>
            </div>
        </div>

        <!-- Clases realizadas -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Clases realizadas</h5>
                    <p><strong>Este mes:</strong> <?php echo htmlspecialchars($clases_mes); ?></p>
                    <p><strong>Total:</strong> <?php echo htmlspecialchars($clases_total); ?></p>
                </div>
            </div>
        </div>

    </div>

    <!-- CLASES INSCRITAS -->
    <div class="card shadow-sm mb-4" data-aos="fade-left" data-aos-delay="200">
        <div class="card-header bg-success text-white">
            Mis Clases Inscritas
        </div>
        <div class="card-body">
            <?php if (empty($clases_inscritas)): ?>
                <p>No tienes clases inscritas.</p>
            <?php else: ?>
                <?php foreach ($clases_inscritas as $clase): ?>
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <div>
                            <strong><?php echo htmlspecialchars($clase['nombre']); ?></strong><br>
                            <small><?php echo htmlspecialchars($clase['fecha_hora']); ?> - <?php echo htmlspecialchars($clase['descripcion']); ?></small>
                        </div>
                        <a href="cancelar_inscripcion.php?id_clase=<?php echo $clase['id_clase']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Cancelar inscripción?')">Cancelar</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- CLASES DISPONIBLES -->
    <div class="card shadow-sm mb-4" data-aos="fade-right" data-aos-delay="300">
        <div class="card-header bg-info text-white">
            Clases Disponibles
        </div>
        <div class="card-body">
            <?php if (empty($clases_disponibles)): ?>
                <p>No hay clases disponibles.</p>
            <?php else: ?>
                <?php foreach ($clases_disponibles as $clase): ?>

                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <div>
                            <strong><?php echo htmlspecialchars($clase['nombre']); ?></strong><br>
                            <small><?php echo htmlspecialchars($clase['fecha_hora']); ?> - Capacidad: <?php echo $clase['cupos']; ?>/<?php echo $clase['capacidad']; ?></small><br>
                            <small><?php echo htmlspecialchars($clase['descripcion']); ?></small>
                        </div>
                        <form action="inscribir_clase.php" method="post" class="m-0">
                            <input type="hidden" name="id_clase" value="<?php echo htmlspecialchars($clase['id_clase']); ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Inscribirme</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php

    // Mostrar próximas 3 clases disponibles (no inscritas)
    $ids_inscritas = array_column($clases_inscritas, 'id_clase');
    $proximas = array_filter($clases_disponibles, function ($c) use ($ids_inscritas) {
        return strtotime($c['fecha_hora']) > time() && !in_array($c['id_clase'], $ids_inscritas);
    });
    $proximas = array_slice(array_values($proximas), 0, 3);

    foreach ($proximas as $p):
        $fecha = date('d/m/Y H:i', strtotime($p['fecha_hora']));
        $dias = ceil((strtotime($p['fecha_hora']) - time()) / 86400);
    ?>

    <?php endforeach; ?>

</div>
</div>


<!-- HISTORIAL RECIENTE -->
<div class="card shadow-sm mb-4" data-aos="fade-down" data-aos-delay="200">
    <div class="card-header bg-secondary text-white">
        Historial reciente
    </div>
    <div class="card-body">

        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Clase</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Obtener las últimas 3 clases pasadas del usuario
                try {
                    $hist_stmt = $pdo->prepare("SELECT c.nombre, c.fecha_hora, i.estado FROM inscripciones i JOIN clases c ON i.id_clase = c.id_clase WHERE i.id_usuario = ? AND i.estado IN ('finalizado','cancelada') AND c.fecha_hora < NOW() ORDER BY c.fecha_hora DESC LIMIT 3");
                    $hist_stmt->execute([$id_usuario]);
                    $hist = $hist_stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    $hist = [];
                }

                if (empty($hist)) {
                    echo '<tr><td colspan="3">No hay historial reciente.</td></tr>';
                } else {
                    foreach ($hist as $h) {
                        if ($h['estado'] === 'finalizado') {
                            $estado = '<span class="badge bg-success">Finalizado</span>';
                        } elseif ($h['estado'] === 'cancelada') {
                            $estado = '<span class="badge bg-danger">Cancelada</span>';
                        } else {
                            $estado = '<span class="badge bg-secondary">' . htmlspecialchars($h['estado']) . '</span>';
                        }
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($h['nombre']) . '</td>';
                        echo '<td>' . htmlspecialchars(date('d/m/Y', strtotime($h['fecha_hora']))) . '</td>';
                        echo '<td>' . $estado . '</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>

    </div>
</div>
</div>
<?php

include '../includes/scripts.php'

?>
</body>

</html>