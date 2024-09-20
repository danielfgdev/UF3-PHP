<?php
include 'conexion.php';

$id_usuario = $_POST['id_usuario'];

if (empty($id_usuario) || !is_numeric($id_usuario)) {
    echo "ID de usuario no válido.";
    exit;
}

$sql = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo "Usuario eliminado exitosamente";
} else {
    echo "Error eliminando el usuario";
}
