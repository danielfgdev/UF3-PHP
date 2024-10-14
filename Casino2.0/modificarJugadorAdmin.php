<?php
/* Este código PHP es para modificar los datos de un jugador en un panel de administración. A continuación se detalla lo que hace el código. */

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
    $nuevoUsuario = $_POST['usuario'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $primerApellido = $_POST['primerApellido'] ?? null;
    $segundoApellido = $_POST['segundoApellido'] ?? null;
    $edad = $_POST['edad'] ?? null;
    $dni = $_POST['dni'] ?? null;
    $sexo = $_POST['sexo'] ?? null;
    $nuevaContrasena = $_POST['nueva_contrasena'] ?? null;
    $emailRegistro = $_POST['emailRegistro'] ?? null;
    $direccion = $_POST['direccion'] ?? null;

    $apellidos = trim($primerApellido . ' ' . $segundoApellido);

    // Inicializar variable de errores
    $errores = [];

    // Verificar si el nuevo nombre de usuario ya está en uso por otro jugador
    if ($nuevoUsuario) {
        $sql = "SELECT COUNT(*) FROM jugador WHERE apodo = :nuevoUsuario AND id_jugador != :id_jugador";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nuevoUsuario', $nuevoUsuario);
        $stmt->bindParam(':id_jugador', $id_jugador);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            $errores[] = "El usuario ya está en uso. Por favor, elige otro.";
        }
    }

    // Verificar si el email ya está en uso
    if ($emailRegistro) {
        $sql = "SELECT COUNT(*) FROM jugador WHERE emailRegistro = :emailRegistro AND id_jugador != :id_jugador";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':emailRegistro', $emailRegistro);
        $stmt->bindParam(':id_jugador', $id_jugador);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            $errores[] = "El correo electrónico ya está en uso. Por favor, elige otro.";
        }

        // Validar el correo electrónico
        if (!filter_var($emailRegistro, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El correo electrónico no es válido.";
        }
    }

    // Verificar si el DNI ya está en uso
    if ($dni) {
        $sql = "SELECT COUNT(*) FROM jugador WHERE dni = :dni AND id_jugador != :id_jugador";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':id_jugador', $id_jugador);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            $errores[] = "El DNI ya está en uso. Por favor, elige otro.";
        }


        // Validar el formato del DNI (debe tener 8 dígitos y una letra)
        if (!preg_match('/^\d{8}[A-Za-z]$/', $dni)) {
            $errores[] = "El DNI debe tener el formato correcto (8 dígitos seguidos de una letra).";
        }
    }

    // Si no hay errores, proceder con la actualización
    if (empty($errores)) {
        $sql = "UPDATE jugador SET ";
        $actualizaciones = [];

        if ($nuevoUsuario) {
            $actualizaciones[] = "apodo = :usuario";
        }
        if ($nombre) {
            $actualizaciones[] = "nombre = :nombre";
        }
        if ($apellidos) {
            $actualizaciones[] = "apellidos = :apellidos";
        }
        if ($edad) {
            $actualizaciones[] = "edad = :edad";
        }
        if ($dni) {
            $actualizaciones[] = "dni = :dni";
        }
        if ($sexo) {
            $actualizaciones[] = "sexo = :sexo";
        }
        if ($emailRegistro) {
            $actualizaciones[] = "emailRegistro = :emailRegistro";
        }
        if ($direccion) {
            $actualizaciones[] = "direccion = :direccion";
        }
        if (!empty($nuevaContrasena)) {
            $nuevaContrasenaHash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
            $actualizaciones[] = "contrasena = :nueva_contrasena";
        }

        $sql .= implode(", ", $actualizaciones);
        $sql .= " WHERE id_jugador = :id_jugador";

        $stmt = $pdo->prepare($sql);
        if ($nuevoUsuario) {
            $stmt->bindParam(':usuario', $nuevoUsuario);
        }
        if ($nombre) {
            $stmt->bindParam(':nombre', $nombre);
        }
        if ($apellidos) {
            $stmt->bindParam(':apellidos', $apellidos);
        }
        if ($edad) {
            $stmt->bindParam(':edad', $edad);
        }
        if ($dni) {
            $stmt->bindParam(':dni', $dni);
        }
        if ($sexo) {
            $stmt->bindParam(':sexo', $sexo);
        }
        if ($emailRegistro) {
            $stmt->bindParam(':emailRegistro', $emailRegistro);
        }
        if ($direccion) {
            $stmt->bindParam(':direccion', $direccion);
        }
        if (!empty($nuevaContrasena)) {
            $stmt->bindParam(':nueva_contrasena', $nuevaContrasenaHash);
        }
        $stmt->bindParam(':id_jugador', $id_jugador);

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

<?php include 'header.php'; ?>
<br>
<h2> <br>Modificar Datos del Jugador</h2>

<!-- Aquí se muestra el mensaje si hay algún error -->
<?php if (!empty($mensaje)): ?>
    <div style="color: #ee1414; margin-bottom: 10px;">
        <?php echo $mensaje; ?>
    </div>
<?php endif; ?>
<div class="modificar-datos-container">
    <form action="modificarJugadorAdmin.php?id=<?php echo $id_jugador; ?>" method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" value="<?php echo $usuario; ?>"><br>

        <label for="nueva_contrasena">Nueva Contraseña (opcional):</label>
        <input type="password" id="nueva_contrasena" name="nueva_contrasena"><br>

        <label for="emailRegistro">Correo electrónico:</label>
        <input type="email" id="emailRegistro" name="emailRegistro" value="<?php echo $emailRegistro; ?>"><br>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>"><br>

        <label for="primerApellido">Primer Apellido:</label>
        <input type="text" id="primerApellido" name="primerApellido" value="<?php echo $primerApellido; ?>"><br>

        <label for="segundoApellido">Segundo Apellido:</label>
        <input type="text" id="segundoApellido" name="segundoApellido" value="<?php echo $segundoApellido; ?>"><br>

        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" value="<?php echo $edad; ?>" min="18"><br>

        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" value="<?php echo $dni; ?>"><br>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" value="<?php echo $direccion; ?>"><br>

        <label for="sexo">Sexo:</label>
        <select id="sexo" name="sexo">
            <option value="masculino" <?php echo ($sexo == 'masculino') ? 'selected' : ''; ?>>Masculino</option>
            <option value="femenino" <?php echo ($sexo == 'femenino') ? 'selected' : ''; ?>>Femenino</option>
            <option value="random" <?php echo ($sexo == 'random') ? 'selected' : ''; ?>>Random</option>
        </select><br>
        <button type="submit" name="modificar">Guardar cambios</button>
    </form>
    <a href="adminDashboard.php">Volver al Panel de Administrador</a>
</div>



<?php include 'footer.php'; ?>