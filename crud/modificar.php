<!DOCTYPE html>
<html>

<head>
    <title>Editar Usuario</title>
</head>

<body>
    <form action="editar_usuario.php" method="post">

        <input type="hidden" name="id_usuario" value="
        
        <?php echo $_GET['id_usuario']; ?>">

        Nombre: <input type="text" name="nombre_usuario" required><br>
        Apellido: <input type="text" name="apellido_usuario" required><br>
        DNI: <input type="text" name="dni_usuario" required><br>
        Dirección: <input type="text" name="direccion_usuario" required><br>
        Teléfono: <input type="text" name="telefono_usuario" required><br>
        <input type="submit" value="Actualizar">
    </form>
</body>

</html>