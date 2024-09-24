<?php
// Inicio de la sesión
session_start();

// Incluir la conexión a la base de datos
include 'conexionBD.php';

// Verificar que el método sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Preparar la consulta para verificar el usuario por apodo
    $sql = "SELECT * FROM jugador WHERE apodo = :usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);

    if ($stmt->execute()) {
        $usuarioDatos = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el usuario existe y la contraseña es correcta
        if ($usuarioDatos && password_verify($contrasena, $usuarioDatos['contrasena'])) {
            // Almacenar datos en la sesión
            $_SESSION['id_jugador'] = $usuarioDatos['id_jugador']; // Guardar el id único del jugador
            $_SESSION['usuario'] = $usuarioDatos['apodo'];
            $_SESSION['nombre'] = $usuarioDatos['nombre'];
            $_SESSION['primerApellido'] = explode(' ', $usuarioDatos['apellidos'])[0]; // Primer apellido
            $_SESSION['segundoApellido'] = explode(' ', $usuarioDatos['apellidos'])[1] ?? ''; // Segundo apellido
            $_SESSION['edad'] = $usuarioDatos['edad'];
            $_SESSION['dni'] = $usuarioDatos['dni'];
            $_SESSION['sexo'] = $usuarioDatos['sexo'];
            $_SESSION['saldo'] = $usuarioDatos['saldo']; // Saldo desde la base de datos
            $_SESSION['historial_apuestas'] = []; // Inicializa el historial de apuestas

            // Redirigir a juego.php
            header("Location: juego.php");
            exit();
        } else {
            echo "<script>alert(\"Login incorrecto\");</script>";
        }
    } else {
        echo "<script>alert(\"Error al verificar usuario\");</script>";
    }
}
?>

<?php include 'header.php'; ?>

<div class="login-container">
    <h2>Iniciar Sesión</h2>
    <form action="index.php" method="POST">
        <div class="form-group">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
        </div>
        <div class="form-group">
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
        </div>
        <button type="submit">Entrar</button>
    </form>
    <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
</div>

<?php include 'footer.php'; ?>