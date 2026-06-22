<?php
// registro.php: pantalla para crear un usuario con un plan seleccionado
include "./includes/header_lite.php";
require_once '../app/auth.php';

$mensaje = '';
$errors = [];
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

        // Intentar registrar el usuario en la base de datos
        $resultado = registrarUsuario($correo, $contraseña, $repetirContraseña, $nombre, $apellidos, $telefono, $plan_id);
        if ($resultado['ok'] === true) {
            header("Location: login.php");
            exit;
        } else {
            $errors = $resultado['errors'] ?? [];
        }
    }
}
?>
    <!-- SIGN UP -->
    <section class="backsignup contact section flex-fill" id="signin">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-5 col-md-6 col-12">
                    <h2 class="mb-4 pb-2" style="color:tomato" data-aos="fade-up" data-aos-delay="200">Registro de Usuario</h2>

                    <?php if ($mensaje): ?>
                        <div class="alert alert-info"><?php echo $mensaje; ?></div>
                    <?php endif; ?>

                    <?php
                        $tieneErroresDeCampo = !empty(array_diff_key($errors, ['general' => '']));
                    ?>
                    <?php if ($tieneErroresDeCampo): ?>
                        <div class="alert alert-danger">Revisa los campos marcados con error.</div>
                    <?php endif; ?>
                    <?php if (isset($errors['general'])): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
                    <?php endif; ?>

                    <form method="post" class="contact-form webform" data-aos="fade-up" data-aos-delay="400" role="form" novalidate>
                        <div class="mb-3">
                            <input type="text" class="form-control <?= isset($errors['user-name']) ? 'is-invalid' : '' ?>" name="user-name" placeholder="Nombre" value="<?= htmlspecialchars($_POST['user-name'] ?? '') ?>" required>
                            <?php if (isset($errors['user-name'])): ?>
                                <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['user-name']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control <?= isset($errors['user-lastname']) ? 'is-invalid' : '' ?>" name="user-lastname" placeholder="Apellidos" value="<?= htmlspecialchars($_POST['user-lastname'] ?? '') ?>" required>
                            <?php if (isset($errors['user-lastname'])): ?>
                                <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['user-lastname']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control <?= isset($errors['user-phone']) ? 'is-invalid' : '' ?>" name="user-phone" placeholder="Teléfono" value="<?= htmlspecialchars($_POST['user-phone'] ?? '') ?>" maxlength="9">
                            <?php if (isset($errors['user-phone'])): ?>
                                <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['user-phone']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <input type="email" class="form-control <?= isset($errors['correo']) ? 'is-invalid' : '' ?>" name="correo" placeholder="Correo electrónico" value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>" required>
                            <?php if (isset($errors['correo'])): ?>
                                <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['correo']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <input type="password" class="form-control <?= isset($errors['contraseña']) ? 'is-invalid' : '' ?>" name="contraseña" placeholder="Contraseña" required>
                            <?php if (isset($errors['contraseña'])): ?>
                                <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['contraseña']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <input type="password" class="form-control <?= isset($errors['repetir_contraseña']) ? 'is-invalid' : '' ?>" name="repetir_contraseña" placeholder="Confirmar Contraseña" required>
                            <?php if (isset($errors['repetir_contraseña'])): ?>
                                <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['repetir_contraseña']) ?></div>
                            <?php endif; ?>
                        </div>

                        <?php if ($plan_id !== null): ?>
                            <input type="hidden" name="plan_id" value="<?= htmlspecialchars($plan_id) ?>">
                            <div class="alert alert-secondary">Plan seleccionado: <strong><?= htmlspecialchars($planes_validos[$plan_id]) ?></strong></div>
                        <?php else: ?>
                            <div class="alert alert-warning">No se ha seleccionado un plan. Vuelve a elegir uno desde la página de planes.</div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-dark w-100" id="submit-button">Registrarse</button>
                        <a href="signup_plan.php" class="btn btn-secondary w-100 mt-2" id="back-button">Volver</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
     <!-- FOOTER -->
    <?php
     include './includes/footer.php';
    ?>
     <!-- SCRIPTS -->
     <script src="./assets/js/jquery.min.js"></script>
     <script src="./assets/js/bootstrap.min.js"></script>
     <script src="./assets/js/aos.js"></script>
     <script src="./assets/js/smoothscroll.js"></script>
     <script src="./assets/js/custom.js"></script>

</body>
</html>

