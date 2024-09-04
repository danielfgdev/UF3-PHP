<?php


// Inicio de la sesión
session_start();
?>

<?php include 'header.php'; ?>

<div>


    <!-- Coje los datos de procesarRegistro.php y los muestra -->
    <h2>Datos del jugador:</h2>
    <?php
    echo "<p>Usuario: " . htmlspecialchars($_SESSION['usuario']) . "</p>";
    echo "<p>Nombre: " . htmlspecialchars($_SESSION['nombre']) . "</p>";
    echo "<p>Primer Apellido: " . htmlspecialchars($_SESSION['primerApellido']) . "</p>";
    echo "<p>Segundo Apellido: " . htmlspecialchars($_SESSION['segundoApellido']) . "</p>";
    echo "<p>Edad: " . htmlspecialchars($_SESSION['edad']) . "</p>";
    echo "<p>DNI: " . htmlspecialchars($_SESSION['dni']) . "</p>";
    echo "<p>Sexo: " . htmlspecialchars($_SESSION['sexo']) . "</p>";
    ?>
</div>

<div class="estadisticas-container">
    <h2>Estadísticas de Apuestas</h2>

    <p class="dinero-disponible">Saldo actual: <?php echo htmlspecialchars($_SESSION['saldo']); ?></p>

    <table border="1" cellspacing="0" cellpadding="12">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Apuesta</th>
                <th>Resultado</th>
                <th>Ganancia</th>
                <th>Pérdida</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>


            <?php


            // Coge los datos de juego.php y los muestra
            if (isset($_SESSION['historial_apuestas']) && count($_SESSION['historial_apuestas']) > 0) {
                foreach ($_SESSION['historial_apuestas'] as $apuesta) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($apuesta['fecha']) . "</td>";
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

    <nav>
        <a href="juego.php">Volver al juego</a> |
        <a href="salir.php">Salir</a>
    </nav>

</div>

<?php include 'footer.php'; ?>