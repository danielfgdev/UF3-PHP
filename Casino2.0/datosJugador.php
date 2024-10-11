<?php
/* This PHP code snippet is responsible for displaying the details of a player (jugador) after
verifying the user's role and login status. Here's a breakdown of what the code does: */

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
        $direccion = htmlspecialchars($jugadorDatos['direccion']);
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
        <p><b>Direccion:</b> <?php echo $direccion; ?></p>
        <p><b>Sexo:</b> <?php echo $sexo; ?></p>



        <!-- Botón para modificar datos -->

        <form action="modificarDatos.php" method="GET">
            <button type="submit">Modificar</button>
        </form>
    </section>
</div>


<?php include 'footer.php'; ?>