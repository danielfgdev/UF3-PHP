<?php
// Incluir la conexión a la base de datos
include '../conexionBD.php'; // Asegúrate de que la ruta sea correcta

// Incluir los archivos para generar el PDF y enviar el correo
include 'generarPDF.php'; // Verifica si el archivo está en la misma carpeta
include 'enviarEmail.php'; // Verifica si el archivo está en la misma carpeta

// Iniciar sesión
session_start();

// Verificar que se hayan enviado los datos necesarios
if (!isset($_POST['id_jugador']) || !isset($_POST['emailRegistro'])) {
  die('Acceso denegado: Datos inválidos'); // Mensaje de acceso denegado si no se envían datos
}

$idJugador = $_POST['id_jugador'];
$destinatarioEmail = $_POST['emailRegistro'];

// Paso 1: Verificar el valor del email en la sesión sin mostrar mensajes
if (!isset($_SESSION['emailRegistro']) || empty($_SESSION['emailRegistro'])) {
  die('Acceso denegado: El email del jugador no está configurado correctamente.');
}


// Verificar si el usuario es admin
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
  // El administrador puede enviar el PDF a cualquier jugador
  try {
    // Llamar a la función para generar el PDF
    $pdfFilePath = generarPDF($idJugador, $pdo); // Genera el PDF utilizando el ID del jugador y el objeto de conexión

    // Llamar a la función para enviar el correo con el PDF adjunto
    enviarEmailConPDF($pdfFilePath, $destinatarioEmail); // Envía el PDF al correo del jugador

    // Si todo es correcto, mostrar un alert y redirigir
    echo "<script>
                alert('El correo con las estadísticas ha sido enviado al jugador con el ID: $idJugador.');
                window.location.href = '../adminDashboard.php'; // Redirige al panel de administrador
              </script>";
  } catch (Exception $e) {
    // Manejo de errores si algo falla
    echo "<script>
                alert('Hubo un error al enviar el correo: " . $e->getMessage() . "');
                window.location.href = '../adminDashboard.php'; // Redirige al panel de administrador incluso en caso de error
              </script>";
  }
} elseif (isset($_SESSION['usuario'])) {
  // Si es un jugador, verifica que el ID del jugador sea el mismo que el que está en la sesión
  if ($_SESSION['id_jugador'] == $idJugador) {
    try {
      // Llamar a la función para generar el PDF
      $pdfFilePath = generarPDF($idJugador, $pdo); // Genera el PDF utilizando el ID del jugador y el objeto de conexión

      // Llamar a la función para enviar el correo con el PDF adjunto
      enviarEmailConPDF($pdfFilePath, $destinatarioEmail); // Envía el PDF al correo del jugador

      // Si todo es correcto, mostrar un alert y redirigir
      echo "<script>
                    alert('El correo con tus estadísticas ha sido enviado al ID de jugador: $idJugador.');
                    window.location.href = '../tablaEstadisticas.php'; // Redirige a la página de estadísticas
                  </script>";
    } catch (Exception $e) {
      // Manejo de errores si algo falla
      echo "<script>
                    alert('Hubo un error al enviar el correo: " . $e->getMessage() . "');
                    window.location.href = '../tablaEstadisticas.php'; // Redirige a la página de estadísticas incluso en caso de error
                  </script>";
    }
  } else {
    die('Acceso denegado: No tienes permiso para enviar el PDF a este jugador.'); // Mensaje si el jugador intenta acceder a otro
  }
} else {
  die('Acceso denegado: Debes estar logueado.'); // Mensaje si el usuario no está logueado
}
