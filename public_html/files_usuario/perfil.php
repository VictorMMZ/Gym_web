<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Usuario</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
     <link rel="stylesheet" href="./assets/css/font-awesome.min.css">
     <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

   


     <!-- MAIN CSS -->
     <link rel="stylesheet" href="./assets/css/tooplate-gymso-style.css">
    <style>
        .sidebar {
            width: 220px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #212529;
            padding-top: 20px;
        }

        .sidebar a {
            color: #ddd;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            font-size: 15px;
        }

        .sidebar a:hover {
            background: #343a40;
            color: #fff;
        }

        .content {
            margin-left: 220px;
            padding: 30px;
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h4 class=" text-center mb-4" style="color:tomato">Mi Gimnasio</h4>

        <a href="dashboard_usuario.php">🏠 Inicio</a>
        <a href="clases.php">📅 Clases disponibles</a>
        <a href="mis_clases.php">✔ Mis clases</a>
        <a href="perfil.php">👤 Perfil</a>
        <a href="index.php">🚪 Cerrar sesión</a>
    </div>

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
 <!-- SCRIPTS -->
     <script src="./assets/js/jquery.min.js"></script>
     <script src="./assets/js/bootstrap.min.js"></script>
     <script src="./assets/js/aos.js"></script>
     <script src="./assets/js/smoothscroll.js"></script>
     <script src="./assets/js/custom.js"></script>
</body>
</html>