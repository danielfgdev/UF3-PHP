<?php
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$data = "$nombre;$apellidos";
$archivo = fopen("usuarios.txt", "a") or die("Error de archivo");
fputs($archivo, $data);
fputs($archivo, "\n");

fclose($archivo);
