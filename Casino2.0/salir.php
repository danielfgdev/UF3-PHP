<?php
/* This PHP code snippet is performing the following actions: */

// Inicio de la sesion.
session_start();


// Eliminar las variables de sesion.
session_unset();


// Destruir la sesion.
session_destroy();


// Redirigir a index.php.
header("Location: index.php");


// Terminar el script.
exit();
