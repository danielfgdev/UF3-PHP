<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de búsqueda</title>
</head>

<body>
    <h1>Formulario de búsqueda</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre">
        <label for="apellido">Apellido</label>
        <input type="text" name="apellido">
        <input type="submit" value="Enviar">
        <input type="reset" value="Limpiar">
    </form>
    <?php
    require_once("pdo-conexion.php");
    if (isset($_POST["nombre"]) and isset($_POST["apellido"])) {
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $sqlPreparada = $db->prepare("SELECT 
        nombre_paciente, apellido_paciente 
        from paciente 
        where nombre_paciente = ? or apellido_paciente = ?");
        $sqlPreparada->execute(array($nombre, $apellido));
        echo "Pacientes con apellido $apellido y nombre $nombre: " . $sqlPreparada->rowCount() . "<br>";
        foreach ($sqlPreparada as $row) {
            print "Nombre: " . $row['nombre_paciente'] . "<br>";
            print "Apellido: " . $row['apellido_paciente'] . "<br>";
            print "-------------------------------------------<br>";
        }
    } else {
        echo "No se han enviado datos<br>";
    }
    ?>
</body>

</html>