<?php
require_once '../../config/db.php';
include '../includes/head_sidebar_admin.php';


 $stmt = $pdo->prepare("SELECT m.id_mensaje, m.nombre_usuario, m.correo_usuario, m.mensaje FROM mensajes_usuarios m ");
        $stmt->execute();
         $mensaje = $stmt->fetchAll(PDO::FETCH_ASSOC);
         ?>
       






<div class="content">

    <!-- =========================================================
         CABECERA
    ========================================================== -->

    <div class="mb-4">
    <h2 class="mb-4 text-center">Mensajes de Usuarios</h2>

    <table class="table table-bordered table-striped shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Mensaje</th>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mensaje as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m['nombre_usuario']) ?></td>
                    <td><?= htmlspecialchars($m['correo_usuario']) ?></td>
                    <td><?= nl2br(htmlspecialchars($m['mensaje'])) ?></td>
                   
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button onclick="window.history.back()" class="btn btn-secondary mt-3"> Volver</button>
</div>

<?php

include '../includes/scripts.php';

?>
</body>
</html>
