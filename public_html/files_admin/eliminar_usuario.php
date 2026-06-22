<?php
// eliminar_usuario.php: acción de eliminación de usuario por parte del administrador
require_once '../../app/UsuariosHelper.php';

$UsuariosHelper = new UsuariosHelper();

$id_usuario = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_usuario <= 0) {
    header("Location: admin_usuarios.php?mensaje=" . urlencode('ID de usuario inválido'));
    exit;
}

if ($UsuariosHelper->eliminarUsuario($id_usuario)) {
    header("Location: admin_usuarios.php?mensaje=Usuario eliminado");
} else {
    header("Location: admin_usuarios.php?mensaje=Error al eliminar");
}
exit;
?>