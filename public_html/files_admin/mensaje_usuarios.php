<?php
require_once '../../config/db.php';



 $stmt = $pdo->prepare("SELECT m.id_mensaje, m.nombre_usuario, m.correo_usuario, m.mensaje FROM mensajes_usuarios m ");
        $stmt->execute();
         $mensaje = $stmt->fetchAll(PDO::FETCH_ASSOC);
         ?>
       




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes de Usuarios</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4 text-center">Mensajes de Usuarios</h2>

    <table class="table table-bordered table-striped shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Mensaje</th>
                <th>Fecha de Envío</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mensaje as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m['nombre_usuario']) ?></td>
                    <td><?= htmlspecialchars($m['correo_usuario']) ?></td>
                    <td><?= nl2br(htmlspecialchars($m['mensaje'])) ?></td>
                    <td><?= htmlspecialchars($m['fecha_envio']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
