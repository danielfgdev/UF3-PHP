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

// Obtiene la hora de inicio de sesión del jugador
$horaInicio = obtenerHoraInicio($jugador['usuario']);

// Obtiene el tiempo de sesión total
$tiempoSesionTotal = calcularTiempoSesion($horaInicio);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino Online - Informe de Uso</title>
    <link rel="stylesheet" href="estilos.css"> <!-- Carga el diseño base -->
    <link id="modoEstilo" rel="stylesheet" href="<?php echo $modo; ?>.css"> <!-- Carga el estilo diurno o nocturno -->
</head>

<body>
    <button id="botonNoche" type="button">Día/Noche</button>

    <div class="report-container">
        <h1>Informe de Uso</h1>
        <p>Usuario: <?php echo htmlspecialchars($jugador['usuario']); ?></p>
        <p>Saldo actual: €<?php echo htmlspecialchars($jugador['saldo']); ?></p>

        <!-- Mostrar el tiempo total de sesión -->
        <p>Tiempo total de sesión:
            <?php
            // Convertir el tiempo de sesión de segundos a horas, minutos, segundos
            if ($tiempoSesionTotal !== null) {
                $horas = floor($tiempoSesionTotal / 3600);
                $minutos = floor(($tiempoSesionTotal % 3600) / 60);
                $segundos = $tiempoSesionTotal % 60;
                echo sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
            } else {
                echo "N/A";
            }
            ?>
        </p>

        <h2>Historial de Jugadas</h2>
        <?php if (empty($jugador['jugadas'])): ?>
            <p>No has realizado ninguna jugada aún.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Apuesta</th>
                        <th>Resultado</th>
                        <th>Ganancia</th>
                        <th>Perdida</th>
                        <th>Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jugador['jugadas'] as $jugada): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($jugada['fecha']); ?></td>
                            <td>€<?php echo htmlspecialchars($jugada['apuesta']); ?></td>
                            <td><?php echo htmlspecialchars($jugada['resultado']); ?></td>
                            <td>€<?php echo htmlspecialchars($jugada['ganancia']); ?></td>
                            <td>€<?php echo htmlspecialchars($jugada['perdida']); ?></td>
                            <td>€<?php echo htmlspecialchars($jugada['saldo']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <nav>
            <a href="jugar.php">Volver al juego</a> |
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