<?php
    include '../includes/head_sidebar_user.php';
    require_once '../../config/db.php';

    $id_usuario = $_SESSION['user_id'] ?? null;
    $nombre = '';
    $correo = '';
    $telefono = '';
    $membresia = '';

    if ($id_usuario) {
        $stmt = $pdo->prepare("SELECT u.nombre, u.apellidos, u.correo, u.telefono, p.nombre AS plan_nombre FROM usuarios u LEFT JOIN planes p ON u.id_plan = p.id_plan WHERE u.id_usuario = ?");
        $stmt->execute([$id_usuario]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario) {
            $nombre = trim($usuario['nombre'] . ' ' . $usuario['apellidos']);
            $correo = $usuario['correo'];
            $telefono = $usuario['telefono'];
            $membresia = $usuario['plan_nombre'] ?? "Básico";
        }
    }
?>
    <div class="planes content d-flex justify-content-center">
    <div class="card shadow-sm" style="max-width: 480px; width: 100%; height: fit-content; background: rgba(255, 255, 255, 0.47);" data-aos="fade-up">

        <div class="card-body">
            <h4 class="mb-4 text-center" >Mi Perfil</h4>

            <form action="actualizar_perfil.php" method="POST">

                <!-- Nombre -->
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($nombre) ?>" readonly>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($correo) ?>">
                </div>

                <!-- Teléfono -->
                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" class="form-control" name="telefono" value="<?= htmlspecialchars($telefono) ?>">
                </div>

                <!-- Contraseña -->
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="contraseña" placeholder="Dejar en blanco para mantener la actual">
                </div>

                <!-- Membresía (solo lectura o editable, tú decides) -->
                <div class="mb-3">
                    <label class="form-label">Membresía</label>
                    <input type="text" class="form-control" name="membresia" value="<?= htmlspecialchars($membresia) ?>" readonly>
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