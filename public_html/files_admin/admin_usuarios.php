<?php
include '../includes/head_sidebar_admin.php';
// admin_usuarios.php: vista del administrador para listar todas las clases y ofertas disponibles
require_once '../../app/UsuariosHelper.php';

$usuariosHelper = new UsuariosHelper();
$usuarios = $usuariosHelper->obtenerUsuarios();
$mensaje = $_GET['mensaje'] ?? '';
?>
?>


<div class="content">
    <h2 class="mb-4" style="color:tomato">Gestionar Usuarios</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Membresía</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <?php if ($usuario['rol'] === 'admin') continue; // Omitir usuarios con rol de administrador ?>
            <tr>
                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                <td><?php echo htmlspecialchars($usuario['id_plan']); ?></td>
                <td>
                    <a href="editar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-warning btn-sm">✏ Editar</a>
                    <a href="eliminar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar usuario?')">🗑 Eliminar</a>
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