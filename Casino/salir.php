<?php
session_start();
session_destroy(); // Destruye la sesión actual
header("Location: index.php"); // Redirige al inicio de sesión
exit(); // Asegura que no se ejecute más código después de la redirección
