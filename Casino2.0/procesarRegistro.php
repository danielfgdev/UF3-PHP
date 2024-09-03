<?php

// Inicio de la sesion
session_start();


// Verificar que el metodo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    $edad = $_POST['edad'];
    $nombre = $_POST['nombre'];
    $primerApellido = $_POST['primerApellido'];
    $segundoApellido = $_POST['segundoApellido'];
    $dni = $_POST['dni'];
    $sexo = $_POST['sexo'];



    // Si la edad es mas de 18 se registra el usuario en
    //  la sesion y se redirige a index.php.
    if ($edad >= 18) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['contraseña'] = $contraseña;
        $_SESSION['edad'] = $edad;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['primerApellido'] = $primerApellido;
        $_SESSION['segundoApellido'] = $segundoApellido;
        $_SESSION['dni'] = $dni;
        $_SESSION['sexo'] = $sexo;

        header("Location: index.php");


        // Si la edad es menos de 18 se vuelve a registro.php 
        //  y se muestra mensaje (Se envia mediante GET a registro.php).
    } else {
        header("Location: registro.php?mensaje=edad_incorrecta");
    }


    // Si la sesion es NO POST (GET) el formulario no es correcto y
    //  se redirige a registro con un mensaje (Se envia mediante GET a registro.php).
    // Esto ocurre si se accede directamente a la URL, 
    //  si se recarga la pagina o si se entra mediante un enlace.
} else {

    header("Location: registro.php?mensaje=no_enviado");
}
