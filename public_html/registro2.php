<?php
include "./includes/header.php"
?>
    <!-- SIGN UP -->
    <section class="backsignup contact section flex-fill " id="signin">
          <div class="container">
               <div class="row">

                    <div class="mx-auto col-lg-5 col-md-6 col-12">
                        <h2 class="mb-4 pb-2" style="color:tomato" data-aos="fade-up" data-aos-delay="200">Registro</h2>

                        <form action="registro2.php" method="post" class="contact-form webform" data-aos="fade-up" data-aos-delay="400" role="form">

                            <input type="text" class="form-control" name="usuario" placeholder=" Nombre usuario">
                            <input type="text" class="form-control" name="password" placeholder="Contraseña">
                            <input type="text" class="form-control" name="repassword" placeholder="Repita la contraseña">
                            <button type="submit" class="btn btn-primary w-100" id="submit-button" name="submit">Registrarse </button>
                            <a href="login.php" class="btn btn-secondary w-100 mt-2" id="back-button">Go back</a>
                             </form>
                    </div>
                       
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

