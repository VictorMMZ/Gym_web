<?php
include '../includes/head_sidebar_admin.php';
require_once '../../app/ClaseHelper.php';

$claseHelper = new ClaseHelper();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_gimnasio = 1; // Asumir gimnasio 1
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $fecha_hora = str_replace('T', ' ', $_POST['fecha_hora']);
    $capacidad = (int)$_POST['capacidad'];

    if ($claseHelper->crearClase($id_gimnasio, $nombre, $descripcion, $fecha_hora, $capacidad)) {
        $mensaje = "Clase creada exitosamente.";
    } else {
        $mensaje = "Error al crear clase.";
    }
}
?>


<div class="content">
    <h2 class="mb-4" style="color:tomato">Crear Nueva Clase</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm" style="max-width: 600px;">
        
        <label class="form-label">Nombre de la clase</label>
        <input type="text" name="nombre" class="form-control mb-3" required>

        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control mb-3" rows="3"></textarea>

        <label class="form-label">Fecha y Hora</label>
        <input type="datetime-local" name="fecha_hora" class="form-control mb-3" required>

        <label class="form-label">Capacidad</label>
        <input type="number" name="capacidad" class="form-control mb-3" value="20" min="1" required>

        <button class="btn btn-primary w-100">Guardar clase</button>
    </form>
</div>
<?php
   
     include '../includes/scripts.php';

    ?>
</body>
</html>