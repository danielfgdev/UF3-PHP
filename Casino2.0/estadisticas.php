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

// Preparar la consulta para obtener los datos del jugador desde la base de datos
$apodo = $_SESSION['usuario'];  // El apodo está guardado en la sesión
$sql = "SELECT * FROM jugador WHERE apodo = :apodo";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':apodo', $apodo);

// Ejecutar la consulta
if ($stmt->execute()) {
    // Obtener los datos del jugador
    $jugadorDatos = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($jugadorDatos) {
        // Asignar los datos a variables
        $nombre = htmlspecialchars($jugadorDatos['nombre']);
        $primerApellido = htmlspecialchars(explode(' ', $jugadorDatos['apellidos'])[0]);
        $segundoApellido = htmlspecialchars(explode(' ', $jugadorDatos['apellidos'])[1] ?? '');
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
        <p><b>Usuario:</b> <?php echo $apodo; ?></p>
        <p><b>Nombre:</b> <?php echo $nombre; ?></p>
        <p><b>Primer Apellido:</b> <?php echo $primerApellido; ?></p>
        <p><b>Segundo Apellido:</b> <?php echo $segundoApellido; ?></p>
        <p><b>Edad:</b> <?php echo $edad; ?></p>
        <p><b>DNI:</b> <?php echo $dni; ?></p>
        <p><b>Sexo:</b> <?php echo $sexo; ?></p>
    </section>

    <section class="estadisticas">
        <h2>Estadísticas de Apuestas</h2>
        <p class="saldo-actual">Saldo actual: <?php echo $saldo; ?></p>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Recarga</th>
                    <th>Apuesta</th>
                    <th>Resultado</th>
                    <th>Ganancia</th>
                    <th>Pérdida</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Aquí podrías incluir la lógica para mostrar el historial de apuestas,
                // si tienes los datos almacenados en la base de datos.
                if (isset($_SESSION['historial_apuestas']) && count($_SESSION['historial_apuestas']) > 0) {
                    foreach ($_SESSION['historial_apuestas'] as $apuesta) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($apuesta['fecha']) . "</td>";
                        echo "<td>" . htmlspecialchars($apuesta['recarga'] ?? 0) . "</td>";
                        echo "<td>" . htmlspecialchars($apuesta['apuesta']) . "</td>";
                        echo "<td>" . htmlspecialchars($apuesta['resultado']) . "</td>";
                        echo "<td>" . htmlspecialchars($apuesta['ganancia']) . "</td>";
                        echo "<td>" . htmlspecialchars($apuesta['perdida']) . "</td>";
                        echo "<td>" . htmlspecialchars($apuesta['saldo']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay apuestas registradas.</td></tr>";
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