<?php
include "./includes/header_lite.php";
require_once '../app/auth.php';

$mensaje = '';
$planes_validos = [1 => 'Básico', 2 => 'Pro', 3 => 'Premium'];
$plan_id = isset($_GET['plan_id']) ? intval($_GET['plan_id']) : null;
if (!isset($planes_validos[$plan_id])) {
    $plan_id = null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plan_id = isset($_POST['plan_id']) ? intval($_POST['plan_id']) : $plan_id;
    if (!isset($planes_validos[$plan_id])) {
        $mensaje = 'Debes seleccionar un plan válido.';
    } else {
        $nombre = trim($_POST['user-name'] ?? '');
        $apellidos = trim($_POST['user-lastname'] ?? '');
        $telefono = trim($_POST['user-phone'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $contraseña = $_POST['contraseña'] ?? '';
        $repetirContraseña = $_POST['repetir_contraseña'] ?? '';

        $resultado = registrarUsuario($correo, $contraseña, $repetirContraseña, $nombre, $apellidos, $telefono, $plan_id);
        if ($resultado === true) {
            $mensaje = "Registro exitoso. <a href='login.php'>Inicia sesión</a>";
        } else {
            $mensaje = $resultado;
        }
    }
}
?>
    <!-- SIGN UP -->
    <section class="backsignup contact section flex-fill " id="signin">
          <div class="container">
               <div class="row">

                    <div class="mx-auto col-lg-5 col-md-6 col-12">
                        <h2 class="mb-4 pb-2" style="color:tomato" data-aos="fade-up" data-aos-delay="200">Registro de Usuario</h2>

                        <?php if ($mensaje): ?>
                            <div class="alert alert-info"><?php echo $mensaje; ?></div>
                        <?php endif; ?>

                        <form method="post" class="contact-form webform" data-aos="fade-up" data-aos-delay="400" role="form">
                            <input type="text" class="form-control mb-3" name="user-name" placeholder="Nombre" required>
                            <input type="text" class="form-control mb-3" name="user-lastname" placeholder="Apellidos" required>                                                
                            <input type="text" class="form-control mb-3" name="user-phone" placeholder="Teléfono">
                            <input type="email" class="form-control mb-3" name="correo" placeholder="Correo electrónico" required>
                            <input type="password" class="form-control mb-3" name="contraseña" placeholder="Contraseña" required>
                            <input type="password" class="form-control mb-3" name="repetir_contraseña" placeholder="Confirmar Contraseña" required>
                        <?php if ($plan_id !== null): ?>
                            <input type="hidden" name="plan_id" value="<?= htmlspecialchars($plan_id) ?>">
                            <div class="alert alert-secondary">Plan seleccionado: <strong><?= htmlspecialchars($planes_validos[$plan_id]) ?></strong></div>
                        <?php else: ?>
                            <div class="alert alert-warning">No se ha seleccionado un plan. Vuelve a elegir uno desde la página de planes.</div>
                        <?php endif; ?>
                            <button type="submit" class="btn btn-dark w-100" id="submit-button">Registrarse</button>
                            <a href="signup_plan.php" class="btn btn-secondary w-100 mt-2" id="back-button">Volver</a>
                    </div>
                    </div>
                    </div>

        

    </section>
     <!-- FOOTER -->
    <?php
     include './includes/footer.php'

    ?>
     <!-- SCRIPTS -->
     <script src="./assets/js/jquery.min.js"></script>
     <script src="./assets/js/bootstrap.min.js"></script>
     <script src="./assets/js/aos.js"></script>
     <script src="./assets/js/smoothscroll.js"></script>
     <script src="./assets/js/custom.js"></script>

</body>
</html>

