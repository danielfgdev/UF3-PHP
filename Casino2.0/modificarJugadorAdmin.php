<?php
session_start();

// Verificar que el usuario tenga el rol de administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Incluir la conexión a la base de datos
include 'conexionBD.php';

// Verificar si se pasa el ID del jugador a modificar
if (!isset($_GET['id'])) {
    echo "<p>Error: No se ha especificado el jugador a modificar.</p>";
    exit();
}

$id_jugador = $_GET['id']; // Obtener el ID del jugador desde la URL

// Inicializar variable para el mensaje de error
$mensaje = '';

// Si el formulario fue enviado para modificar los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar'])) {
    $nuevoUsuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $primerApellido = $_POST['primerApellido'];
    $segundoApellido = $_POST['segundoApellido'];
    $edad = $_POST['edad'];
    $dni = $_POST['dni'];
    $sexo = $_POST['sexo'];
    $nuevaContrasena = $_POST['nueva_contrasena'];
    $emailRegistro = $_POST['emailRegistro'];
    $direccion = $_POST['direccion'];

    $apellidos = $primerApellido . ' ' . $segundoApellido;

    // Inicializar variable de errores
    $errores = [];

    // Verificar si el nuevo nombre de usuario ya está en uso por otro jugador
    $sql = "SELECT COUNT(*) FROM jugador WHERE apodo = :nuevoUsuario AND id_jugador != :id_jugador";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nuevoUsuario', $nuevoUsuario);
    $stmt->bindParam(':id_jugador', $id_jugador);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        $errores[] = "El usuario ya está en uso. Por favor, elige otro.";
    }

    // Verificar si el email ya está en uso
    $sql = "SELECT COUNT(*) FROM jugador WHERE emailRegistro = :emailRegistro AND id_jugador != :id_jugador";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':emailRegistro', $emailRegistro);
    $stmt->bindParam(':id_jugador', $id_jugador);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        $errores[] = "El correo electrónico ya está en uso. Por favor, elige otro.";
    }

    // Verificar si el DNI ya está en uso
    $sql = "SELECT COUNT(*) FROM jugador WHERE dni = :dni AND id_jugador != :id_jugador";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dni', $dni);
    $stmt->bindParam(':id_jugador', $id_jugador);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        $errores[] = "El DNI ya está en uso. Por favor, elige otro.";
    }

    // Si no hay errores, proceder con la actualización
    if (empty($errores)) {
        $sql = "UPDATE jugador SET apodo = :usuario, nombre = :nombre, apellidos = :apellidos, edad = :edad, dni = :dni, sexo = :sexo, emailRegistro = :emailRegistro, direccion = :direccion";
        if (!empty($nuevaContrasena)) {
            $nuevaContrasenaHash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
            $sql .= ", contrasena = :nueva_contrasena";
        }
        $sql .= " WHERE id_jugador = :id_jugador";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario', $nuevoUsuario);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':edad', $edad);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':emailRegistro', $emailRegistro);
        $stmt->bindParam(':id_jugador', $id_jugador);
        $stmt->bindParam(':direccion', $direccion);

        if (!empty($nuevaContrasena)) {
            $stmt->bindParam(':nueva_contrasena', $nuevaContrasenaHash);
        }

        if ($stmt->execute()) {
            $mensaje = "Los datos del jugador se han actualizado con éxito."; // Mensaje de éxito
            echo "<script>alert('$mensaje'); window.location.href='adminDashboard.php';</script>";
            exit(); // Evitar que el resto del código se ejecute después de la redirección
        } else {
            $mensaje = "Error al actualizar los datos.";
        }
    } else {
        // Concatenar todos los errores en un mensaje
        $mensaje = implode("<br>", $errores);
    }
}

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
    $emailRegistro = htmlspecialchars($jugadorDatos['emailRegistro']);
    $direccion = htmlspecialchars($jugadorDatos['direccion']);
} else {
    echo "<p>Error al obtener los datos del jugador.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Modificar Jugador</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <h2>Modificar Datos del Jugador</h2>

    <!-- Aquí se muestra el mensaje si hay algún error -->
    <?php if (!empty($mensaje)): ?>
        <div style="color: #ee1414; margin-bottom: 10px;">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <form action="modificarJugadorAdmin.php?id=<?php echo $id_jugador; ?>" method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" value="<?php echo $usuario; ?>" required><br>

        <label for="nueva_contrasena">Nueva Contraseña (opcional):</label>
        <input type="password" id="nueva_contrasena" name="nueva_contrasena"><br>

        <label for="emailRegistro">Correo electrónico:</label>
        <input type="email" id="emailRegistro" name="emailRegistro" value="<?php echo $emailRegistro; ?>"><br>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required><br>

        <label for="primerApellido">Primer Apellido:</label>
        <input type="text" id="primerApellido" name="primerApellido" value="<?php echo $primerApellido; ?>" required><br>

        <label for="segundoApellido">Segundo Apellido:</label>
        <input type="text" id="segundoApellido" name="segundoApellido" value="<?php echo $segundoApellido; ?>"><br>

        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" value="<?php echo $edad; ?>" min="18" required><br>

        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" value="<?php echo $dni; ?>" required><br>

        <label for="direccion">Direccion:</label>
        <input type="text" id="direccion" name="direccion" value="<?php echo $direccion; ?>" required>

        <label for="sexo">Sexo:</label>
        <select id="sexo" name="sexo" required>
            <option value="masculino" <?php echo ($sexo == 'masculino') ? 'selected' : ''; ?>>Masculino</option>
            <option value="femenino" <?php echo ($sexo == 'femenino') ? 'selected' : ''; ?>>Femenino</option>
            <option value="random" <?php echo ($sexo == 'random') ? 'selected' : ''; ?>>Random</option>
        </select><br>


        <button type="submit" name="modificar">Guardar cambios</button>
    </form>

    <a href="adminDashboard.php">Volver al Panel de Administrador</a>
</body>

</html>