<?php
// Incluir la conexión a la base de datos
include '../conexionBD.php'; // Asegúrate de que la ruta sea correcta

// Incluir los archivos para generar el PDF y enviar el correo
include 'generarPDF.php'; // Verifica si el archivo está en la misma carpeta
include 'enviarEmail.php'; // Verifica si el archivo está en la misma carpeta

// Iniciar sesión por seguridad (si es necesario para verificar permisos)
session_start();

// Verificar si el usuario tiene rol de admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die('Acceso denegado'); // Muestra un mensaje de acceso denegado si no es admin
}

// Obtener el ID del jugador y su correo electrónico desde el formulario enviado
$idJugador = $_POST['id_jugador']; // Verificar si este es el nombre correcto del campo en el formulario
$destinatarioEmail = $_POST['emailRegistro']; // Verificar si 'email_jugador' coincide con el nombre del campo en el formulario

// Llamar a la función para generar el PDF
// Asegúrate de que la función 'generarPDF' acepte dos parámetros: ID y conexión a la base de datos
$pdfFilePath = generarPDF($idJugador, $pdo); // Aquí se genera el PDF utilizando el ID del jugador y el objeto de conexión

// Llamar a la función para enviar el correo con el PDF adjunto
// Verificar que la función 'enviarEmailConPDF' esté correctamente definida en 'enviarEmail.php'
enviarEmailConPDF($pdfFilePath, $destinatarioEmail); // Envía el PDF al correo del jugador

// Mostrar mensaje de confirmación
echo "El correo con las estadisticas ha sido enviado al jugador con el ID: $idJugador."; // Mensaje de éxito
