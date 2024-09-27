<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST['nombre']);
    $mensaje = htmlspecialchars($_POST['mensaje']);

    $to = "emailpruebadaw@gmail.com";  // Cambia esto por tu dirección de correo electrónico
    $subject = "Nuevo mensaje de contacto";
    $body = "Nombre: $nombre\nMensaje: $mensaje";
    $headers = "From: $nombre <$to>";

    if (mail($to, $subject, $body, $headers)) {
        echo "Mensaje enviado con éxito.";
    } else {
        echo "Error al enviar el mensaje.";
    }
} else {
    echo "Método no permitido.";
}
