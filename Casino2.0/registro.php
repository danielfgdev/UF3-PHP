<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de registro</title>
</head>

<body>



    <div class="register-container">
        <h1>Registro</h1>

        <form method="POST">
            <label>Usuario: <input type="text" name="usuario" required></label><br>
            <label>Contraseña: <input type="password" name="contrasena" required></label><br>
            <label>Edad: <input type="number" name="edad" min="0" required></label><br>
            <button type="submit">Registrarse</button>
        </form>

    </div>





</body>

</html>