<?php


// Inicio de la sesión

session_start();


// Incluir la conexión a la base de datos

include 'conexionBD.php';


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

    // Si la edad es mayor o igual a 18 se registra el usuario en
    // la sesión y se redirige a index.php.

    if ($edad >= 18) {

        // Hash de la contraseña para almacenarla de forma segura !!!!QUITAR LA Ñ!!!!!!
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


        // Imprimir los parámetros para depuración

        echo "Parametros: $nombre, $apellidos, $dni, $edad, $usuario, $sexo, $saldo, $contrasenaHash, $emailRegistro, $direccion<br>";

        try {

            // Ejecutar la consulta
            if ($stmt->execute()) {

                // Registro exitoso, redirigir a index.php
                header("Location: index.php");
            } else {

                // Si hay un error en la inserción, redirigir a registro.php con un mensaje
                header("Location: registro.php?mensaje=error_db");
            }
        } catch (PDOException $e) {

            // Mostrar el error si ocurre un problema
            echo "Error en la consulta: " . $sql . "<br>";
            echo "Error: " . $e->getMessage();
        }
    } else {

        // Si la edad es menor de 18, redirigir a registro.php con un mensaje
        header("Location: registro.php?mensaje=edad_incorrecta");
    }
} else {

    // Si la sesión no es POST (GET), redirigir a registro con un mensaje
    header("Location: registro.php?mensaje=no_enviado");
}
