<?php
$cadena_conexion = 'mysql:dbname=hospital;host=127.0.0.1';
$usuario = 'root';
$clave = '123456';
try {
    $db = new PDO(
        $cadena_conexion,
        $usuario,
        $clave,
        array(PDO::ATTR_PERSISTENT => true)
    );
} catch (PDOException $e) {
    echo 'Error con la base de datos: ' . $e->getMessage();
}
