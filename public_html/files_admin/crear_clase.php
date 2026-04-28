<?php
include '../includes/sidebar_admin.php'
?>


<div class="content">
    <h2 class="mb-4" style="color:tomato">Crear nueva clase</h2>

    <form action="procesar_crear_clase.php" method="POST" class="card p-4 shadow-sm" style="max-width: 600px;">
        
        <label class="form-label">Nombre de la clase</label>
        <input type="text" name="nombre" class="form-control mb-3" required>

        <label class="form-label">Día</label>
        <select name="dia" class="form-select mb-3">
            <option>Lunes</option><option>Martes</option><option>Miércoles</option>
            <option>Jueves</option><option>Viernes</option><option>Sábado</option>
        </select>

        <label class="form-label">Hora</label>
        <input type="time" name="hora" class="form-control mb-3" required>

        <label class="form-label">Sala</label>
        <input type="text" name="sala" class="form-control mb-3" required>

        <button class="btn btn-primary w-100">Guardar clase</button>
    </form>
</div>
</body>
</html>