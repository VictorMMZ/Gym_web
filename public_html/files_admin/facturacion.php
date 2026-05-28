<?php
include '../includes/head_sidebar_admin.php';
require_once '../../app/auth.php';
require_once '../../config/db.php';
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_facturado'])) {
    $idFacturacion = intval($_POST['toggle_facturado']);
    $toggleSql = "
        UPDATE facturacion
        SET facturado = 1 - facturado
        WHERE id_facturacion = :id_facturacion
    ";
    $toggleStmt = $pdo->prepare($toggleSql);
    $toggleStmt->execute([':id_facturacion' => $idFacturacion]);

    header('Location: facturacion.php');
    exit;
}

$sql = "
INSERT INTO facturacion (id_usuario,id_plan,facturado)
SELECT u.id_usuario, u.id_plan, 1
FROM usuarios u
WHERE u.rol = 'usuario'
  AND u.id_plan IS NOT NULL
  AND u.id_usuario NOT IN (
      SELECT id_usuario FROM facturacion
  )
";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$facturacionQuery = "
SELECT f.id_facturacion,
       u.id_usuario,
       u.nombre,
       u.apellidos,
       u.correo,
       p.nombre AS plan_nombre,
       p.precio,
       f.facturado
FROM facturacion f
JOIN usuarios u ON f.id_usuario = u.id_usuario
LEFT JOIN planes p ON f.id_plan = p.id_plan
WHERE u.rol = 'usuario'
ORDER BY u.apellidos, u.nombre
";
$stmt = $pdo->prepare($facturacionQuery);
$stmt->execute();
$facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalQuery = "
SELECT COALESCE(SUM(p.precio), 0) AS total_facturado
FROM usuarios u
JOIN planes p ON u.id_plan = p.id_plan
JOIN facturacion f ON f.id_usuario = u.id_usuario
WHERE u.rol = 'usuario'
  AND u.id_plan IS NOT NULL
  AND f.facturado = 1
";
$totalStmt = $pdo->prepare($totalQuery);
$totalStmt->execute();
$totalFacturado = $totalStmt->fetchColumn();

function formatoMoneda($cantidad)
{
    return number_format((float) $cantidad, 2, ',', '.') . ' €';
}
?>

<div class="content">
    <h2 class="mb-4" style="color:tomato">Facturación</h2>

    <!-- TOTAL FACTURADO -->
    <div class="card shadow-sm mb-4" style="max-width: 400px;">
        <div class="card-body">
            <h5>Total facturado por usuarios activos</h5>
            <p class="display-6" style="color:green; font-weight:bold;">
                <?= formatoMoneda($totalFacturado) ?>
            </p>
        </div>
    </div>

    <!-- TABLA DE FACTURACIÓN -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Email</th>
                <th>Plan</th>
                <th>Precio</th>
                <th>Pagado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($facturas)): ?>
                <tr>
                    <td colspan="6" class="text-center">No hay datos de facturación disponibles.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($facturas as $factura): ?>
                    <tr>
                        <td><?= htmlspecialchars($factura['nombre'] . ' ' . $factura['apellidos']) ?></td>
                        <td><?= htmlspecialchars($factura['correo']) ?></td>
                        <td><?= htmlspecialchars($factura['plan_nombre'] ?? 'Sin plan') ?></td>
                        <td><?= isset($factura['precio']) ? formatoMoneda($factura['precio']) : '0,00 €' ?></td>
                        <td>
                            <?php if ($factura['facturado']): ?>
                                <span class="badge bg-success">Pagado</span>
                            <?php else: ?>
                                <span class="badge bg-danger">No pagado</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="post" style="display:inline-block; margin:0;">
                                <input type="hidden" name="toggle_facturado" value="<?= intval($factura['id_facturacion']) ?>">
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <?= $factura['facturado'] ? 'Marcar no pagado' : 'Marcar pagado' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
include '../includes/scripts.php';
?>
</body>
</html>
