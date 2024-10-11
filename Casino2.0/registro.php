<!-- /* This PHP code snippet is a registration form for a website. Here's a breakdown of what it does: */ -->
<?php include 'header.php'; ?>

<div class="registro-container">
    <h2>Registro</h2>

    <?php
    // Mostrar los errores si existen por intentar registrar datos que ya estan duplicados.
    if (isset($_GET['errores'])) {
        $errores = explode(",", $_GET['errores']);
        foreach ($errores as $error) {
            if ($error === 'edad_incorrecta') {
                echo "<div style='color:red;'>Debes tener al menos 18 años para registrarte.</div>";
            } elseif ($error === 'usuario_duplicado') {
                echo "<div style='color:red;'>El nombre de usuario ya está en uso. Por favor, elige otro.</div>";
            } elseif ($error === 'dni_duplicado') {
                echo "<div style='color:red;'>El DNI ya está en uso. Por favor, verifica tu DNI.</div>";
            } elseif ($error === 'email_duplicado') {
                echo "<div style='color:red;'>El correo electrónico ya está en uso. Por favor, usa otro correo.</div>";
            } elseif ($error === 'registro_fallido') {
                echo "<div style='color:red;'>Hubo un error en el registro. Inténtalo de nuevo.</div>";
            } elseif ($error === 'error_sql') {
                echo "<div style='color:red;'>Error en el servidor. Contacta al administrador.</div>";
            }
        }
    }

    // Si en procesarRegistro.php la edad es incorrecta o la sesion es NO POST (GET) se muestra alguno de los mensajes de abajo.
    if (isset($_GET['mensaje'])) {
        $mensaje = $_GET['mensaje'];
        if ($mensaje === 'edad_incorrecta') {
            echo "<script>alert(\"Mínimo 18 años para registrarse\");</script>";
        } elseif ($mensaje === 'no_enviado') {
            echo "<script>alert(\"No se ha enviado el formulario\");</script>";
        }
    }
    ?>

    <form class="form-registro" action="procesarRegistro.php" method="post" id="registro">
        <label>Usuario: <input type="text" name="usuario" required></label>
        <label>Contraseña: <input type="password" name="contrasena" required></label>
        <label>Correo electronico:<input type="email" name="emailRegistro" required></label>
        <label>Edad: <input type="number" name="edad" min="0" required></label>
        <label>Nombre: <input type="text" name="nombre" required></label>
        <label>Primer Apellido: <input type="text" name="primerApellido" required></label>
        <label>Segundo Apellido: <input type="text" name="segundoApellido" required></label>
        <label>Direccion: <input type="text" name="direccion" required></label>
        <label>DNI: <input type="text" name="dni" required></label>
        <div class="form-group">
            <label for="sexo">Sexo:</label>
            <select id="sexo" name="sexo">
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="random">Random</option>
            </select>
        </div>

        <button type="submit">Registrarse</button>
    </form>
</div>

<?php include 'footer.php'; ?>