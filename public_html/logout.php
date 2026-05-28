<?php
require_once '../app/auth.php';

cerrarSesion();
header("Location: index.php");
exit;
?>