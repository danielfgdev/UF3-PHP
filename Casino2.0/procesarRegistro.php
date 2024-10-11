<?php
/* This PHP code snippet is a registration form processing script. Here's a breakdown of what it does: */

// Inicio de la sesión
session_start();

// Incluir la conexión a la base de datos
include 'conexionBD.php';

// Inicializar variable para el mensaje de error
$mensaje = '';

// Verificar que el método sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $emailRegistro = $_POST['emailRegistro'];
    $edad = (int)$_POST['edad'];
    $nombre = $_POST['nombre'];
    $primerApellido = $_POST['primerApellido'];
    $segundoApellido = $_POST['segundoApellido'];
    $direccion = $_POST['direccion'];
    $dni = $_POST['dni'];
    $sexo = $_POST['sexo'];
    $saldo = 0;

    // Inicializar variable de errores
    $errores = [];

    // Validar la edad
    if ($edad < 18) {
        $errores[] = "edad_incorrecta";
    }

    // Verificar si el usuario ya está en uso
    $sql = "SELECT COUNT(*) FROM jugador WHERE apodo = :usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    if ($stmt->fetchColumn() > 0) {
        $errores[] = "usuario_duplicado";
    }

    // Verificar si el DNI ya está en uso
    $sql = "SELECT COUNT(*) FROM jugador WHERE dni = :dni";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dni', $dni);
    $stmt->execute();
    if ($stmt->fetchColumn() > 0) {
        $errores[] = "dni_duplicado";
    }

    // Verificar si el correo electrónico ya está en uso
    $sql = "SELECT COUNT(*) FROM jugador WHERE emailRegistro = :emailRegistro";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':emailRegistro', $emailRegistro);
    $stmt->execute();
    if ($stmt->fetchColumn() > 0) {
        $errores[] = "email_duplicado";
    }

    // Si no hay errores, proceder con el registro
    if (empty($errores)) {
        // Hash de la contraseña para almacenarla de forma segura
        $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

        // Preparar la consulta para insertar en la base de datos
        $sql = "INSERT INTO jugador (nombre, apellidos, dni, edad, apodo, sexo, saldo, contrasena, emailRegistro, direccion)
                VALUES (:nombre, :apellidos, :dni, :edad, :apodo, :sexo, :saldo, :contrasena, :emailRegistro, :direccion)";

        $stmt = $pdo->prepare($sql);

        // Concatenar apellidos
        $apellidos = $primerApellido . ' ' . $segundoApellido;

        // Vincular los parámetros a la consulta
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':edad', $edad);
        $stmt->bindParam(':apodo', $usuario);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':saldo', $saldo);
        $stmt->bindParam(':contrasena', $contrasenaHash);
        $stmt->bindParam(':emailRegistro', $emailRegistro);
        $stmt->bindParam(':direccion', $direccion);

        try {
            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Registro exitoso, redirigir a index.php
                header("Location: index.php");
                exit();
            } else {
                $errores[] = "registro_fallido";
            }
        } catch (PDOException $e) {
            // Captura de errores si hay un problema en la consulta
            $errores[] = "error_sql";
        }
    }

    // Si hay errores, redirigir a registro.php con los errores
    if (!empty($errores)) {
        $errorString = implode(",", $errores);
        header("Location: registro.php?errores=$errorString");
        exit();
    }
} else {
    // Si la sesión no es POST (GET), redirigir a registro con un mensaje
    header("Location: registro.php?mensaje=no_enviado");
    exit();
}
