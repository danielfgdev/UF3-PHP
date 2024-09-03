<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de registro</title>
</head>

<body>


    <div class="registro-container">
        <h1>Registro</h1>

        <?php


        // Si en procesarRegistro.php la edad es incorrecta o la
        //  sesion es NO POST (GET) se muestra alguno de los mensajes de abajo.
        if (isset($_GET['mensaje'])) {
            $mensaje = $_GET['mensaje'];
            if ($mensaje === 'edad_incorrecta') {
                echo "<p>Mínimo 18 años para registrarse</p>";
            } elseif ($mensaje === 'no_enviado') {
                echo "<p>No se ha enviado el formulario</p>";
            }
        }
        ?>

        <form action="procesarRegistro.php" method="post" id="registro">
            <label>Usuario: <input type="text" name="usuario" required></label><br>
            <label>Contraseña: <input type="password" name="contraseña" required></label><br>
            <label>Edad: <input type="number" name="edad" min="0" required></label><br>
            <button type="submit">Registrarse</button>
        </form>

    </div>




</body>

</html>