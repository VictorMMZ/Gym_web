<?php
// editar_usuario.php: formulario para modificar un usuario existente
include '../includes/head_sidebar_admin.php';
require_once '../../app/UsuariosHelper.php';

$UsuariosHelper = new UsuariosHelper();
$mensaje = '';

$id_usuario = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_usuario <= 0) {
    header("Location: admin_usuario.php?mensaje=" . urlencode('ID de usuario inválido'));
    exit;
}

$usuario = $UsuariosHelper->obtenerUsuarioPorId($id_usuario);
if (!$usuario) {
    header("Location: admin_usuario.php?mensaje=" . urlencode('Usuario no encontrado'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $id_plan = isset($_POST['id_plan']) ? intval($_POST['id_plan']) : null;
    


$sql = "UPDATE facturacion SET id_plan = :id_plan WHERE id_usuario = :id_usuario";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':id_plan' => $id_plan,
    ':id_usuario' => $id_usuario
]);


    if ($UsuariosHelper->actualizarUsuario($id_usuario, $nombre, $apellidos, $correo, $telefono, $id_plan)) {
        $mensaje = "Usuario actualizado exitosamente.";
        $usuario = $UsuariosHelper->obtenerUsuarioPorId($id_usuario); // Recargar datos actualizados
    } else {
        $mensaje = "Error al actualizar usuario.";
    }
}
?>

<div class="content">
    <h2 class="mb-4" style="color:tomato">Editar Usuario</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm" style="max-width: 600px;">
        
        <label class="form-label">Nombre del Usuario</label>
        <input type="text" name="nombre" class="form-control mb-3" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>

        <label class="form-label">Apellidos</label>
        <input type="text" name="apellidos" class="form-control mb-3" value="<?php echo htmlspecialchars($usuario['apellidos']); ?>" required>

        <label class="form-label">Correo Electrónico</label>
        <input type="email" name="correo" class="form-control mb-3" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>

        <label class="form-label">Teléfono</label>
        <input type="tel" name="telefono" class="form-control mb-3" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required>

        <label class="form-label">ID del Plan</label>
        <select name="id_plan" class="form-control mb-3" required>
            <option value="1" <?php if($usuario['id_plan']==1) echo 'selected'; ?>>Básico</option>
            <option value="2" <?php if($usuario['id_plan']==2) echo 'selected'; ?>>Pro</option>
            <option value="3" <?php if($usuario['id_plan']==3) echo 'selected'; ?>>Premium</option>
        </select>


        <button class="btn btn-primary w-100">Actualizar usuario</button>
        <a href="admin_usuario.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
    </form>
</div>

<?php
include '../includes/scripts.php';
?>
</body>
</html>