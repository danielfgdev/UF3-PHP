<?php
// Define la ruta al archivo JSON donde se guardan los datos de los jugadores
define('RUTA_JUGADORES', 'jugadores.json');

// Obtiene la lista de jugadores desde el archivo JSON
function obtenerJugadores()
{
    // Verifica si el archivo existe
    if (!file_exists(RUTA_JUGADORES)) {
        return [];
    }

    // Lee el contenido del archivo JSON
    $data = file_get_contents(RUTA_JUGADORES);

    // Si la lectura falla o el archivo está vacío, devuelve un array vacío
    if ($data === false || empty($data)) {
        return [];
    }

    // Decodifica el JSON en un array PHP
    $jugadores = json_decode($data, true);

    // Verifica si hubo un error en la decodificación
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [];
    }

    return $jugadores;
}

// Guarda la lista de jugadores en el archivo JSON
function guardarJugadores($jugadores)
{
    // Codifica el array de jugadores en formato JSON
    $data = json_encode($jugadores, JSON_PRETTY_PRINT);

    // Guarda el JSON en el archivo
    file_put_contents(RUTA_JUGADORES, $data);
}

// Registra un nuevo jugador si cumple con los requisitos
function registrarJugador($usuario, $contrasena, $saldoInicial, $edad)
{
    // Verifica si el jugador es mayor de edad
    if ($edad < 18) {
        return false;
    }

    // Obtiene la lista actual de jugadores
    $jugadores = obtenerJugadores();

    // Inicializa el array de jugadores si es necesario
    if (!is_array($jugadores)) {
        $jugadores = [];
    }

    // Verifica si el usuario ya existe
    foreach ($jugadores as $j) {
        if ($j['usuario'] === $usuario) {
            return false;
        }
    }

    // Crea un nuevo jugador
    $nuevoJugador = [
        'usuario' => $usuario,
        'contrasena' => $contrasena,
        'saldo' => $saldoInicial,
        'edad' => $edad,
        'jugadas' => [] // Inicializa el historial de jugadas vacío
    ];

    // Añade el nuevo jugador a la lista
    $jugadores[] = $nuevoJugador;

    // Guarda la lista actualizada de jugadores
    guardarJugadores($jugadores);
    return true;
}

// Verifica las credenciales del usuario
function verificarCredenciales($usuario, $contrasena, &$jugador)
{
    // Obtiene la lista de jugadores
    $jugadores = obtenerJugadores();

    // Verifica si la lista de jugadores es un array
    if (!is_array($jugadores)) {
        return 'no_existe';
    }

    // Busca al jugador con el nombre de usuario proporcionado
    foreach ($jugadores as $index => $j) {
        if ($j['usuario'] === $usuario) {
            // Verifica la contraseña
            if ($j['contrasena'] === $contrasena) {
                $jugador = $j;
                $jugador['index'] = $index; // Guarda el índice para actualizar después
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
    // Obtiene la lista de jugadores
    $jugadores = obtenerJugadores();

    // Verifica que el índice del jugador sea válido
    if (!isset($jugador['index']) || !isset($jugadores[$jugador['index']])) {
        return false;
    }

    // Actualiza la información del jugador en la lista
    $jugadores[$jugador['index']] = $jugador;

    // Guarda la lista actualizada de jugadores
    guardarJugadores($jugadores);
    return true;
}

// Lanza dos dados y devuelve los resultados
function lanzarDados()
{
    // Genera dos números aleatorios entre 1 y 6
    $dado1 = rand(1, 6);
    $dado2 = rand(1, 6);

    // Calcula el resultado de la suma de los dados
    $resultado = $dado1 + $dado2;

    return [$dado1, $dado2, $resultado];
}
