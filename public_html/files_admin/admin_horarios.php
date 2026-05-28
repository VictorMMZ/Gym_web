<?php
include '../includes/head_sidebar_admin.php'
?>

<div class="content">
    <h2 class="mb-4" style="color:tomato">Modificar Horario Gimnasio</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                
                <th>Horario actual</th>
                <th>Nuevo horario</th>
                <th>Guardar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                
                <td>10:00-20:00</td>
                <td>
                    <input type="time" class="form-control" value="10:00">
                    <input type="time" class="form-control" value="20:00">
                </td>
                <td>
                    <button class="btn btn-primary btn-sm">💾 Guardar</button>
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
