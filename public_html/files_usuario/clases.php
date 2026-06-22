<?php
    include '../includes/head_sidebar_user.php';
    require_once '../../app/ClaseHelper.php';
    require_once '../../app/auth.php';

    if (!estaLogueado() || obtenerRol() !== 'usuario') {
        header("Location: ../../login.php");
        exit;
    }

    $claseHelper = new ClaseHelper();
    $id_usuario = $_SESSION['user_id'];
    $clases = $claseHelper->obtenerClases();
    $inscritas = $claseHelper->obtenerClasesUsuario($id_usuario);
    $ids_inscritas = array_column($inscritas, 'id_clase');
?>

<div class=" planes content">
    <h2 class="mb-4" style="color:tomato">Clases disponibles</h2>

    <div class="row g-4" data-aos="fade-down" data-aos-delay="200">

    <?php foreach ($clases as $clase):
        // Mostrar solo clases futuras
        if (strtotime($clase['fecha_hora']) < time()) continue;
        $fecha = date('l d/m H:i', strtotime($clase['fecha_hora']));
        $estaInscrito = in_array($clase['id_clase'], $ids_inscritas);
        $disponibles = $clase['capacidad'] - $clase['cupos'];
    ?>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5><?php echo htmlspecialchars($clase['nombre']); ?></h5>
                    <p class="text-muted"><?php echo htmlspecialchars($fecha); //poner tambien salas ?> </p>
                    <?php if ($estaInscrito): ?>
                        <span class="badge bg-success">Inscrito</span>
                    <?php elseif ($disponibles > 0): ?>
                        <form action="inscribir_clase.php" method="post" class="m-0">
                            <input type="hidden" name="id_clase" value="<?php echo htmlspecialchars($clase['id_clase']); ?>">
                            <button type="submit" class="btn btn-success w-100">Apuntarme</button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-danger w-100" disabled>Completo</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

   
    </div>
</div>

   <?php
   
     include '../includes/scripts.php'

    ?>

</body>
</html>