<?php
$cadena_conexion = 'mysql:dbname=hospital;host=127.0.0.1';
$usuario = 'root';
$clave = '';
try {
    $db = new PDO($cadena_conexion, $usuario, $clave, array(PDO::ATTR_PERSISTENT => true));
    echo '<script>alert("Conexión OK")</script>';
} catch (PDOException $e) {
    echo 'Error con la base de datos: ' . $e->getMessage();
}