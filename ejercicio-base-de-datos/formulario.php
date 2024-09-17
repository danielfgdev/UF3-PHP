<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Sencillo</title>
</head>

<body>
    <h2>Formulario</h2>
    <form action="guardar.php" method="post" id="formulario">
        Nombre: <input type="text" name="nombre" />
        Apellidos: <input type="text" name="apellidos" />
        <input type="reset"> <input type="submit" />
    </form>