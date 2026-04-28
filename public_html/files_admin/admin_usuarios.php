<?php
include '../includes/sidebar_admin.php'
?>


<div class="content">
    <h2 class="mb-4" style="color:tomato">Gestionar Usuarios</h2>

    <a href="crear_usuario.php" class="btn btn-success mb-3">➕ Crear usuario</a>

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
            <tr>
                <td>Carlos Pérez</td>
                <td>carlos@example.com</td>
                <td>Premium</td>
                <td>
                    <a href="editar_usuario.php?id=1" class="btn btn-warning btn-sm">✏ Editar</a>
                    <a href="eliminar_usuario.php?id=1" class="btn btn-danger btn-sm">🗑 Eliminar</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>