<?php
// Inicio de la sesión
session_start();

// Verificar que el usuario sea jugador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'jugador') {
    header("Location: index.php"); // Redirigir si no es admin
    exit();
}

// Incluir la conexión a la base de datos
include 'conexionBD.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Obtener el id del jugador y su correo electrónico desde la sesión
$id_jugador = $_SESSION['id_jugador'];
$emailRegistro = $_SESSION['emailRegistro']; // Asegúrate de que el correo esté en la sesión

// Preparar la consulta para obtener el historial de jugadas
$sqlJugadas = "SELECT * FROM jugada WHERE id_jugador = :id_jugador ORDER BY hora DESC";
$stmtJugadas = $pdo->prepare($sqlJugadas);
$stmtJugadas->bindParam(':id_jugador', $id_jugador);
$stmtJugadas->execute();
$historialJugadas = $stmtJugadas->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; ?>

<div class="estadisticas-container">
    <h2>Estadísticas de Apuestas</h2>
    <p class="saldo-actual">Saldo actual: <?php echo htmlspecialchars($_SESSION['saldo']); ?></p>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Apuesta</th>
                <th>Lanzamiento</th>
                <th>Saldo Inicial</th>
                <th>Saldo Final</th>
                <th>Resultado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($historialJugadas) > 0) {
                foreach ($historialJugadas as $jugada) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($jugada['hora']) . "</td>";
                    echo "<td>" . htmlspecialchars($jugada['apuesta']) . "</td>";
                    echo "<td>" . htmlspecialchars($jugada['lanzamiento']) . "</td>";
                    echo "<td>" . htmlspecialchars($jugada['saldo_inicial']) . "</td>";
                    echo "<td>" . htmlspecialchars($jugada['saldo_final']) . "</td>";

                    // Determinar el resultado y el color de la celda
                    $resultado = ($jugada['apuesta'] > 0) ? ($jugada['saldo_final'] > $jugada['saldo_inicial'] ? 'Ganó' : 'Perdió') : 'Recarga';
                    $color = ($resultado === 'Ganó') ? '#32da32' : (($resultado === 'Perdió') ? '#ee1414' : 'black');

                    echo "<td style='color: $color;'>" . htmlspecialchars($resultado) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No hay jugadas registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Formulario para enviar estadísticas por correo al jugador -->
    <!-- <h2>Enviar Estadísticas por Correo</h2> -->
    <br>
    <form action="enviarPdf/controlador.php" method="POST">
        <input type="hidden" name="id_jugador" value="<?php echo $id_jugador; ?>">
        <input type="hidden" name="emailRegistro" value="<?php echo $emailRegistro; ?>">
        <button type="submit">Enviar PDF al Email</button>
    </form>
    <br>
</div>

<?php include 'footer.php'; ?>