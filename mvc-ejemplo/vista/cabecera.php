<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC Ejemplo</title>

    <link rel="stylesheet" href="http://localhost/xampp/UF3-PHP/mvc-ejemplo/vista/css/styles.css">
    <script src="http://localhost/xampp/UF3-PHP/mvc-ejemplo/vista/js/scripts.js"></script>

</head>

<body>
    <h1>MVC Ejemplo</h1>
    <div class="menu">
        <a href="http://localhost/xampp/UF3-PHP/mvc-ejemplo/vista/vista.php">Inicio<span class="popup">Has seleccionado la
                opción Inicio.</span></a>
        <a href="#">Insertar<span class="popup">Has seleccionado la opción Insertar.</span></a>
        <a href="#">Modificar<span class="popup">Has seleccionado la opción Modificar.</span></a>
        <a href="#">Eliminar<span class="popup">Has seleccionado la opción Eliminar.</span></a>
        <a href="http://localhost/xampp/UF3-PHP/mvc-ejemplo/controlador/consultar.php">Consultar<span class="popup">Has
                seleccionado la opción Consultar.</span></a>
        <?php echo '<button onclick="saludar()">Saludar</button>'; ?>
    </div>
</body>

</html>