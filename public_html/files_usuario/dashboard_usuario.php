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

    <!-- CONTENIDO PRINCIPAL -->
    <div class="planes content">
    <h2 class="mb-4" style="color:tomato">Dashboard</h2>

    <div class="row g-4 mb-4" data-aos="fade-up" data-aos-delay="200">

        <!-- Membresía -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Membresía actual</h5>
                    <p class="text-muted">Tipo: <strong>Premium</strong></p>
                    <p class="text-muted">Renovación: 12/06/2026</p>
                </div>
            </div>
        </div>

        <!-- Clases realizadas -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Clases realizadas</h5>
                    <p><strong>Este mes:</strong> 8</p>
                    <p><strong>Total:</strong> 42</p>
                </div>
            </div>
        </div>

    </div>

    <!-- PRÓXIMAS CLASES -->
<div class="card shadow-sm mb-4" data-aos="fade-left" data-aos-delay="200" style="width: 600px">
    <div class="card-header bg-info text-white">
        Próximas clases
    </div>
    <div class="card-body">

        <div class="d-flex justify-content-between border-bottom pb-2 mb-2" >
            <div>
                <strong>Yoga</strong><br>
                <small>Lunes 10:00 - Sala 1</small>
            </div>
             <span class="badge bg-primary d-flex align-items-center justify-content-center px-4 py-2" 
      style="min-width: 120px;">
    En 2 días
</span>
        </div>

        <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
            <div>
                <strong>Cycling</strong><br>
                <small>Martes 18:00 - Sala 2</small>
            </div>    
                 <span class="badge bg-primary d-flex align-items-center justify-content-center px-4 py-2" 
      style="min-width: 120px;">
    En 3 días
</span>
</div>
        

        <div class="d-flex justify-content-between">
            <div>
                <strong>Funcional</strong><br>
                <small>Viernes 19:00 - Sala 3</small>
            </div>
             <span class="badge bg-primary d-flex align-items-center justify-content-center px-4 py-2" 
      style="min-width: 120px;">
    En 5 días
</span>
        </div>

    </div>
</div>


<!-- HISTORIAL RECIENTE -->
<div class="card shadow-sm mb-4" data-aos="fade-down" data-aos-delay="200">
    <div class="card-header bg-secondary text-white">
        Historial reciente
    </div>
    <div class="card-body">

        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Clase</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Boxeo</td>
                    <td>20/04/2026</td>
                    <td><span class="badge bg-success">Asistió</span></td>
                </tr>
                <tr>
                    <td>Pilates</td>
                    <td>18/04/2026</td>
                    <td><span class="badge bg-success">Asistió</span></td>
                </tr>
                <tr>
                    <td>Yoga</td>
                    <td>15/04/2026</td>
                    <td><span class="badge bg-danger">Faltó</span></td>
                </tr>
            </tbody>
        </table>

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
