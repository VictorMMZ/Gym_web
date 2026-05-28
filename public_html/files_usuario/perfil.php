<?php
    include '../includes/head_sidebar_user.php';
?>
    <div class="planes content d-flex justify-content-center">
    <div class="card shadow-sm" style="max-width: 480px; width: 100%; height: fit-content; background: rgba(255, 255, 255, 0.47);" data-aos="fade-up">

        <div class="card-body">
            <h4 class="mb-4 text-center" >Mi Perfil</h4>

            <form action="actualizar_perfil.php" method="POST">

                <!-- Nombre -->
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" value="Víctor García" readonly>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" name="email" value="victor@example.com">
                </div>

                <!-- Teléfono -->
                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" class="form-control" name="telefono" value="600 123 456">
                </div>

                <!-- Contraseña -->
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="telefono" value="xxxx"> <!-- si cambia contraseña abrir un bloque que pida contraseña anterior y contraseña nueva si coincide la anterior entonces cambiar a la nueva-->
                </div>

                <!-- Membresía (solo lectura o editable, tú decides) -->
                <div class="mb-3">
                    <label class="form-label">Membresía</label>  <!-- Al cambiar de membresia se desplegara un un bloque con las opciones y precios al elegir se le recordara que se le cambiara el precio de la cuota,una vez acepte queda editada para el siguiente periodo -->
                    <input type="text" class="form-control" name="membresia" value="Premium" readonly>
                </div>

                <!-- Botón -->
                <button type="submit" class="btn btn-primary w-100">
                    Guardar cambios
                </button>

            </form>
        </div>
    </div>
</div>
 <?php
   
    include '../includes/scripts.php';

    ?>
</body>
</html>