<?php
session_start();
include 'funciones.php';

$error = "";
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $saldoInicial = 0;

    if (registrarJugador($usuario, $contrasena, $saldoInicial)) {
        $mensaje = "Registro exitoso. Ahora puedes <a href='index.php'>iniciar sesión</a>.";
    } else {
        $error = "El nombre de usuario ya existe. Por favor, elige otro.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino Online - Registro</title>
    <link rel="stylesheet" href="estilos.css">
    <link id="diaNoche" rel="stylesheet" href="diurno.css">
</head>

<body>
    <button id="botonNoche" type="submit">Día/Noche</button>

    <div class="register-container">
        <h1>Registro</h1>
        <?php if ($error): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($mensaje): ?>
            <p><?php echo $mensaje; ?></p>
        <?php else: ?>
            <form method="POST">
                <label>Usuario: <input type="text" name="usuario" required></label><br>
                <label>Contraseña: <input type="password" name="contrasena" required></label><br>
                <button type="submit">Registrarse</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>