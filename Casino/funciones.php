<?php
function obtenerJugadores()
{
    $data = file_get_contents('jugadores.json');
    return json_decode($data, true);
}

function guardarJugadores($jugadores)
{
    $data = json_encode($jugadores, JSON_PRETTY_PRINT);
    file_put_contents('jugadores.json', $data);
}

function registrarJugador($usuario, $contrasena, $saldoInicial, $edad)
{
    if ($edad < 18) {
        return false; // El jugador debe tener al menos 18 años
    }

    $jugadores = obtenerJugadores();

    foreach ($jugadores as $j) {
        if ($j['usuario'] === $usuario) {
            return false; // El usuario ya existe
        }
    }

    $nuevoJugador = [
        'usuario' => $usuario,
        'contrasena' => $contrasena,
        'saldo' => $saldoInicial,
        'edad' => $edad, // Añadir edad al nuevo jugador
        'jugadas' => []
    ];

    $jugadores[] = $nuevoJugador;

    guardarJugadores($jugadores);
    return true; // Registro exitoso
}

function verificarCredenciales($usuario, $contrasena, &$jugador)
{
    $jugadores = obtenerJugadores();

    foreach ($jugadores as $j) {
        if ($j['usuario'] === $usuario) {
            if ($j['contrasena'] === $contrasena) {
                $jugador = $j;
                return 'correcto';
            } else {
                return 'contrasena_incorrecta';
            }
        }
    }

    return 'no_existe';
}

function lanzarDados()
{
    $dado1 = rand(1, 6);
    $dado2 = rand(1, 6);
    $resultado = $dado1 + $dado2;
    return [$dado1, $dado2, $resultado]; // Devolver los resultados de ambos dados y la suma
}
