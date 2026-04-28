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
        <h4 class="text-center mb-4" style="color:tomato">Mi Gimnasio</h4>

        <a href="dashboard_usuario.php">🏠 Inicio</a>
        <a href="clases.php">📅 Clases disponibles</a>
        <a href="mis_clases.php">✔ Mis clases</a>
        <a href="perfil.php">👤 Perfil</a>
        <a href="index.php">🚪 Cerrar sesión</a>
    </div>

<div class=" planes content">
    <h2 class="mb-4" style="color:tomato">Clases disponibles</h2>

    <div class="row g-4" data-aos="fade-down" data-aos-delay="200">

        <!-- CARD -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Yoga</h5>
                    <p class="text-muted">Lunes 10:00 - Sala 1</p>
                    <form action="apuntarse.php" method="post">
                        <input type="hidden" name="clase_id" value="1">
                        <button class="btn btn-success w-100">Apuntarme</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- CARD -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Cycling</h5>
                    <p class="text-muted">Martes 18:00 - Sala 2</p>
                    <form action="apuntarse.php" method="post">
                        <input type="hidden" name="clase_id" value="2">
                        <button class="btn btn-success w-100">Apuntarme</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- CARD -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Funcional</h5>
                    <p class="text-muted">Viernes 19:00 - Sala 3</p>
                    <form action="apuntarse.php" method="post">
                        <input type="hidden" name="clase_id" value="3">
                        <button class="btn btn-success w-100">Apuntarme</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

   <script src="./assets/js/jquery.min.js"></script>
     <script src="./assets/js/bootstrap.min.js"></script>
     <script src="./assets/js/aos.js"></script>
     <script src="./assets/js/smoothscroll.js"></script>
     <script src="./assets/js/custom.js"></script>

</body>
</html>