<?php
// Incluir el autoloader de Composer
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $email = htmlspecialchars(trim($_POST['email']));
    $asunto = htmlspecialchars(trim($_POST['asunto']));
    $mensaje = htmlspecialchars(trim($_POST['mensaje']));

    // Validar los campos del formulario
    if (!empty($nombre) && !empty($email) && !empty($asunto) && !empty($mensaje) && filter_var($email, FILTER_VALIDATE_EMAIL)) {

        // Crear una instancia de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
            $mail->SMTPAuth = true; // Activar autenticación SMTP
            $mail->Username = 'tu_email@gmail.com'; // Tu dirección de correo
            $mail->Password = 'tu_contraseña_o_contraseña_de_aplicación'; // Tu contraseña o contraseña de aplicación
            $mail->SMTPSecure = 'tls'; // Habilitar encriptación TLS
            $mail->Port = 587; // Puerto TCP a utilizar

            // Destinatarios
            $mail->setFrom('tu_email@gmail.com', 'Tu Nombre'); // Remitente
            $mail->addAddress($email, $nombre); // Destinatario

            // Contenido del correo
            $mail->isHTML(true); // Establecer el formato de correo como HTML
            $mail->Subject = $asunto; // Asunto
            $mail->Body    = $mensaje; // Mensaje
            $mail->AltBody = strip_tags($mensaje); // Mensaje alternativo en texto plano

            // Enviar el correo
            $mail->send();
            echo 'El mensaje se ha enviado correctamente.';
        } catch (Exception $e) {
            echo "Hubo un error al enviar el mensaje: {$mail->ErrorInfo}";
        }
    } else {
        echo "Por favor, completa todos los campos correctamente.";
    }
}
