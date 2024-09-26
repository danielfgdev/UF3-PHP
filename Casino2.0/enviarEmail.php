<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Dirección de correo a la que se enviará
    $to = "xxxxgmail.com";

    // Encabezados del correo
    $headers = "From: $email" . "\r\n" .
        "Reply-To: $email" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    // Enviar correo
    if (mail($to, $subject, $message, $headers)) {
        echo "El correo ha sido enviado correctamente.";
    } else {
        echo "Hubo un error al enviar el correo.";
    }
} else {
    echo "Método de solicitud no válido.";
}
