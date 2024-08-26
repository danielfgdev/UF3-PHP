<?php
session_start();
include 'funciones.php';

if (!isset($_SESSION['jugador'])) {
    header("Location: index.php");
    exit();
}

$jugador = $_SESSION['jugador'];
$modo = isset($_COOKIE['modo']) ? $_COOKIE['modo'] : 'diurno';
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
    <button id="botonNoche" type="button">Dia/Noche</button>

    <div class="report-container">
        <h1>Informe de Uso</h1>
        <p>Usuario: <?php echo htmlspecialchars($jugador['usuario']); ?></p>
        <p>Saldo actual: €<?php echo htmlspecialchars($jugador['saldo']); ?></p>
        <h2>Historial de Jugadas</h2>
        <?php if (empty($jugador['jugadas'])): ?>
            <p>No has realizado ninguna jugada aún.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($jugador['jugadas'] as $jugada): ?>
                    <li>
                        <?php echo htmlspecialchars($jugada['fecha']); ?> - Apuesta:
                        €<?php echo htmlspecialchars($jugada['apuesta']); ?> - Resultado:
                        <?php echo htmlspecialchars($jugada['resultado']); ?> - Ganancia:
                        €<?php echo htmlspecialchars($jugada['ganancia']); ?> - Saldo:
                        €<?php echo htmlspecialchars($jugada['saldo']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <nav>
            <a href="jugar.php">Volver al juego</a> |
            <a href="salir.php">Salir</a>
        </nav>
    </div>

    <script>
        function getCookie(name) {
            let cookies = document.cookie.split(';');
            for (let cookie of cookies) {
                let [key, value] = cookie.trim().split('=');
                if (key === name) return value;
            }
            return null;
        }

        document.getElementById('botonNoche').addEventListener('click', function () {
            let currentMode = getCookie('modo');
            let newMode = currentMode === 'diurno' ? 'nocturno' : 'diurno';
            document.cookie = `modo=${newMode};path=/`;
            document.getElementById('modoEstilo').setAttribute('href', newMode + '.css');
            document.getElementById('botonNoche').innerText = `Cambiar a modo ${newMode === 'diurno' ? 'nocturno' : 'diurno'}`;
        });

        window.onload = function () {
            let savedMode = getCookie('modo');
            if (savedMode) {
                document.getElementById('modoEstilo').setAttribute('href', savedMode + '.css');
                document.getElementById('botonNoche').innerText = `Cambiar a modo ${savedMode === 'diurno' ? 'nocturno' : 'diurno'}`;
            }
        };
    </script>
</body>

</html>