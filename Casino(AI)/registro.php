<?php
session_start();
include 'funciones.php';

$error = "";
$mensaje = "";

// Verifica si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $edad = (int) ($_POST['edad'] ?? 0);
    $saldoInicial = 0;

    // Llama a la función para registrar al jugador
    if (registrarJugador($usuario, $contrasena, $saldoInicial, $edad)) {
        $mensaje = "Registro exitoso. Ahora puedes <a href='index.php'>iniciar sesión</a>.";
    } else {
        $error = $edad < 18 ? "Debes tener al menos 18 años para registrarte." : "El nombre de usuario ya existe. Por favor, elige otro.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino Online - Registro</title>
    <link rel="stylesheet" href="estilos.css"> <!-- Carga el diseño base -->
    <link id="modoEstilo" rel="stylesheet" href="<?php echo isset($_COOKIE['modo']) ? $_COOKIE['modo'] . '.css' : 'diurno.css'; ?>"> <!-- Carga el estilo diurno o nocturno -->
</head>

<body>
    <button id="botonNoche" type="button">Día/Noche</button>

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
                <label>Edad: <input type="number" name="edad" min="0" required></label><br>
                <button type="submit">Registrarse</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        // Obtiene el valor de una cookie por su nombre
        function getCookie(name) {
            let cookies = document.cookie.split(';');
            for (let cookie of cookies) {
                let [key, value] = cookie.trim().split('=');
                if (key === name) return value;
            }
            return null;
        }

        // Cambia el modo de visualización entre diurno y nocturno
        document.getElementById('botonNoche').addEventListener('click', function() {
            let currentMode = getCookie('modo');
            let newMode = currentMode === 'diurno' ? 'nocturno' : 'diurno';
            document.cookie = `modo=${newMode};path=/`;
            document.getElementById('modoEstilo').setAttribute('href', newMode + '.css');
            document.getElementById('botonNoche').innerText = `Cambiar a modo ${newMode === 'diurno' ? 'nocturno' : 'diurno'}`;
        });

        // Al cargar la página, aplica el modo guardado en las cookies
        window.onload = function() {
            let savedMode = getCookie('modo');
            if (savedMode) {
                document.getElementById('modoEstilo').setAttribute('href', savedMode + '.css');
                document.getElementById('botonNoche').innerText = `Cambiar a modo ${savedMode === 'diurno' ? 'nocturno' : 'diurno'}`;
            }
        };
    </script>
</body>

</html>