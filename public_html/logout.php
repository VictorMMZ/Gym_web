<?php
// logout.php: cierra la sesión del usuario y redirige al inicio
require_once '../app/auth.php';

cerrarSesion();
header("Location: index.php");
exit;
?>