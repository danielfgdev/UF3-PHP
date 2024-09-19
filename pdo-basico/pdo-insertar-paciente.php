<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de búsqueda</title>
</head>

<body>
    <h1>Formulario de insertar paciente</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="nombre_paciente">Nombre</label>
        <input type="text" name="nombre_paciente">
        <label for="apellido_paciente">Apellido</label>
        <input type="text" name="apellido_paciente">
        <label for="direccion_paciente">Dirección</label>
        <input type="text" name="direccion_paciente">
        <input type="submit" value="Insertar">
        <input type="reset" value="Limpiar">
    </form>
    <?php

    require_once("pdo-conexion.php");
    if (
        isset($_POST["nombre_paciente"]) and isset($_POST["apellido_paciente"])
        and isset($_POST["direccion_paciente"])
    ) {
        $nombre_paciente = $_POST["nombre_paciente"];
        $apellido_paciente = $_POST["apellido_paciente"];
        $direccion_paciente = $_POST["direccion_paciente"];
        $sqlPreparada = $db->prepare("INSERT INTO paciente 
        (nombre_paciente, apellido_paciente, direccion_paciente) 
        VALUES (?,?,?)");
        try {
            $sqlPreparada->execute(array(
                $nombre_paciente,
                $apellido_paciente,
                $direccion_paciente
            ));
            echo 'Registro guardado correctamente<br>';
        } catch (PDOException $e) {
            echo 'Error de inserción en la base de datos: ' . $e->getMessage();
        }
    } else {
        echo "No se han enviado datos<br>";
    }
    ?>
</body>

</html>