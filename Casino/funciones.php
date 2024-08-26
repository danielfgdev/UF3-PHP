<?php
function verificarCredenciales($usuario, $contrasena, &$jugador)
{
    $jugadores = obtenerJugadores();

    if (is_array($jugadores)) {
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
    }

    return 'no_existe';
}

function obtenerJugadores()
{
    $archivo = 'jugadores.json';
    if (file_exists($archivo)) {
        $datos = file_get_contents($archivo);
        $jugadores = json_decode($datos, true);

        if (is_array($jugadores)) {
            return $jugadores;
        }
    }

    return [];
}

function registrarJugador($usuario, $contrasena, $saldoInicial)
{
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
        'jugadas' => []
    ];

    $jugadores[] = $nuevoJugador;

    guardarJugadores($jugadores);
    return true; // Registro exitoso
}

function guardarJugadores($jugadores)
{
    $archivo = 'jugadores.json';
    file_put_contents($archivo, json_encode($jugadores, JSON_PRETTY_PRINT));
}

function lanzarDados()
{
    return rand(2, 12);
}
?>