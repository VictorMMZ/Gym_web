<?php
include '../includes/head_sidebar_admin.php'
?>

<div class="content">
    <h2 class="mb-4" style="color:tomato">Facturación</h2>

    <!-- TOTAL FACTURADO -->
    <div class="card shadow-sm mb-4" style="max-width: 400px;">
        <div class="card-body">
            <h5>Total facturado este mes</h5>

            <!-- Aquí pones tú el total manualmente -->
            <p class="display-6" style="color:green; font-weight:bold;">
                450€
            </p>
        </div>
    </div>

    <!-- TABLA DE USUARIOS -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Email</th>
                <th>Pagado</th>
                <th>Cambiar estado</th>
                <th>Eliminar</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Carlos Pérez</td>
                <td>carlos@example.com</td>
                <td>
                    <span class="badge bg-success">Pagado</span>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm">Cambiar</button>
                </td>
                <td>
                    <button class="btn btn-danger btn-sm">🗑 Eliminar</button>
                </td>
            </tr>

            <tr>
                <td>Ana López</td>
                <td>ana@example.com</td>
                <td>
                    <span class="badge bg-danger">No pagado</span>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm">Cambiar</button>
                </td>
                <td>
                    <button class="btn btn-danger btn-sm">🗑 Eliminar</button>
                </td>
            </tr>

            <tr>
                <td>María Torres</td>
                <td>maria@example.com</td>
                <td>
                    <span class="badge bg-success">Pagado</span>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm">Cambiar</button>
                </td>
                <td>
                    <button class="btn btn-danger btn-sm">🗑 Eliminar</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php
   
     include '../includes/scripts.php';

    ?>
</body>
</html>
