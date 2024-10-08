<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Cargar Bootstrap primero -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Cargar tu CSS personalizado después de Bootstrap -->
    <link rel="stylesheet" href="estilos.css">

    <!-- Cargar el CSS para el modo diurno -->
    <link id="diaNoche" rel="stylesheet" href="diurno.css">

    <title>Casino Virtual</title>
</head>



<body>

    <header>
        <div class="header-container">
            <button id="cambioTema"></button>
            <h1 id="enlace">Casino Virtual</h1>

            <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Menu</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="juego.php">Jugar</a></li>
                    <li><a class="dropdown-item" href="datosJugador.php">Mi perfil</a></li>
                    <li><a class="dropdown-item" href="modificarDatos.php">Modificar mis datos</a></li>
                    <li><a class="dropdown-item" href="tablaEstadisticas.php">Estadisticas</a></li>
                    <li><a class="dropdown-item" href="salir.php">Salir</a></li>
                </ul>
            </div>
        </div>
    </header>