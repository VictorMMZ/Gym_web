<?php
// admin_clase.php: vista del administrador para listar todas las clases y ofertas disponibles
include '../includes/head_sidebar_admin.php';
require_once '../../app/ClaseHelper.php';

$claseHelper = new ClaseHelper();
$clases = $claseHelper->obtenerClases();
$mensaje = $_GET['mensaje'] ?? '';
?>


<div class="content">
    <h2 class="mb-4" style="color:tomato">Gestionar Clases</h2>
    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <a href="crear_clase.php" class="btn btn-success mb-3">➕ Crear nueva clase</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Clase</th>
                <th>Descripción</th>
                <th>Fecha y Hora</th>
                <th>Capacidad</th>
                <th>Cupos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clases as $clase): ?>
            <tr>
                <td><?php echo htmlspecialchars($clase['nombre']); ?></td>
                <td><?php echo htmlspecialchars($clase['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($clase['fecha_hora']); ?></td>
                <td><?php echo htmlspecialchars($clase['capacidad']); ?></td>
                <td><?php echo htmlspecialchars($clase['cupos']); ?></td>
                <td>
                    <a href="editar_clase.php?id=<?php echo $clase['id_clase']; ?>" class="btn btn-warning btn-sm">✏ Editar</a>
                    <a href="eliminar_clase.php?id=<?php echo $clase['id_clase']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar clase?')">🗑 Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
include '../includes/scripts.php';
?>
    
</body>
</html>