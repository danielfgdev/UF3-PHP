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

    <form action="procesarRegistro.php" method="post" id="registro">
        <label>Usuario: <input type="text" name="usuario" required></label><br>
        <label>Contraseña: <input type="password" name="contraseña" required></label><br>
        <label>Edad: <input type="number" name="edad" min="0" required></label><br>
        <label>Nombre <input type="text" name="nombre" required></label><br>
        <label>Primer Apellido: <input type="text" name="primerApellido" required></label><br>
        <label>Segundo Apellido: <input type="text" name="segundoApellido" required></label><br>
        <label>dni <input type="text" name="dni" required></label><br>
        <label for="sexo">Sexo:</label>
        <select id="sexo" name="sexo">
            <option value="masculino">masculino</option>
            <option value="femenino">femenino</option>
            <option value="random">random</option>
        </select>
        <button type="submit">Registrarse</button>
    </form>

</div>



<?php include 'footer.php'; ?>