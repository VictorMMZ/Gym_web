<?php
include "./includes/header.php"
?>
    <!-- SIGN UP -->
    <section class="backsignup contact section flex-fill " id="signin">
          <div class="container">
               <div class="row">

                    <div class="mx-auto col-lg-5 col-md-6 col-12">
                        <h2 class="mb-4 pb-2" style="color:tomato" data-aos="fade-up" data-aos-delay="200">Tus Datos</h2>

                        <form action="login.php" method="post" class="contact-form webform" data-aos="fade-up" data-aos-delay="400" role="form">
                            <input type="text" class="form-control" name="user-name" placeholder="Name">
                            <input type="text" class="form-control" name="user-lastname" placeholder="Last Name">                         
                            <input type="text" class="form-control" name="user-phone" placeholder="Phone">
                            <input type="text" class="form-control" name="birth-date" placeholder="Fecha de nacimiento (dd/mm/aaaa)">
                            
       <div class="mb-3 mt-2" style="background: #fff; border-radius: 6px;">
    <label class="form-control"  >Género</label>

    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="genero" value="Hombre" id="genHombre">
        <label class="form-check-label" for="genHombre" >Hombre</label>
    </div>

    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="genero" value="Mujer" id="genMujer">
        <label class="form-check-label" for="genMujer" >Mujer</label>
    </div>

    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="genero" value="Otro" id="genOtro">
        <label class="form-check-label" for="genOtro" >Otro</label>
    </div>
</div>

                            <input type="text" class="form-control" name="zip_code" placeholder="Código Postal">
                             <a href="registro2.php" class="btn btn-dark w-100" id="submit-button" name="registro_siguiente">Continuar</a>
                            <a href="signup_plan.php" class="btn btn-secondary w-100 mt-2" id="back-button">Go back</a>


                        </form>
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

