<?php
// editar_clase.php: formulario para modificar una clase existente
include '../includes/head_sidebar_admin.php';
require_once '../../app/ClaseHelper.php';

$claseHelper = new ClaseHelper();
$mensaje = '';

$id_clase = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_clase <= 0) {
    header("Location: admin_clase.php?mensaje=" . urlencode('ID de clase inválido'));
    exit;
}

$clase = $claseHelper->obtenerClasePorId($id_clase);
if (!$clase) {
    header("Location: admin_clase.php?mensaje=" . urlencode('Clase no encontrada'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_gimnasio = 1; // Asumir gimnasio 1
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $fecha_hora = str_replace('T', ' ', $_POST['fecha_hora']);
    $capacidad = (int)$_POST['capacidad'];

    if ($claseHelper->actualizarClase($id_clase, $id_gimnasio, $nombre, $descripcion, $fecha_hora, $capacidad)) {
        $mensaje = "Clase actualizada exitosamente.";
        $clase = $claseHelper->obtenerClasePorId($id_clase); // Recargar datos actualizados
    } else {
        $mensaje = "Error al actualizar clase.";
    }
}
?>

<div class="content">
    <h2 class="mb-4" style="color:tomato">Editar Clase</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm" style="max-width: 600px;">
        
        <label class="form-label">Nombre de la clase</label>
        <input type="text" name="nombre" class="form-control mb-3" value="<?php echo htmlspecialchars($clase['nombre']); ?>" required>

        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control mb-3" rows="3"><?php echo htmlspecialchars($clase['descripcion']); ?></textarea>

        <label class="form-label">Fecha y Hora</label>
        <input type="datetime-local" name="fecha_hora" class="form-control mb-3" value="<?php echo date('Y-m-d\TH:i', strtotime($clase['fecha_hora'])); ?>" required>

        <label class="form-label">Capacidad</label>
        <input type="number" name="capacidad" class="form-control mb-3" value="<?php echo $clase['capacidad']; ?>" min="1" required>

        <button class="btn btn-primary w-100">Actualizar clase</button>
        <a href="admin_clase.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
    </form>
</div>

<?php
include '../includes/scripts.php';
?>
</body>
</html>