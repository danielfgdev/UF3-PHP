<?php
include 'conexion.php';

$id_usuario = $_POST['id_usuario'];
$nombre_usuario = $_POST['nombre_usuario'];
$apellido_usuario = $_POST['apellido_usuario'];
$dni_usuario = $_POST['dni_usuario'];
$direccion_usuario = $_POST['direccion_usuario'];
$telefono_usuario = $_POST['telefono_usuario'];

$sql = "UPDATE usuarios SET nombre_usuario = :nombre_usuario, apellido_usuario = :apellido_usuario, dni_usuario = :dni_usuario, direccion_usuario = :direccion_usuario, telefono_usuario = :telefono_usuario WHERE id_usuario = :id_usuario";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
$stmt->bindParam(':apellido_usuario', $apellido_usuario, PDO::PARAM_STR);
$stmt->bindParam(':dni_usuario', $dni_usuario, PDO::PARAM_STR);
$stmt->bindParam(':direccion_usuario', $direccion_usuario, PDO::PARAM_STR);
$stmt->bindParam(':telefono_usuario', $telefono_usuario, PDO::PARAM_STR);

if ($stmt->execute()) {
    echo "Registro actualizado exitosamente";
} else {
    echo "Error actualizando el registro";
}
