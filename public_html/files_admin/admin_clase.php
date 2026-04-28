<?php
include '../includes/sidebar_admin.php'
?>


<div class="content">
    <h2 class="mb-4" style="color:tomato">Gestionar Clases</h2>

    <a href="crear_clase.php" class="btn btn-success mb-3">➕ Crear nueva clase</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Clase</th>
                <th>Día</th>
                <th>Hora</th>
                <th>Sala</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Yoga</td>
                <td>Lunes</td>
                <td>10:00</td>
                <td>Sala 1</td>
                <td>
                    <a href="editar_clase.php?id=1" class="btn btn-warning btn-sm">✏ Editar</a>
                    <a href="eliminar_clase.php?id=1" class="btn btn-danger btn-sm">🗑 Eliminar</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>