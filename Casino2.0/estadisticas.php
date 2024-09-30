<?php


// Inicio de la sesión

session_start();


// Incluir la conexión a la base de datos

include 'conexionBD.php';


// Verificar que el usuario esté logueado

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}


// Obtener el id del jugador desde la sesión

$id_jugador = $_SESSION['id_jugador'];


// Preparar la consulta para obtener los datos del jugador desde la base de datos usando id_jugador

$sql = "SELECT * FROM jugador WHERE id_jugador = :id_jugador";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_jugador', $id_jugador);


// Ejecutar la consulta

if ($stmt->execute()) {
    $jugadorDatos = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($jugadorDatos) {
        // Asignar los datos a variables
        $nombre = htmlspecialchars($jugadorDatos['nombre']);
        $primerApellido = htmlspecialchars(explode(' ', $jugadorDatos['apellidos'])[0]);
        $segundoApellido = htmlspecialchars(explode(' ', $jugadorDatos['apellidos'])[1] ?? '');
        $emailRegistro = htmlspecialchars($jugadorDatos['emailRegistro']);
        $edad = htmlspecialchars($jugadorDatos['edad']);
        $dni = htmlspecialchars($jugadorDatos['dni']);
        $sexo = htmlspecialchars($jugadorDatos['sexo']);
        $saldo = htmlspecialchars($jugadorDatos['saldo']);
    } else {
        echo "No se encontraron datos para el jugador.";
        exit();
    }
} else {
    echo "Error al obtener los datos del jugador.";
    exit();
}
?>

<?php include 'header.php'; ?>

<div class="estadisticas-container">
    <section class="datos-jugador">
        <h2>Datos del jugador:</h2>
        <p><b>Usuario:</b> <?php echo $_SESSION['usuario']; ?></p>
        <p><b>Nombre:</b> <?php echo $nombre; ?></p>
        <p><b>Correo Electronico:</b> <?php echo $emailRegistro; ?></p>
        <p><b>Primer Apellido:</b> <?php echo $primerApellido; ?></p>
        <p><b>Segundo Apellido:</b> <?php echo $segundoApellido; ?></p>
        <p><b>Edad:</b> <?php echo $edad; ?></p>
        <p><b>DNI:</b> <?php echo $dni; ?></p>
        <p><b>Sexo:</b> <?php echo $sexo; ?></p>


        <!-- Botón para modificar datos -->

        <form action="modificarDatos.php" method="GET">
            <button type="submit">Modificar</button>
        </form>
    </section>

    <section class="estadisticas">
        <h2>Estadísticas de Apuestas</h2>
        <p class="saldo-actual">Saldo actual: <?php echo $saldo; ?></p>
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


                // Preparar la consulta para obtener el historial de jugadas

                $sqlJugadas = "SELECT * FROM jugada WHERE id_jugador = :id_jugador ORDER BY hora DESC";
                $stmtJugadas = $pdo->prepare($sqlJugadas);
                $stmtJugadas->bindParam(':id_jugador', $id_jugador);
                $stmtJugadas->execute();
                $historialJugadas = $stmtJugadas->fetchAll(PDO::FETCH_ASSOC);

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

                        echo "<td style='color: $color;'>" . htmlspecialchars($resultado) . "</td>"; // Color basado en el resultado
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay jugadas registradas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <nav>
        <a href="juego.php">Volver al juego</a> |
        <a href="salir.php">Salir</a>
    </nav>
</div>

<?php include 'footer.php'; ?>