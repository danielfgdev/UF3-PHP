<?php
$cadena_conexion = 'mysql:dbname=usuarios;host=127.0.0.1:3306';
$usuario = 'root';
$clave = '';
try {
    $pdo = new PDO($cadena_conexion, $usuario, $clave);
    // $db = new PDO($cadena_conexion, $usuario, $clave, array(PDO::ATTR_PERSISTENT => true));
    echo 'Conexión OK<br>';
} catch (PDOException $e) {
    echo 'Error con la base de datos: ' . $e->getMessage();
}
