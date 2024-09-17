<?php
require_once("pdo-conexion.php");
$sql = "SELECT nombre_paciente, apellido_paciente, direccion_paciente FROM paciente";
try {
    $pacientes = $db->query($sql);
    echo 'Total pacientes: ' . $pacientes->rowCount() . '<br>';
    foreach ($pacientes as $row) {
        print $row['nombre_paciente'] . '<br>';
        print $row['apellido_paciente'] . '<br>';
        print $row['direccion_paciente'] . '<br>';
        print '-----------------------------------<br>';
    }
} catch (PDOException $e) {
    echo 'Error con la base de datos: ' . $e->getMessage();
}