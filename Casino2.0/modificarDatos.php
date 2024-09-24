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

// Si el formulario fue enviado para modificar los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar'])) {
    $nuevoUsuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $primerApellido = $_POST['primerApellido'];
    $segundoApellido = $_POST['segundoApellido'];
    $edad = $_POST['edad'];
    $dni = $_POST['dni'];
    $sexo = $_POST['sexo'];

    // Concatenar apellidos
    $apellidos = $primerApellido . ' ' . $segundoApellido;

    // Verificar si el nuevo usuario ya existe
    $sql = "SELECT COUNT(*) FROM jugador WHERE apodo = :nuevoUsuario AND id_jugador != :id_jugador";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nuevoUsuario', $nuevoUsuario);
    $stmt->bindParam(':id_jugador', $id_jugador);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        echo "<p style='color: #ee1414;'>El usuario ya está en uso. Por favor, elige otro.</p>";
    } else {


        // Actualizar los datos del jugador en la base de datos
        $sql = "UPDATE jugador SET apodo = :usuario, nombre = :nombre, apellidos = :apellidos, edad = :edad, dni = :dni, sexo = :sexo WHERE id_jugador = :id_jugador";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario', $nuevoUsuario);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':edad', $edad);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':id_jugador', $id_jugador);

        if ($stmt->execute()) {


            // Actualizar los datos en la sesión
            $_SESSION['usuario'] = $nuevoUsuario;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['primerApellido'] = $primerApellido;
            $_SESSION['segundoApellido'] = $segundoApellido;
            $_SESSION['edad'] = $edad;
            $_SESSION['dni'] = $dni;
            $_SESSION['sexo'] = $sexo;


            // Redirigir a la página de estadísticas con un mensaje de éxito
            header("Location: estadisticas.php?actualizado=1");
            exit();
        } else {
            echo "<p>Error al actualizar los datos.</p>";
        }
    }
}


// Si el formulario fue enviado para eliminar la cuenta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    // Eliminar al jugador de la base de datos
    $sql = "DELETE FROM jugador WHERE id_jugador = :id_jugador";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_jugador', $id_jugador);

    if ($stmt->execute()) {


        // Cerrar la sesión y redirigir al inicio

        session_destroy();
        header("Location: index.php?cuenta_eliminada=1");
        exit();
    } else {
        echo "<p>Error al eliminar la cuenta.</p>";
    }
}


// Obtener los datos actuales del jugador

$sql = "SELECT * FROM jugador WHERE id_jugador = :id_jugador";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_jugador', $id_jugador);
$stmt->execute();
$jugadorDatos = $stmt->fetch(PDO::FETCH_ASSOC);

if ($jugadorDatos) {
    $usuario = htmlspecialchars($jugadorDatos['apodo']);
    $nombre = htmlspecialchars($jugadorDatos['nombre']);
    $apellidos = explode(' ', $jugadorDatos['apellidos']);
    $primerApellido = htmlspecialchars($apellidos[0]);
    $segundoApellido = htmlspecialchars($apellidos[1] ?? '');
    $edad = htmlspecialchars($jugadorDatos['edad']);
    $dni = htmlspecialchars($jugadorDatos['dni']);
    $sexo = htmlspecialchars($jugadorDatos['sexo']);
} else {
    echo "<p>Error al obtener los datos del jugador.</p>";
    exit();
}
?>

<?php include 'header.php'; ?>


<!-- Modificar datos -->

<div class="modificar-datos-container">
    <h2>Modificar Datos</h2>
    <form action="modificarDatos.php" method="POST">
        <div class="form-group">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" value="<?php echo $usuario; ?>" required>
        </div>
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
        </div>
        <div class="form-group">
            <label for="primerApellido">Primer Apellido:</label>
            <input type="text" id="primerApellido" name="primerApellido" value="<?php echo $primerApellido; ?>" required>
        </div>
        <div class="form-group">
            <label for="segundoApellido">Segundo Apellido:</label>
            <input type="text" id="segundoApellido" name="segundoApellido" value="<?php echo $segundoApellido; ?>">
        </div>
        <div class="form-group">
            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad" value="<?php echo $edad; ?>" min="18" required>
        </div>
        <div class="form-group">
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" value="<?php echo $dni; ?>" required>
        </div>
        <div class="form-group">
            <label for="sexo">Sexo:</label>
            <select id="sexo" name="sexo" required>
                <option value="masculino" <?php echo ($sexo == 'masculino') ? 'selected' : ''; ?>>Masculino</option>
                <option value="femenino" <?php echo ($sexo == 'femenino') ? 'selected' : ''; ?>>Femenino</option>
                <option value="random" <?php echo ($sexo == 'random') ? 'selected' : ''; ?>>Random</option>
            </select>
        </div>
        <button type="submit" name="modificar">Guardar cambios</button>
    </form>


    <!-- Botón para eliminar la cuenta -->

    <form action="modificarDatos.php" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.');">
        <button type="submit" name="eliminar" style="color: red;">Eliminar Cuenta</button>
    </form>

    <a href="estadisticas.php">Cancelar</a>
</div>

<?php include 'footer.php'; ?>