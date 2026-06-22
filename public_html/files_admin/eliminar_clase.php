<?php
// eliminar_clase.php: acción de eliminación de clase por parte del administrador
require_once '../../app/ClaseHelper.php';

$claseHelper = new ClaseHelper();

$id_clase = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_clase <= 0) {
    header("Location: admin_clase.php?mensaje=" . urlencode('ID de clase inválido'));
    exit;
}

if ($claseHelper->eliminarClase($id_clase)) {
    header("Location: admin_clase.php?mensaje=Clase eliminada");
} else {
    header("Location: admin_clase.php?mensaje=Error al eliminar");
}
exit;
?>