<?php


// Inicio de la sesión

session_start();


// Incluir la conexión a la base de datos

include 'conexionBD.php';


// Verificar si el usuario está logueado

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}


// Obtener el id_jugador desde la sesión

$id_jugador = $_SESSION['id_jugador'];


// Recuperar el saldo del usuario desde la base de datos usando id_jugador

$stmt = $pdo->prepare("SELECT saldo FROM jugador WHERE id_jugador = :id_jugador");
$stmt->bindParam(':id_jugador', $id_jugador);
$stmt->execute();
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);


// Si se encuentra el usuario, establecer el saldo en la sesión

if ($resultado) {
    $_SESSION['saldo'] = $resultado['saldo'];
} else {


    // Si no se encuentra el usuario, redirigir a la página de login

    header("Location: index.php");
    exit();
}


// Inicializar el contador de apuestas si no está ya creado

if (!isset($_SESSION['contador_apuestas'])) {
    $_SESSION['contador_apuestas'] = 0;
}


// Inicializa una variable para los mensajes

$mensaje = '';


// Verifica si la solicitud es POST para recargar saldo

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recargar'])) {


    // Obtener la recarga del formulario

    $recarga = $_POST['recargar'];


    // Verificar que la recarga sea válida

    if ($recarga >= 20 && $recarga <= 100) {


        // Sumar la recarga al saldo y actualizar en la base de datos

        $_SESSION['saldo'] += $recarga;
        $stmt = $pdo->prepare("UPDATE jugador SET saldo = :saldo WHERE id_jugador = :id_jugador");
        $stmt->bindParam(':saldo', $_SESSION['saldo']);
        $stmt->bindParam(':id_jugador', $id_jugador);
        $stmt->execute();


        // Mensaje de éxito

        $mensaje = "<p style='color: #32da32;'>Has recargado $recarga. Tu nuevo saldo es " . $_SESSION['saldo'] . ".</p>";


        // Guardar los datos de la recarga en la tabla jugada

        $stmt = $pdo->prepare("INSERT INTO jugada (id_jugador, apuesta, saldo_inicial, saldo_final) VALUES (:id_jugador, 0, :saldo_inicial, :saldo_final)");
        $saldo_inicial = $_SESSION['saldo'] - $recarga;
        $stmt->bindParam(':id_jugador', $id_jugador);
        $stmt->bindParam(':saldo_inicial', $saldo_inicial);
        $stmt->bindParam(':saldo_final', $_SESSION['saldo']);
        $stmt->execute();
    } else {


        // Si la recarga no es válida, mensaje de error

        $mensaje = "<p>La recarga debe ser entre 20 y 100.</p>";
    }
}


// Verifica si la solicitud es POST para apostar

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apuesta'])) {


    // Obtener la cantidad a apostar

    $apuesta = $_POST['apuesta'];


    // Si la apuesta es mayor que el saldo, muestra un mensaje de error

    if ($apuesta > $_SESSION['saldo']) {
        $mensaje = "<p style='color: #ee1414;'>No tienes suficiente saldo para apostar esa cantidad.</p>";
    } else {

        // Lanzamiento y suma de los dados

        $dado1 = rand(1, 6);
        $dado2 = rand(1, 6);
        $suma = $dado1 + $dado2;


        // Guardar el saldo inicial antes de realizar la apuesta

        $saldo_inicial = $_SESSION['saldo'];


        // Determina el resultado y actualiza el saldo

        if ($suma == 7 || $suma == 11) {
            $_SESSION['saldo'] += $apuesta;
            $resultado = 'Ganaste';
        } else {
            $_SESSION['saldo'] -= $apuesta;
            $resultado = 'Perdiste';
        }


        // Actualizar el saldo en la base de datos usando id_jugador

        $stmt = $pdo->prepare("UPDATE jugador SET saldo = :saldo WHERE id_jugador = :id_jugador");
        $stmt->bindParam(':saldo', $_SESSION['saldo']);
        $stmt->bindParam(':id_jugador', $id_jugador);
        $stmt->execute();


        // Guardar los datos de la apuesta en la tabla jugada, incluyendo el lanzamiento

        $stmt = $pdo->prepare("INSERT INTO jugada (id_jugador, apuesta, saldo_inicial, saldo_final, lanzamiento) VALUES (:id_jugador, :apuesta, :saldo_inicial, :saldo_final, :lanzamiento)");
        $stmt->bindParam(':id_jugador', $id_jugador);
        $stmt->bindParam(':apuesta', $apuesta);
        $stmt->bindParam(':saldo_inicial', $saldo_inicial);
        $saldo_final = $_SESSION['saldo'];
        $stmt->bindParam(':saldo_final', $saldo_final);
        $stmt->bindParam(':lanzamiento', $suma);
        $stmt->execute();


        // Incrementar el contador de apuestas

        $_SESSION['contador_apuestas']++;


        // Mostrar alerta cada 3 apuestas

        if ($_SESSION['contador_apuestas'] % 3 == 0) {
            echo "<script>alert('Recuerda que si no hay diversión no hay juego');</script>";
        }


        // Mensaje de resultado

        if ($resultado === 'Ganaste') {
            $mensaje = "<p style='color: #32da32;'>{$resultado}! La suma de los dados fue $suma. Tu nuevo saldo es " . $_SESSION['saldo'] . ".</p>";
        } else {
            $mensaje = "<p style='color: #ee1414;'>{$resultado}! La suma de los dados fue $suma. Tu nuevo saldo es " . $_SESSION['saldo'] . ".</p>";
        }
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