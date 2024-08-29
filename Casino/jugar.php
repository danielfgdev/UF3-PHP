<?php
session_start();
include 'funciones.php';

// Verifica si el usuario está autenticado
if (!isset($_SESSION['jugador'])) {
    header("Location: index.php"); // Redirige al inicio de sesión si no está autenticado
    exit();
}

$jugador = $_SESSION['jugador'];
$modo = isset($_COOKIE['modo']) ? $_COOKIE['modo'] : 'diurno';
$mensajeResultado = "";

// Maneja el formulario de apuestas y recarga de saldo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['apuesta'])) {
        $apuesta = (int) $_POST['apuesta'];

        // Verifica si el saldo es suficiente para la apuesta
        if ($apuesta > $jugador['saldo']) {
            $mensajeResultado = "No tienes suficiente saldo para esta apuesta.";
        } else {
            // Lanza los dados y calcula el resultado
            $dados = [rand(1, 6), rand(1, 6)];
            $resultado = array_sum($dados);
            $ganancia = 0;
            $perdida = 0;

            // Verifica si la apuesta es ganadora
            if (in_array($resultado, [7, 11])) {
                // Ganar significa recibir el doble de la cantidad apostada
                $ganancia = $apuesta; // La ganancia es igual a la cantidad apostada
                $jugador['saldo'] += $ganancia; // Añade la cantidad apostada más la ganancia
                $mensajeResultado = "¡Ganaste €" . ($ganancia) . "! Los dados mostraron: " . implode(' y ', $dados) . ". La suma es $resultado.";
            } else {
                // Perder significa perder la cantidad apostada
                $perdida = $apuesta; // La pérdida es el doble de la cantidad apostada
                $jugador['saldo'] -= $apuesta; // Resta la cantidad apostada del saldo del jugador
                $mensajeResultado = "Perdiste €$apuesta. Los dados mostraron: " . implode(' y ', $dados) . ". La suma es $resultado.";
            }

            // Guarda el historial de la jugada
            $jugada = [
                'fecha' => date("Y-m-d H:i:s"),
                'apuesta' => $apuesta,
                'resultado' => $resultado,
                'ganancia' => $ganancia,
                'perdida' => $perdida,
                'saldo' => $jugador['saldo']
            ];
            $jugador['jugadas'][] = $jugada;


            // Actualiza la sesión del jugador
            $_SESSION['jugador'] = $jugador;

            // Actualiza el jugador en el archivo JSON
            actualizarJugador($jugador);

            // Muestra un mensaje de recordatorio cada 3 jugadas
            if (count($jugador['jugadas']) % 3 == 0) {
                echo "<script>alert('Recuerda que si no hay diversión no hay juego');</script>";
            }
        }
    } elseif (isset($_POST['recargar'])) {
        $recarga = (int) $_POST['recargar'];

        // Verifica si el monto de la recarga está en el rango permitido
        if ($recarga < 20 || $recarga > 100) {
            $mensajeResultado = "La recarga debe estar entre €20 y €100.";
        } else {
            // No modificar la hora de inicio, solo actualizar el saldo
            $jugador = $_SESSION['jugador'];

            // Actualiza el saldo
            $jugador['saldo'] += $recarga;
            $mensajeResultado = "Has recargado €$recarga. Tu nuevo saldo es €" . $jugador['saldo'] . ".";

            // Guarda la información actualizada del jugador en la sesión
            $_SESSION['jugador'] = $jugador;

            // Actualiza el jugador en el archivo JSON
            actualizarJugador($jugador);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino Online - Jugar</title>
    <link rel="stylesheet" href="estilos.css"> <!-- Carga el diseño base -->
    <link id="modoEstilo" rel="stylesheet" href="<?php echo $modo; ?>.css"> <!-- Carga el estilo diurno o nocturno -->
</head>

<body>
    <button id="botonNoche" type="button">Día/Noche</button>

    <div class="game-container">
        <h1>¡Bienvenido, <?php echo htmlspecialchars($jugador['usuario']); ?>!</h1>

        <div class="instructions">
            <h2>Instrucciones del Juego</h2>
            <p class="instructions-text">En este juego, lanzas dos dados. Si la suma de los números en los dados es 7 o 11, ¡ganas! De lo contrario, pierdes la cantidad que apostaste.</p>
        </div>

        <p class="saldo-disponible">Saldo disponible: €<?php echo $jugador['saldo']; ?></p>
        <p id="temporizador">Tiempo de sesión: 00:00:00</p>

        <!-- Formulario para hacer una apuesta -->
        <form method="POST">
            <label>Apuesta: <input type="number" name="apuesta" min="1" required></label><br>
            <button type="submit">Lanzar Dados</button>
        </form>

        <!-- Formulario para recargar el saldo -->
        <form method="POST">
            <label>Recargar saldo: <input type="number" name="recargar" min="20" max="100" required></label><br>
            <button type="submit">Recargar</button>
        </form>

        <!-- Muestra el resultado de la última jugada o recarga -->
        <?php if ($mensajeResultado): ?>
            <p><?php echo htmlspecialchars($mensajeResultado); ?></p>
        <?php endif; ?>

        <nav>
            <a href="informe.php">Informe de Uso</a> |
            <a href="salir.php">Salir</a>
        </nav>
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

        // Al cargar la página, aplica el modo guardado en las cookies y gestiona el temporizador
        window.onload = function() {
            let savedMode = getCookie('modo');
            if (savedMode) {
                document.getElementById('modoEstilo').setAttribute('href', savedMode + '.css');
                document.getElementById('botonNoche').innerText = `Cambiar a modo ${savedMode === 'diurno' ? 'nocturno' : 'diurno'}`;
            }

            // Recupera el tiempo de inicio de la sesión de localStorage
            let tiempoInicio = localStorage.getItem('tiempoInicio');

            // Si no hay tiempo de inicio guardado o si la sesión es nueva, lo establecemos
            if (!tiempoInicio || sessionStorage.getItem('sessionStart') !== 'true') {
                tiempoInicio = new Date().toISOString();
                localStorage.setItem('tiempoInicio', tiempoInicio);
                sessionStorage.setItem('sessionStart', 'true');
            }

            // Calcula el tiempo transcurrido inmediatamente al cargar la página
            let tiempoInicioFecha = new Date(tiempoInicio);
            let tiempoActual = new Date();
            let diferencia = new Date(tiempoActual - tiempoInicioFecha);
            let horas = String(diferencia.getUTCHours()).padStart(2, '0');
            let minutos = String(diferencia.getUTCMinutes()).padStart(2, '0');
            let segundos = String(diferencia.getUTCSeconds()).padStart(2, '0');
            document.getElementById('temporizador').textContent = `Tiempo de sesión: ${horas}:${minutos}:${segundos}`;

            // Establece un intervalo para actualizar el temporizador cada segundo
            setInterval(function() {
                tiempoActual = new Date();
                diferencia = new Date(tiempoActual - tiempoInicioFecha);
                horas = String(diferencia.getUTCHours()).padStart(2, '0');
                minutos = String(diferencia.getUTCMinutes()).padStart(2, '0');
                segundos = String(diferencia.getUTCSeconds()).padStart(2, '0');
                document.getElementById('temporizador').textContent = `Tiempo de sesión: ${horas}:${minutos}:${segundos}`;
            }, 1000);
        };
    </script>
</body>

</html>