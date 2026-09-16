
<?php

require_once '../../app/auth.php';

if (!estaLogueado() || !in_array(obtenerRol(), ['admin', 'entrenador'])) {
    header("Location: ../login.php");
    exit;
}

include '../includes/head_sidebar_admin.php';

global $pdo;
/*
Variables del dashboard
*/

$usuarios_count = 0;
$clases_activas = 0;
$asistencias_mes = 0;

$usuarios_sin_plan = 0;
$facturas_pendientes = 0;
$facturas_facturadas = 0;

$proximas_clases = [];
$asistencias_semana = [];
$usuarios_por_plan = [];
$actividad_reciente = [];


/*
|--------------------------------------------------------------------------
| Obtener datos
|--------------------------------------------------------------------------
*/

try {

   /* Usuarios registrados */

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM usuarios
        WHERE rol = 'usuario'
    ");

    $usuarios_count = (int) $stmt->fetchColumn();


    /* Clases próximas */

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM clases
        WHERE fecha_hora >= NOW()
    ");

    $clases_activas = (int) $stmt->fetchColumn();


    /* Asistencias del mes */

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM inscripciones i
        INNER JOIN clases c
            ON i.id_clase = c.id_clase
        WHERE i.estado = 'finalizado'
        AND MONTH(c.fecha_hora) = MONTH(CURRENT_DATE())
        AND YEAR(c.fecha_hora) = YEAR(CURRENT_DATE())
    ");

    $asistencias_mes = (int) $stmt->fetchColumn();


    /* Usuarios sin plan */

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM usuarios
        WHERE rol = 'usuario'
        AND id_plan IS NULL
    ");

    $usuarios_sin_plan = (int) $stmt->fetchColumn();


    /* Facturación */

    $stmt = $pdo->query("
        SELECT
            SUM(CASE WHEN facturado = 0 THEN 1 ELSE 0 END) AS pendientes,
            SUM(CASE WHEN facturado = 1 THEN 1 ELSE 0 END) AS facturadas
        FROM facturacion
    ");

    $facturacion = $stmt->fetch(PDO::FETCH_ASSOC);

    $facturas_pendientes = (int) ($facturacion['pendientes'] ?? 0);
    $facturas_facturadas = (int) ($facturacion['facturadas'] ?? 0);


    /* Próximas clases */

    $stmt = $pdo->prepare("
        SELECT
            c.id_clase,
            c.nombre,
            c.fecha_hora,
            c.capacidad,
            COUNT(i.id_inscripcion) AS inscritos
        FROM clases c
        LEFT JOIN inscripciones i
            ON c.id_clase = i.id_clase
            AND i.estado != 'cancelada'
        WHERE c.fecha_hora >= NOW()
        GROUP BY
            c.id_clase,
            c.nombre,
            c.fecha_hora,
            c.capacidad
        ORDER BY c.fecha_hora ASC
        LIMIT 5
    ");

    $stmt->execute();

    $proximas_clases = $stmt->fetchAll(PDO::FETCH_ASSOC);


    /* Asistencias últimos 7 días */

    $stmt = $pdo->prepare("
        SELECT
            DATE(c.fecha_hora) AS fecha,
            COUNT(*) AS total
        FROM inscripciones i
        INNER JOIN clases c
            ON i.id_clase = c.id_clase
        WHERE i.estado = 'finalizado'
        AND c.fecha_hora >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 DAY)
        GROUP BY DATE(c.fecha_hora)
        ORDER BY fecha ASC
    ");

    $stmt->execute();

    $datos_asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);


    /* Inicializar los 7 días */

    for ($i = 6; $i >= 0; $i--) {

        $fecha = date('Y-m-d', strtotime("-$i days"));

        $asistencias_semana[$fecha] = 0;
    }


    foreach ($datos_asistencias as $fila) {

        if (isset($asistencias_semana[$fila['fecha']])) {

            $asistencias_semana[$fila['fecha']] =
                (int) $fila['total'];
        }
    }


    /* Usuarios por plan */

    $stmt = $pdo->query("
        SELECT
            p.nombre AS plan,
            COUNT(u.id_usuario) AS total
        FROM planes p
        LEFT JOIN usuarios u
            ON p.id_plan = u.id_plan
            AND u.rol = 'usuario'
        GROUP BY p.id_plan, p.nombre
        ORDER BY p.id_plan
    ");

    $usuarios_por_plan = $stmt->fetchAll(PDO::FETCH_ASSOC);


    /* Actividad reciente */                    

    $stmt = $pdo->query("
        SELECT
            i.id_inscripcion,
            i.estado,
            i.fecha_inscripcion,
            u.nombre,
            u.apellidos,
            c.nombre AS clase
        FROM inscripciones i
        INNER JOIN usuarios u
            ON i.id_usuario = u.id_usuario
        INNER JOIN clases c
            ON i.id_clase = c.id_clase
        ORDER BY i.fecha_inscripcion DESC
        LIMIT 6
    ");

    $actividad_reciente = $stmt->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {

    /*
    |--------------------------------------------------------------------------
    | Si ocurre un error, evitamos romper el dashboard
    |--------------------------------------------------------------------------
    */

    $usuarios_count = 0;
    $clases_activas = 0;
    $asistencias_mes = 0;

    $usuarios_sin_plan = 0;
    $facturas_pendientes = 0;
    $facturas_facturadas = 0;

    $proximas_clases = [];
    $asistencias_semana = [];
    $usuarios_por_plan = [];
    $actividad_reciente = [];
}

?>


<div class="content">

    <!-- =========================================================
         CABECERA
    ========================================================== -->

    <div class="mb-4">

        <h2 style="color: tomato;" class="mb-1">
            Panel de Administración
        </h2>

        <p class="text-muted mb-0">
            Resumen general de Gym Web
        </p>

    </div>


    <!-- =========================================================
         MÉTRICAS PRINCIPALES
    ========================================================== -->

    <div class="row g-4 mb-4">

        <!-- Usuarios -->

        <div class="col-md-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center">

                    <i class="bi bi-people-fill fs-2 text-primary"></i>

                    <h6 class="text-muted mt-2">
                        Usuarios registrados
                    </h6>

                    <div class="display-6 fw-bold">
                        <?= htmlspecialchars($usuarios_count) ?>
                    </div>

                </div>

            </div>

        </div>


        <!-- Clases -->

        <div class="col-md-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center">

                    <i class="bi bi-calendar-check-fill fs-2 text-success"></i>

                    <h6 class="text-muted mt-2">
                        Clases activas
                    </h6>

                    <div class="display-6 fw-bold">
                        <?= htmlspecialchars($clases_activas) ?>
                    </div>

                </div>

            </div>

        </div>


        <!-- Asistencias -->

        <div class="col-md-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center">

                    <i class="bi bi-person-check-fill fs-2 text-warning"></i>

                    <h6 class="text-muted mt-2">
                        Asistencias este mes
                    </h6>

                    <div class="display-6 fw-bold">
                        <?= htmlspecialchars($asistencias_mes) ?>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         SEGUNDA FILA
    ========================================================== -->

    <div class="row g-4 mb-4">


        <!-- PRÓXIMAS CLASES -->

        <div class="col-lg-8">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event me-2"></i>
                        Próximas clases
                    </h5>

                </div>


                <div class="card-body px-4">

                    <?php if (empty($proximas_clases)): ?>

                        <div class="text-center text-muted py-4">

                            <i class="bi bi-calendar-x fs-2"></i>

                            <p class="mt-2 mb-0">
                                No hay próximas clases.
                            </p>

                        </div>

                    <?php else: ?>

                        <div class="table-responsive">

                            <table class="table align-middle mb-0">

                                <thead>

                                    <tr>

                                        <th>Clase</th>

                                        <th>Fecha</th>

                                        <th>Hora</th>

                                        <th>Ocupación</th>

                                    </tr>

                                </thead>


                                <tbody>

                                    <?php foreach ($proximas_clases as $clase): ?>

                                        <?php

                                        $timestamp =
                                            strtotime($clase['fecha_hora']);

                                        $fecha =
                                            date('d/m/Y', $timestamp);

                                        $hora =
                                            date('H:i', $timestamp);

                                        $capacidad =
                                            (int) $clase['capacidad'];

                                        $inscritos =
                                            (int) $clase['inscritos'];

                                        $porcentaje =
                                            $capacidad > 0
                                                ? min(
                                                    100,
                                                    ($inscritos / $capacidad) * 100
                                                )
                                                : 0;

                                        ?>

                                        <tr>

                                            <td>

                                                <strong>
                                                    <?= htmlspecialchars(
                                                        $clase['nombre']
                                                    ) ?>
                                                </strong>

                                            </td>


                                            <td>
                                                <?= htmlspecialchars($fecha) ?>
                                            </td>


                                            <td>
                                                <?= htmlspecialchars($hora) ?>
                                            </td>


                                            <td style="min-width: 160px;">

                                                <div class="d-flex justify-content-between">

                                                    <small>
                                                        <?= $inscritos ?> /
                                                        <?= $capacidad ?>
                                                    </small>

                                                    <small>
                                                        <?= round($porcentaje) ?>%
                                                    </small>

                                                </div>


                                                <div
                                                    class="progress mt-1"
                                                    style="height: 6px;"
                                                >

                                                    <div
                                                        class="progress-bar"
                                                        role="progressbar"
                                                        style="width: <?= $porcentaje ?>%;"
                                                    ></div>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>


        <!-- RESUMEN GENERAL -->

        <div class="col-lg-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart-fill me-2"></i>
                        Resumen general
                    </h5>

                </div>


                <div class="card-body px-4">


                    <!-- Usuarios sin plan -->

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>

                            <div class="text-muted small">
                                Usuarios sin plan
                            </div>

                            <strong>
                                Pendientes de asignación
                            </strong>

                        </div>

                        <span class="badge bg-warning text-dark fs-6">
                            <?= htmlspecialchars($usuarios_sin_plan) ?>
                        </span>

                    </div>


                    <!-- Facturación pendiente -->

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>

                            <div class="text-muted small">
                                Facturación
                            </div>

                            <strong>
                                Pendiente
                            </strong>

                        </div>

                        <span class="badge bg-danger fs-6">
                            <?= htmlspecialchars($facturas_pendientes) ?>
                        </span>

                    </div>


                    <!-- Facturación realizada -->

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small">
                                Facturación
                            </div>

                            <strong>
                                Facturado
                            </strong>

                        </div>

                        <span class="badge bg-success fs-6">
                            <?= htmlspecialchars($facturas_facturadas) ?>
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         TERCERA FILA
    ========================================================== -->

    <div class="row g-4">


        <!-- GRÁFICO ASISTENCIAS -->

        <div class="col-lg-7">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>
                        Asistencias de los últimos 7 días
                    </h5>

                </div>


                <div class="card-body">

                    <div style="height: 280px;">

                        <canvas id="asistenciasChart"></canvas>

                    </div>

                </div>

            </div>

        </div>


        <!-- USUARIOS POR PLAN -->

        <div class="col-lg-5">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart-fill me-2"></i>
                        Usuarios por plan
                    </h5>

                </div>


                <div class="card-body">

                    <div style="height: 280px;">

                        <canvas id="planesChart"></canvas>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         CUARTA FILA
    ========================================================== -->

    <div class="row g-4 mt-1">


        <!-- ACTIVIDAD RECIENTE -->

        <div class="col-12">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Actividad reciente
                    </h5>

                </div>


                <div class="card-body px-4">

                    <?php if (empty($actividad_reciente)): ?>

                        <p class="text-muted text-center py-3 mb-0">
                            No hay actividad reciente.
                        </p>

                    <?php else: ?>

                        <div class="row">

                            <?php foreach ($actividad_reciente as $actividad): ?>

                                <div class="col-md-6 col-xl-4 mb-3">

                                    <div class="d-flex align-items-start">

                                        <div class="me-3">

                                            <?php if ($actividad['estado'] === 'finalizado'): ?>

                                                <i class="bi bi-check-circle-fill text-success fs-5"></i>

                                            <?php elseif ($actividad['estado'] === 'cancelada'): ?>

                                                <i class="bi bi-x-circle-fill text-danger fs-5"></i>

                                            <?php else: ?>

                                                <i class="bi bi-calendar-plus-fill text-primary fs-5"></i>

                                            <?php endif; ?>

                                        </div>


                                        <div>

                                            <strong>

                                                <?= htmlspecialchars(
                                                    $actividad['nombre']
                                                    . ' '
                                                    . ($actividad['apellidos'] ?? '')
                                                ) ?>

                                            </strong>


                                            <div class="text-muted small">

                                                Inscripción en
                                                <strong>
                                                    <?= htmlspecialchars(
                                                        $actividad['clase']
                                                    ) ?>
                                                </strong>

                                            </div>


                                            <div class="text-muted small">

                                                <?= htmlspecialchars(
                                                    date(
                                                        'd/m/Y H:i',
                                                        strtotime(
                                                            $actividad['fecha_inscripcion']
                                                        )
                                                    )
                                                ) ?>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     CHART.JS
========================================================== -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

    /*
    |--------------------------------------------------------------------------
    | Gráfico de asistencias
    |--------------------------------------------------------------------------
    */

    const etiquetasAsistencias = [

        <?php foreach ($asistencias_semana as $fecha => $total): ?>

            '<?= date('d/m', strtotime($fecha)) ?>',

        <?php endforeach; ?>

    ];


    const datosAsistencias = [

        <?php foreach ($asistencias_semana as $total): ?>

            <?= (int) $total ?>,

        <?php endforeach; ?>

    ];


    const ctxAsistencias =
        document.getElementById('asistenciasChart');


    new Chart(ctxAsistencias, {

        type: 'bar',

        data: {

            labels: etiquetasAsistencias,

            datasets: [

                {

                    label: 'Asistencias',

                    data: datosAsistencias,

                    borderWidth: 1,

                    borderRadius: 6

                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {
                    display: false
                }

            },

            scales: {

                y: {

                    beginAtZero: true,

                    ticks: {
                        precision: 0
                    }

                }

            }

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Gráfico de usuarios por plan
    |--------------------------------------------------------------------------
    */

    const etiquetasPlanes = [

        <?php foreach ($usuarios_por_plan as $plan): ?>

            '<?= htmlspecialchars($plan['plan'], ENT_QUOTES) ?>',

        <?php endforeach; ?>

    ];


    const datosPlanes = [

        <?php foreach ($usuarios_por_plan as $plan): ?>

            <?= (int) $plan['total'] ?>,

        <?php endforeach; ?>

    ];


    const ctxPlanes =
        document.getElementById('planesChart');


    new Chart(ctxPlanes, {

        type: 'doughnut',

        data: {

            labels: etiquetasPlanes,

            datasets: [

                {

                    label: 'Usuarios',

                    data: datosPlanes,

                    borderWidth: 1

                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    position: 'bottom'

                }

            }

        }

    });

</script>


<?php

include '../includes/scripts.php';

?>

</body>

</html>

