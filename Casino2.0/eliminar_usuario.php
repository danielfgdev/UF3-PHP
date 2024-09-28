<?php
// Inicio de la sesion
session_start();

// Incluir la conexion a la base de datos
include 'conexionBD.php';

// Verificar que el usuario este logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Obtener el id del jugador desde la sesion
$id_jugador = $_POST['id_jugador'];

// Eliminar la cuenta del jugador
$sql = "DELETE FROM jugador WHERE id_jugador = :id_jugador";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_jugador', $id_jugador);

// Ejecutar la consulta
if ($stmt->execute()) {
    // Verificar si el usuario es un administrador
    if ($_SESSION['rol'] === 'admin') { // Asegúrate de que esta variable de sesión existe y es correcta
        // Mostrar mensaje de éxito antes de redirigir
        echo "<script>
                alert('La cuenta ha sido eliminada exitosamente.');
                window.location.href = 'admin_dashboard.php'; // Cambiar a la página del dashboard
              </script>";
    } else {
        // Si es un usuario regular, mostrar mensaje de éxito en JavaScript y redirigir
        echo "<script>
                alert('Tu cuenta ha sido eliminada.');
                window.location.href = 'index.php'; // Redirigir a index después de eliminar la cuenta
              </script>";
    }
} else {
    // Manejar el error si la eliminación falla
    echo "<p>Error al eliminar la cuenta.</p>";
}
