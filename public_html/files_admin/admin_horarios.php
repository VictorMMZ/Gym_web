<?php
include '../includes/head_sidebar_admin.php';
require_once '../../app/HorarioHelper.php';

if (isset($_POST['submit'])) {
    $nuevo_horario_apertura = $_POST['horario_apertura'];
    $nuevo_horario_cierre = $_POST['horario_cierre'];

    $horarioHelper = new HorarioHelper();
    $horarioHelper->actualizarHorario($nuevo_horario_apertura, $nuevo_horario_cierre);
}
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
                <?php
                $horarioHelper = new HorarioHelper();
                $horaApertura = $horarioHelper->obtenerHoraApertura();
                $horaCierre = $horarioHelper->obtenerHoraCierre();
                ?>
                <td><?php echo $horaApertura[0]['horario_apertura'] . '-' . $horaCierre[0]['horario_cierre']; ?></td>
                <form method="post">
                <td>
                    <input type="time" class="form-control" name="horario_apertura" value="<?php echo $horaApertura[0]['horario_apertura']; ?>">
                    <input type="time" class="form-control" name="horario_cierre" value="<?php echo $horaCierre[0]['horario_cierre']; ?>">
                </td>
                <td>
                    <button type="submit" name="submit" class="btn btn-primary btn-sm">💾 Guardar</button>
                </td>
                </form>
            </tr>
        </tbody>
    </table>
</div>
<?php
   
     include '../includes/scripts.php';

    ?>

</body>
</html>
