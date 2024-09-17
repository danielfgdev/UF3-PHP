<?php
$cadena_conexion = 'mysql:dbname=northwind;host=127.0.0.1';
$usuario = 'root';
$clave = '123456';
try {
    $db = new PDO(
        $cadena_conexion,
        $usuario,
        $clave,
        array(PDO::ATTR_PERSISTENT => true)
    );
    $sql = "SELECT * FROM customers";
    $customers = $db->query($sql);
    echo 'Total clientes: ' . $customers->rowCount() . '<br>';
    foreach ($customers as $row) {
        print $row['CustomerName'] . '<br>';
        print $row['City'] . '<br>';
        print $row['Country'] . '<br>';
        print '-----------------------------------<br>';
    }
} catch (PDOException $e) {
    echo 'Error con la base de datos: ' . $e->getMessage();
}