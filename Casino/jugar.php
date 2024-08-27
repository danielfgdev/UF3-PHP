<?php
session_start();
include 'funciones.php';

if (!isset($_SESSION['jugador'])) {
    header("Location: index.php");
    exit();
}

$jugador = $_SESSION['jugador'];
$modo = isset($_COOKIE['modo']) ? $_COOKIE['modo'] : 'diurno';
$mensajeResultado = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['apuesta'])) {
        $apuesta = (int) $_POST['apuesta'];

        if ($apuesta > $jugador['saldo']) {
            $mensajeResultado = "No tienes suficiente saldo para esta apuesta.";
        } else {
            $dados = [rand(1, 6), rand(1, 6)];
            $resultado = array_sum($dados);
            $ganancia = 0;

            if (in_array($resultado, [7, 11])) {
                $ganancia = $apuesta;
                $jugador['saldo'] += $ganancia;
                $mensajeResultado = "¡Ganaste €$ganancia! Los dados mostraron: " . implode(' y ', $dados) . ". La suma es $resultado.";
            } else {
                $jugador['saldo'] -= $apuesta;
                $mensajeResultado = "Perdiste €$apuesta. Los dados mostraron: " . implode(' y ', $dados) . ". La suma es $resultado.";
            }

            $jugada = [
                'fecha' => date("Y-m-d H:i:s"),
                'apuesta' => $apuesta,
                'resultado' => $resultado,
                'ganancia' => $ganancia,
                'saldo' => $jugador['saldo']
            ];
            $jugador['jugadas'][] = $jugada;

            $_SESSION['jugador'] = $jugador;

            if (count($jugador['jugadas']) % 3 == 0) {
                echo "<script>alert('Recuerda que si no hay diversión no hay juego');</script>";
            }
        }
    } elseif (isset($_POST['recargar'])) {
        $recarga = (int) $_POST['recargar'];
        $jugador['saldo'] += $recarga;
        $mensajeResultado = "Has recargado €$recarga. Tu nuevo saldo es €" . $jugador['saldo'] . ".";
        $_SESSION['jugador'] = $jugador;
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
    <button id="botonNoche" type="button">Dia/Noche</button>

    <div class="game-container">
        <h1>¡Bienvenido, <?php echo htmlspecialchars($jugador['usuario']); ?>!</h1>

        <div class="instructions">
            <h2>Instrucciones del Juego</h2>
            <p class="instructions-text">En este juego, lanzas dos dados. Si la suma de los números en los dados es 7 o 11, ¡ganas! De lo contrario, pierdes la cantidad que apostaste.</p>
        </div>

        <p class="saldo-disponible">Saldo disponible: €<?php echo $jugador['saldo']; ?></p>


        <form method="POST">
            <label>Apuesta: <input type="number" name="apuesta" min="1" required></label><br>
            <button type="submit">Lanzar Dados</button>
        </form>

        <form method="POST">
            <label>Recargar saldo: <input type="number" name="recargar" min="1" required></label><br>
            <button type="submit">Recargar</button>
        </form>

        <?php if ($mensajeResultado): ?>
            <p><?php echo htmlspecialchars($mensajeResultado); ?></p>
        <?php endif; ?>

        <nav>
            <a href="informe.php">Informe de Uso</a> |
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

        document.getElementById('botonNoche').addEventListener('click', function() {
            let currentMode = getCookie('modo');
            let newMode = currentMode === 'diurno' ? 'nocturno' : 'diurno';
            document.cookie = `modo=${newMode};path=/`;
            document.getElementById('modoEstilo').setAttribute('href', newMode + '.css');
            document.getElementById('botonNoche').innerText = `Cambiar a modo ${newMode === 'diurno' ? 'nocturno' : 'diurno'}`;
        });

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