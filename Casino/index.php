<?php
session_start();
include 'funciones.php';

// Determina el modo actual (diurno o nocturno) desde la cookie
$modo = isset($_COOKIE['modo']) ? $_COOKIE['modo'] : 'diurno';
$error = "";

// Maneja el formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    // Verifica las credenciales del usuario
    $resultado = verificarCredenciales($usuario, $contrasena, $jugador);

    // Maneja los resultados de la verificación
    if ($resultado === 'correcto') {
        // Registra la nueva hora de inicio de sesión
        $jugador['hora_inicio'] = date("Y-m-d H:i:s");

        // Guarda la información del jugador en la sesión
        $_SESSION['jugador'] = $jugador;

        // Actualiza el jugador en el archivo JSON
        actualizarJugador($jugador);

        // Redirige al usuario al juego
        header("Location: jugar.php");
        exit();
    } elseif ($resultado === 'contrasena_incorrecta') {
        $error = "Contraseña incorrecta.";
    } elseif ($resultado === 'no_existe') {
        $error = "El usuario no existe. <a href='registro.php'>Regístrate aquí</a>.";
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino Online - Inicio de Sesión</title>
    <link rel="stylesheet" href="estilos.css"> <!-- Carga el diseño base -->
    <link id="modoEstilo" rel="stylesheet" href="<?php echo $modo; ?>.css"> <!-- Carga el estilo diurno o nocturno -->
</head>

<body>

    <button id="botonNoche" type="button">Cambiar a modo nocturno </button>

    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <label>Usuario:</label>
            <input type="text" name="usuario" required>
            <label>Contraseña:</label>
            <input type="password" name="contrasena" required>
            <button type="submit">Entrar</button>
        </form>
        <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
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