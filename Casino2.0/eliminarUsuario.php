<?php
// Inicio de la sesion
session_start();

// Incluir la conexion a la base de datos
include 'conexionBD.php';

// Incluir la función para enviar el email
include 'enviarPdf/enviarEmail.php';

// Verificar que el usuario este logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Obtener el id del jugador desde la sesion
$id_jugador = $_POST['id_jugador'];

// Primero, obtenemos el correo del jugador antes de eliminar su cuenta
$sqlEmail = "SELECT emailRegistro FROM jugador WHERE id_jugador = :id_jugador";
$stmtEmail = $pdo->prepare($sqlEmail);
$stmtEmail->bindParam(':id_jugador', $id_jugador);
$stmtEmail->execute();
$emailRegistro = $stmtEmail->fetchColumn();

if ($emailRegistro) {
    // Llamar a la función para generar y enviar el PDF antes de eliminar la cuenta
    $pdfEnviado = generarYEnviarPDF($id_jugador, $emailRegistro, $pdo);

    // Eliminar la cuenta del jugador
    $sql = "DELETE FROM jugador WHERE id_jugador = :id_jugador";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_jugador', $id_jugador);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Verificar si el usuario es un administrador
        if ($_SESSION['rol'] === 'admin') { // Asegúrate de que esta variable de sesión existe y es correcta
            // Mostrar mensaje de éxito antes de redirigir
            if ($pdfEnviado) {
                echo "<script>
                        alert('El PDF se ha enviado correctamente al jugador ID: $id_jugador y la cuenta ha sido eliminada exitosamente.');
                        window.location.href = 'adminDashboard.php'; // Cambiar a la página del dashboard
                      </script>";
            } else {
                echo "<script>
                        alert('El PDF no se pudo enviar al jugador ID: $id_jugador, pero la cuenta ha sido eliminada exitosamente.');
                        window.location.href = 'adminDashboard.php'; // Cambiar a la página del dashboard
                      </script>";
            }
        } else {
            // Si es un usuario regular, mostrar mensaje de éxito en JavaScript y redirigir
            if ($pdfEnviado) {
                echo "<script>
                        alert('El PDF se ha enviado correctamente y tu cuenta ha sido eliminada.');
                        window.location.href = 'index.php'; // Redirigir a index después de eliminar la cuenta
                      </script>";
            } else {
                echo "<script>
                        alert('El PDF no se pudo enviar, pero tu cuenta ha sido eliminada.');
                        window.location.href = 'index.php'; // Redirigir a index después de eliminar la cuenta
                      </script>";
            }
        }
    } else {
        // Manejar el error si la eliminación falla
        echo "<p>Error al eliminar la cuenta.</p>";
    }
} else {
    // Si no se encuentra el correo del jugador, manejar el error
    echo "<script>
            alert('Error: No se encontró el correo del jugador.');
            window.location.href = 'index.php'; // Redirigir a index o a donde desees
          </script>";
}
