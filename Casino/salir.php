<?php
session_start();
include 'funciones.php';

$usuario = $_SESSION['jugador']['usuario'] ?? null;

if ($usuario) {
    // Obtener la hora de inicio de sesión desde el archivo JSON
    $horaInicio = obtenerHoraInicio($usuario);

    if ($horaInicio) {
        $horaFin = date("Y-m-d H:i:s");
        $tiempoSesion = strtotime($horaFin) - strtotime($horaInicio);

        // Leer el archivo JSON
        $filePath = 'jugadores.json';
        $contenido = file_get_contents($filePath);
        if ($contenido === false) {
            die('Error al leer el archivo de jugadores.');
        }

        $jugadores = json_decode($contenido, true);
        if ($jugadores === null) {
            die('Error al decodificar el archivo JSON.');
        }

        // Actualizar el historial de la sesión
        $actualizado = false;
        foreach ($jugadores as &$jugador) {
            if ($jugador['usuario'] === $usuario) {
                $jugador['jugadas'][] = [
                    'fecha' => $horaFin,
                    'apuesta' => 0,
                    'resultado' => 'Sesión cerrada',
                    'ganancia' => 0,
                    'perdida' => 0,
                    'saldo' => $jugador['saldo'],
                    'tiempo_sesion' => $tiempoSesion
                ];
                $actualizado = true;
                break;
            }
        }

        // Guardar el archivo JSON si se actualizó
        if ($actualizado) {
            if (file_put_contents($filePath, json_encode($jugadores, JSON_PRETTY_PRINT)) === false) {
                die('Error al guardar el archivo de jugadores.');
            }
        } else {
            die('No se encontró el usuario en el archivo.');
        }
    }
}

// Destruir la sesión y eliminar datos de almacenamiento local
session_unset(); // Limpia las variables de sesión
session_destroy(); // Destruye la sesión

echo "<script>
    localStorage.removeItem('tiempoInicio');
    sessionStorage.removeItem('sessionStart');
    window.location.href = 'index.php';
</script>";
exit();
