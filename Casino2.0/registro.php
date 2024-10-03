<?php include 'header.php'; ?>


<div class="registro-container">
    <h2>Registro</h2>

    <?php


    // Si en procesarRegistro.php la edad es incorrecta o la
    //  sesion es NO POST (GET) se muestra alguno de los mensajes de abajo.
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