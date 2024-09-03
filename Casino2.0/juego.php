<?php


// Inicio de la sesion
session_start();


// Se inicial el saldo si ya no lo esta, y se pone a cero.
if (!isset($_SESSION['saldo'])) {
    $_SESSION['saldo'] = 0;
}


// Verifica si la solicitud es POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recargar'])) {


    // Coge la recarga del formulario.
    $recarga = $_POST['recargar'];


    // Verificar que la recarga sea válida.
    if ($recarga >= 20 && $recarga <= 100) {


        // Suma la recarga al saldo y deja el mensaje.
        $_SESSION['saldo'] += $recarga;

        echo "<p>Has recargado $recarga. Tu nuevo saldo es " . $_SESSION['saldo'] . ".</p>";
    } else {


        // Si la recarga no esta en el rango deja el mensaje.
        echo "<p>La recarga debe ser entre 20 y 100.</p>";
    }
}

// Verifica si la solicitud es POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apuesta'])) {


    // Obtiene la cantidad a apostar
    $apuesta = $_POST['apuesta'];


    // Si la apuesta es mas grande que el saldo muestra un mensaje.
    if ($apuesta > $_SESSION['saldo']) {
        echo "<p>No tienes suficiente saldo para apostar esa cantidad.</p>";
    } else {



        // Lanzamiento y suma de los dados.
        $dado1 = rand(1, 6);
        $dado2 = rand(1, 6);
        $suma = $dado1 + $dado2;


        // Suma o resta saldo despendiendo del resultado de la apuesta.
        if ($suma == 7 || $suma == 11) {
            $_SESSION['saldo'] += $apuesta;
            echo "<p>¡Ganaste! La suma fue $suma. Tu nuevo saldo es " . $_SESSION['saldo'] . ".</p>";
        } else {
            $_SESSION['saldo'] -= $apuesta;
            echo "<p>Perdiste. La suma fue $suma. Tu nuevo saldo es " . $_SESSION['saldo'] . ".</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego de los dados</title>
</head>

<body>

    <div class="juego-container">
        <h1>¡Bienvenido al juego de los dados!</h1>

        <div class="instrucciones">
            <h2>Instrucciones del Juego</h2>
            <p class="instrucciones-descripcion">En este juego, lanzas dos dados. Si la suma de los números en los dados es 7 o 11, ¡ganas! De lo contrario, pierdes la cantidad que apostaste.</p>
        </div>

        <p class="dinero-disponible">Saldo disponible: <?php echo $_SESSION['saldo']; ?> </p>
        <p id="temporizador">Tiempo de sesión: 00:00:00</p>

        <form method="POST">
            <label>Apuesta: <input type="number" name="apuesta" min="1" required></label><br>
            <button type="submit">Lanzar Dados</button>
        </form>

        <form method="POST">
            <label>Recargar saldo: <input type="number" name="recargar" min="20" max="100" required></label><br>
            <button type="submit">Recargar</button>
        </form>

        <nav>
            <a href="estadisticas.php">Estadísticas de uso</a> |
            <a href="salir.php">Salir</a>
        </nav>
    </div>

</body>

</html>