<!-- No funciona en Xampp, pero basicamente seria esto: -->

<form action="enviar_email.php" method="POST">
    <label for="email">Correo Electrónico:</label><br>
    <input type="email" id="email" name="email" required><br>

    <label for="subject">Asunto:</label><br>
    <input type="text" id="subject" name="subject" required><br>

    <label for="message">Mensaje:</label><br>
    <textarea id="message" name="message" rows="4" required></textarea><br>

    <input type="submit" value="Enviar">
</form>