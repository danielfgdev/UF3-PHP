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
            <button id="cambioTema">Cambiar a Modo Nocturno</button> <!-- Botón a la izquierda -->
            <h1 id="enlace">Casino Virtual</h1> <!-- Título en el centro -->
            <div class="dropdown"> <!-- Dropdown a la derecha -->
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Dropdown link
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
            </div>
        </div>
    </header>