<?php

// Inicio de la sesion
session_start();
?>

<?php include 'header.php'; ?>

<div class="estadisticas-container">
    <section class="datos-jugador">

        <!-- Coje los datos del jugador de procesarRegistro.php y los muestra -->
        <h2>Datos del jugador:</h2>
        <?php
        echo "<p><b>Usuario:</b> " . htmlspecialchars($_SESSION['usuario']) . "</p>";
        echo "<p><b>Nombre:</b> " . htmlspecialchars($_SESSION['nombre']) . "</p>";
        echo "<p><b>Primer Apellido:</b> " . htmlspecialchars($_SESSION['primerApellido']) . "</p>";
        echo "<p><b>Segundo Apellido:</b> " . htmlspecialchars($_SESSION['segundoApellido']) . "</p>";
        echo "<p><b>Edad:</b> " . htmlspecialchars($_SESSION['edad']) . "</p>";
        echo "<p><b>DNI:</b> " . htmlspecialchars($_SESSION['dni']) . "</p>";
        echo "<p><b>Sexo:</b> " . htmlspecialchars($_SESSION['sexo']) . "</p>";
        ?>
    </section>

    <section class="estadisticas">
        <h2>Estadísticas de Apuestas</h2>
        <p class="saldo-actual">Saldo actual: <?php echo htmlspecialchars($_SESSION['saldo']); ?></p>
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