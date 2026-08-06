<?php
require_once '../../app/ClaseHelper.php';
require_once '../../app/auth.php';

if (!estaLogueado() || obtenerRol() !== 'usuario') {
    header("Location: ../login.php");
    exit;
}

$claseHelper = new ClaseHelper();
$id_usuario = $_SESSION['user_id'];
$id_clase = $_POST['id_clase'] ?? $_GET['id_clase'] ?? null;
$id_clase = ($id_clase === null || $id_clase === '')
    ? null
    : intval($id_clase);

if ($id_clase === null) {
    header("Location: dashboard_usuario.php?mensaje=" . urlencode('ID de clase inválido'));
    exit;
}


$resultado = $claseHelper->inscribirUsuario($id_usuario, $id_clase);
if ($resultado !== true) {
    error_log('Inscripción fallida: ' . $resultado . ' - usuario=' . $id_usuario . ' clase=' . $id_clase);
}
if ($resultado === true) {
    header("Location: dashboard_usuario.php?mensaje=Inscripción exitosa");
} else {
    header("Location: dashboard_usuario.php?mensaje=" . urlencode($resultado));
}
exit;
