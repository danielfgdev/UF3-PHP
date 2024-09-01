<?php
// Define la ruta al archivo JSON donde se guardan los datos de los jugadores
define('RUTA_JUGADORES', 'jugadores.json');

// Obtiene la lista de jugadores desde el archivo JSON
function obtenerJugadores()
{
    if (!file_exists(RUTA_JUGADORES)) {
        return [];
    }

    $data = file_get_contents(RUTA_JUGADORES);
    if ($data === false || empty($data)) {
        return [];
    }

    $jugadores = json_decode($data, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [];
    }

    return $jugadores;
}

// Guarda la lista de jugadores en el archivo JSON
function guardarJugadores($jugadores)
{
    $data = json_encode($jugadores, JSON_PRETTY_PRINT);
    file_put_contents(RUTA_JUGADORES, $data);
}

// Registra un nuevo jugador si cumple con los requisitos
function registrarJugador($usuario, $contrasena, $saldoInicial, $edad)
{
    if ($edad < 18) {
        return false;
    }

    $jugadores = obtenerJugadores();
    if (!is_array($jugadores)) {
        $jugadores = [];
    }

    foreach ($jugadores as $j) {
        if ($j['usuario'] === $usuario) {
            return false;
        }
    }

    $nuevoJugador = [
        'usuario' => $usuario,
        'contrasena' => $contrasena,
        'saldo' => $saldoInicial,
        'edad' => $edad,
        'hora_inicio' => null, // Añadir la hora de inicio (inicialmente null)
        'jugadas' => [],

    ];

    $jugadores[] = $nuevoJugador;
    guardarJugadores($jugadores);
    return true;
}

// Verifica las credenciales del usuario
function verificarCredenciales($usuario, $contrasena, &$jugador)
{
    $jugadores = obtenerJugadores();
    if (!is_array($jugadores)) {
        return 'no_existe';
    }

    foreach ($jugadores as $index => $j) {
        if ($j['usuario'] === $usuario) {
            if ($j['contrasena'] === $contrasena) {
                $jugador = $j;
                $jugador['index'] = $index;
                return 'correcto';
            } else {
                return 'contrasena_incorrecta';
            }
        }
    }

    return 'no_existe';
}

// Actualiza la información de un jugador en el archivo JSON
function actualizarJugador($jugador)
{
    $jugadores = obtenerJugadores();
    if (!isset($jugador['index']) || !isset($jugadores[$jugador['index']])) {
        return false;
    }

    $jugadores[$jugador['index']] = $jugador;
    guardarJugadores($jugadores);
    return true;
}

// Lanza dos dados y devuelve los resultados
function lanzarDados()
{
    $dado1 = rand(1, 6);
    $dado2 = rand(1, 6);
    $resultado = $dado1 + $dado2;
    return [$dado1, $dado2, $resultado];
}

// Registra la hora de inicio de sesión
function registrarHoraInicio($usuario)
{
    $jugadores = obtenerJugadores();
    foreach ($jugadores as &$jugador) {
        if ($jugador['usuario'] === $usuario) {
            $jugador['hora_inicio'] = date("Y-m-d H:i:s");
            guardarJugadores($jugadores);
            return;
        }
    }
}

// Obtiene la hora de inicio de sesión
function obtenerHoraInicio($usuario)
{
    $jugadores = obtenerJugadores();
    foreach ($jugadores as $jugador) {
        if ($jugador['usuario'] === $usuario) {
            return $jugador['hora_inicio'] ?? null;
        }
    }
    return null;
}

// Calcula el tiempo de sesión desde la hora de inicio
function calcularTiempoSesion($horaInicio)
{
    if (!$horaInicio) {
        return null;
    }
    $horaInicio = new DateTime($horaInicio);
    $horaActual = new DateTime();
    $intervalo = $horaInicio->diff($horaActual);

    return $intervalo->s + $intervalo->i * 60 + $intervalo->h * 3600; // Devuelve el tiempo en segundos
}
