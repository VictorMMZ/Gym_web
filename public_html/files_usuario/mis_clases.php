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
    <h2 class="mb-4" style="color:tomato">Mis clases</h2>

    <div class="row g-4" data-aos="fade-up" data-aos-delay="200">
            <?php foreach ($clases as $clase):
                // Mostrar solo clases inscritas
                if (!in_array($clase['id_clase'], $ids_inscritas)) continue;
                $fecha = date('l d/m H:i', strtotime($clase['fecha_hora']));
            ?>
        <!-- CARD -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5><?php echo htmlspecialchars($clase['nombre']); ?></h5>
                    <p class="text-muted"><?php echo htmlspecialchars($fecha); ?></p>
                    <form action="cancelar_inscripcion.php" method="post">
                        <input type="hidden" name="clase_id" value="<?php echo htmlspecialchars($clase['id_clase']); ?>">
                        <button class="btn btn-danger w-100">Desapuntarme</button>
                    </form>
                </div>
            </div>
        </div>

        <?php endforeach; ?>


        <?php if(count($inscritas) == 0): ?>
            <h3 style="color: tomato;">No estás inscrito en ninguna clase.</h3>
        <?php endif; ?>
    </div>
</div>
   <?php
   
    include '../includes/scripts.php';

    ?>
</body>
</html>