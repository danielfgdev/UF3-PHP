<?php

// Inicio de la sesion
session_start();


// Verificar que el metodo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];


    // Verifica si en las variables hay datos
    if (isset($_SESSION['usuario']) && isset($_SESSION['contraseña'])) {


        // Si el login es correcto se redirige al juego,
        if ($_SESSION['usuario'] === $usuario && $_SESSION['contraseña'] === $contraseña) {
            $_SESSION['logged_in'] = true;
            echo "<script>alert(\"Login correcto\");</script>";
            header("Location: juego.php");


            // Sino muestra mensaje de login incorrecto. 
        } else {
            echo "<script>alert(\"Login incorrecto\");</script>";
        }


        // Si no hay nadie registrado deja otro mensaje.
    } else {
        echo "<script>alert(\"Ningun registro\");</script>";
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
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required>
        </div>
        <button type="submit">Entrar</button>
    </form>


    <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
</div>



<?php include 'footer.php'; ?>