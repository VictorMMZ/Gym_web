<?php
// login.php: formulario de acceso e inicio de sesión para usuarios y admins
include "./includes/header_lite.php";
require_once '../app/auth.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $contraseña = $_POST['contraseña'] ?? '';

    // Intentar iniciar sesión con las credenciales recibidas
    $resultado = iniciarSesion($correo, $contraseña);
    if ($resultado === true) {
        // Redirigir en función del rol guardado en sesión
        $rol = obtenerRol();
        if ($rol === 'usuario') {
            header("Location: ./files_usuario/dashboard_usuario.php");
        } else {
            header("Location: ./files_admin/dashboard_admin.php");
        }
        exit;
    } else {
        $mensaje = $resultado;
    }
}
?>
<!-- LOGIN -->
<section class="backsignup contact section flex-fill" id="signin">
    <div class="container">
        <div class="row">

            <div class="mx-auto col-lg-5 col-md-6 col-12">

                <?php if ($mensaje): ?>
                    <div class="alert alert-danger"><?php echo $mensaje; ?></div>
                <?php endif; ?>

                <h2 class="mb-4 pb-2 text-center" style="color:tomato" data-aos="fade-up" data-aos-delay="200">
                    Iniciar sesión
                </h2>

                <form action="login.php" method="post" class="contact-form webform"
                      data-aos="fade-up" data-aos-delay="400" role="form">

                    <!-- Email -->
                    <input type="email" class="form-control" name="correo" placeholder="Correo electrónico" required>

                    <!-- Password -->
                    <input type="password" class="form-control" name="contraseña" placeholder="Contraseña" required>

                    <!-- Botón -->
                    <button type="submit" class="btn btn-dark w-100 mt-3" id="submit-button" name="login">
                        Entrar
                    </button>

                    <!-- Volver -->
                    <a href="index.php" class="btn btn-secondary w-100 mt-2" id="back-button">
                        Volver
                    </a>

                </form>
            </div>

        </div>
    </div>
</section>

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
