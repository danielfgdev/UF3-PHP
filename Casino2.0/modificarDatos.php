<?php
/* This PHP code is for a user profile page where a logged-in player can modify their personal
information. Here's a breakdown of what the code does: */
// Inicio de la sesion
session_start();

// Verificar que el usuario sea jugador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'jugador') {
    header("Location: index.php"); // Redirigir si no es admin
    exit();
}

// Incluir la conexion a la base de datos
include 'conexionBD.php';

// Verificar que el usuario este logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Obtener el id del jugador desde la sesion
$id_jugador = $_SESSION['id_jugador'];

// Si el formulario fue enviado para modificar los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar'])) {
    // Obtener los nuevos datos del formulario
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

    // Concatenar apellidos
    $apellidos = $primerApellido . ' ' . $segundoApellido;

    // Inicializar variable para el mensaje de error
    $mensaje = '';

    // Verificar si el nuevo usuario ya existe
    $sql = "SELECT COUNT(*) FROM jugador WHERE apodo = :nuevoUsuario AND id_jugador != :id_jugador";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nuevoUsuario', $nuevoUsuario);
    $stmt->bindParam(':id_jugador', $id_jugador);
    $stmt->execute();

    // Si el nombre de usuario ya esta en uso, mostrar un mensaje de error
    if ($stmt->fetchColumn() > 0) {
        $mensaje .= "<p style='color: #ee1414;'>El usuario ya está en uso. Por favor, elige otro.</p>";
    }

    // Verificar si el correo electrónico ya está en uso
    $sql = "SELECT COUNT(*) FROM jugador WHERE emailRegistro = :emailRegistro AND id_jugador != :id_jugador";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':emailRegistro', $emailRegistro);
    $stmt->bindParam(':id_jugador', $id_jugador);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        $mensaje .= "<p style='color: #ee1414;'>El correo electrónico ya está en uso. Por favor, elige otro.</p>";
    }

    // Verificar si el DNI ya está en uso
    $sql = "SELECT COUNT(*) FROM jugador WHERE dni = :dni AND id_jugador != :id_jugador";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dni', $dni);
    $stmt->bindParam(':id_jugador', $id_jugador);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        $mensaje .= "<p style='color: #ee1414;'>El DNI ya está en uso. Por favor, elige otro.</p>";
    }

    // Si no hay errores, proceder con la actualización
    if (empty($mensaje)) {
        // Actualizar los datos del jugador en la base de datos
        $sql = "UPDATE jugador SET apodo = :usuario, nombre = :nombre, apellidos = :apellidos, edad = :edad, dni = :dni, sexo = :sexo, emailRegistro = :emailRegistro, direccion = :direccion";

        // Si hay una nueva contraseña, actualizarla
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
        $stmt->bindParam(':id_jugador', $id_jugador);
        $stmt->bindParam(':emailRegistro', $emailRegistro);
        $stmt->bindParam(':direccion', $direccion);

        // Si hay nueva contraseña, vincular el parámetro
        if (!empty($nuevaContrasena)) {
            $stmt->bindParam(':nueva_contrasena', $nuevaContrasenaHash);
        }

        // Ejecutar la consulta y manejar errores
        if ($stmt->execute()) {
            // Actualizar los datos en la sesion
            $_SESSION['usuario'] = $nuevoUsuario;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['primerApellido'] = $primerApellido;
            $_SESSION['segundoApellido'] = $segundoApellido;
            $_SESSION['edad'] = $edad;
            $_SESSION['dni'] = $dni;
            $_SESSION['sexo'] = $sexo;
            $_SESSION['emailRegistro'] = $emailRegistro;
            $_SESSION['direccion'] = $direccion;

            // Redirigir a la pagina de estadisticas con un mensaje de exito
            header("Location: datosJugador.php?actualizado=1");
            exit();
        } else {
            // Mensaje de error si la actualizacion falla
            echo "<p>Error al actualizar los datos.</p>";
        }
    } else {
        // Mostrar mensaje de error
        echo $mensaje;
    }
}

// Obtener los datos actuales del jugador
$sql = "SELECT * FROM jugador WHERE id_jugador = :id_jugador";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_jugador', $id_jugador);
$stmt->execute();
$jugadorDatos = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si se obtuvieron los datos
if ($jugadorDatos) {
    // Asignar datos a variables
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
    // Mensaje de error si no se obtienen los datos
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
            <label for="nueva_contrasena">Nueva Contraseña:</label>
            <input type="password" id="nueva_contrasena" name="nueva_contrasena">
        </div>
        <div class="form-group">
            <label for="emailRegistro">Correo electronico:</label>
            <input type="email" id="emailRegistro" name="emailRegistro" value="<?php echo $emailRegistro; ?>">
        </div>
        <div class=" form-group">
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
            <label for="direccion">Direccion:</label>
            <input type="text" id="direccion" name="direccion" value="<?php echo $direccion; ?>" required>
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

    <!-- Boton para eliminar la cuenta -->
    <form action="eliminarUsuario.php" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.');">
        <input type="hidden" name="id_jugador" value="<?php echo $id_jugador; ?>">
        <button type="submit" name="eliminar" style="color: red;">Eliminar Cuenta</button>
    </form>

</div>

<?php include 'footer.php'; ?>