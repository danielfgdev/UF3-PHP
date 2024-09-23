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

// Obtener el apodo del usuario desde la sesión
$usuario = $_SESSION['usuario'];

// Recuperar el saldo del usuario desde la base de datos
$stmt = $pdo->prepare("SELECT saldo FROM jugador WHERE apodo = :apodo");
$stmt->bindParam(':apodo', $usuario);
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

// Inicializar un array para almacenar el historial de apuestas si no está ya creado
if (!isset($_SESSION['historial_apuestas'])) {
    $_SESSION['historial_apuestas'] = [];
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
        $stmt = $pdo->prepare("UPDATE jugador SET saldo = :saldo WHERE apodo = :apodo");
        $stmt->bindParam(':saldo', $_SESSION['saldo']);
        $stmt->bindParam(':apodo', $usuario);
        $stmt->execute();

        // Mensaje de éxito
        $mensaje = "<p style='color: #32da32;'>Has recargado $recarga. Tu nuevo saldo es " . $_SESSION['saldo'] . ".</p>";

        // Guardar los datos de la recarga en el historial
        $_SESSION['historial_apuestas'][] = [
            'fecha' => date('Y-m-d H:i:s'),
            'recarga' => $recarga,
            'apuesta' => 0,
            'resultado' => 'Recarga',
            'ganancia' => 0,
            'perdida' => 0,
            'saldo' => $_SESSION['saldo']
        ];
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

        // Determina el resultado y actualiza el saldo
        if ($suma == 7 || $suma == 11) {
            $_SESSION['saldo'] += $apuesta;
            $resultado = 'Ganaste';
        } else {
            $_SESSION['saldo'] -= $apuesta;
            $resultado = 'Perdiste';
        }

        // Actualizar el saldo en la base de datos
        $stmt = $pdo->prepare("UPDATE jugador SET saldo = :saldo WHERE apodo = :apodo");
        $stmt->bindParam(':saldo', $_SESSION['saldo']);
        $stmt->bindParam(':apodo', $usuario);
        $stmt->execute();

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