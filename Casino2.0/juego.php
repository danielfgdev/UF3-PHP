<?php

// Inicio de la sesion
session_start();

// Se inicializa el saldo si no esta ya, y se pone a cero.
if (!isset($_SESSION['saldo'])) {
    $_SESSION['saldo'] = 0;
}

// Inicializar un array para almacenar el historial de apuestas si no esta ya creado
if (!isset($_SESSION['historial_apuestas'])) {
    $_SESSION['historial_apuestas'] = [];
}

// Inicializar el contador de apuestas si no esta ya creado
if (!isset($_SESSION['contador_apuestas'])) {
    $_SESSION['contador_apuestas'] = 0;
}

// Inicializa una variable para los mensajes
$mensaje = '';

// Verifica si la solicitud es POST para recargar saldo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recargar'])) {

    // Coge la recarga del formulario
    $recarga = $_POST['recargar'];

    // Verificar que la recarga sea valida
    if ($recarga >= 20 && $recarga <= 100) {

        // Suma la recarga al saldo y guarda el mensaje
        $_SESSION['saldo'] += $recarga;
        $mensaje = "<p>Has recargado $recarga. Tu nuevo saldo es " . $_SESSION['saldo'] . ".</p>";
    } else {

        // Si la recarga no esta en el rango guarda el mensaje
        $mensaje = "<p>La recarga debe ser entre 20 y 100.</p>";
    }
}

// Verifica si la solicitud es POST para apostar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apuesta'])) {

    // Obtiene la cantidad a apostar
    $apuesta = $_POST['apuesta'];

    // Si la apuesta es mas grande que el saldo muestra un mensaje
    if ($apuesta > $_SESSION['saldo']) {
        $mensaje = "<p>No tienes suficiente saldo para apostar esa cantidad.</p>";
    } else {

        // Lanzamiento y suma de los dados
        $dado1 = rand(1, 6);
        $dado2 = rand(1, 6);
        $suma = $dado1 + $dado2;

        // Determina el resultado y actualiza el saldo
        if ($suma == 7 || $suma == 11) {
            $_SESSION['saldo'] += $apuesta;
            $resultado = 'Ganaste';
        } else {
            $_SESSION['saldo'] -= $apuesta;
            $resultado = 'Perdiste';
        }

        // Guardar los datos de la apuesta en el historial
        $_SESSION['historial_apuestas'][] = [
            'fecha' => date('Y-m-d H:i:s'),
            'apuesta' => $apuesta,
            'resultado' => $resultado,
            'ganancia' => ($resultado === 'Ganaste') ? $apuesta : 0,
            'perdida' => ($resultado === 'Perdiste') ? $apuesta : 0,
            'saldo' => $_SESSION['saldo']
        ];

        // Incrementar el contador de apuestas
        $_SESSION['contador_apuestas']++;

        // Mostrar alerta cada 3 apuestas
        if ($_SESSION['contador_apuestas'] % 3 == 0) {
            echo "<script>alert('Recuerda que si no hay diversión no hay juego');</script>";
        }

        // Mensaje de nuevo saldo
        $mensaje = "<p>{$resultado}! La suma fue $suma. Tu nuevo saldo es " . $_SESSION['saldo'] . ".</p>";
    }
}
?>

<?php include 'header.php'; ?>

<div class="juego-container">
    <h2>¡Bienvenido al juego de los dados!</h2>

    <div class="instrucciones">
        <h2>Instrucciones del Juego</h2>
        <p class="instrucciones-descripcion">En este juego, lanzas dos dados. Si la suma de los números en los dados es 7 o 11, ¡ganas! De lo contrario, pierdes la cantidad que apostaste.</p>
    </div>

    <p class="dinero-disponible">Saldo disponible: <?php echo $_SESSION['saldo']; ?></p>


    <div class="mensaje-area">
        <p><?php echo $mensaje; ?></p>
    </div>

    <form method="POST" class="apuesta-form">
        <label>Apuesta: <input type="number" name="apuesta" min="1" required></label><br>
        <button type="submit">Lanzar Dados</button>
    </form>

    <form method="POST" class="recarga-form">
        <label>Recargar saldo: <input type="number" name="recargar" min="20" max="100" required></label><br>
        <button type="submit">Recargar</button>
    </form>

    <nav>
        <a href="estadisticas.php">Estadísticas de uso</a> |
        <a href="salir.php">Salir</a>
    </nav>
</div>

<?php include 'footer.php'; ?>