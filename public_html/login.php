<?php
include "./includes/header.php"
?>
<!-- LOGIN -->
<section class="backsignup contact section flex-fill" id="signin">
    <div class="container">
        <div class="row">

            <div class="mx-auto col-lg-5 col-md-6 col-12">

                

                <h2 class="mb-4 pb-2 text-center" style="color:tomato" data-aos="fade-up" data-aos-delay="200">
                    Iniciar sesión
                </h2>

                <form action="login.php" method="post" class="contact-form webform" 
                      data-aos="fade-up" data-aos-delay="400" role="form">

                    <!-- Email -->
                    <input type="email" class="form-control" name="email" placeholder="Correo electrónico" required>

                    <!-- Password -->
                    <input type="password" class="form-control" name="password" placeholder="Contraseña" required>

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
