<?php

$cadena_conexion = 'mysql:dbname=northwind;host=127.0.0.1';
$usuario = 'root';
$clave = '';
try {
    $db = new PDO($cadena_conexion, $usuario, $clave);
    echo 'Conexion Exitosa';
    // $db -> close();
} catch (PDOException $e) {
    echo 'Error con la base de datos: ' . $e->getMessage();
}
