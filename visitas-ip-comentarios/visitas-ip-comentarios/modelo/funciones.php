<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = htmlspecialchars($_POST["nombre"]);
    $comentario = htmlspecialchars($_POST["comentario"]);

    // Establecer la zona horaria
    date_default_timezone_set("Europe/Madrid");

    // Obtener la fecha y hora actual
    $fechaYHora = date("Y-m-d H:i:s");

    // Formatear el mensaje a guardar
    $mensaje = sprintf("Nombre: %s\nComentario: %s\nFecha y Hora: %s\n\n", $nombre, $comentario, $fechaYHora);

    comentario($mensaje);
}

function visitaLog()
{
    // Ruta al archivo que almacena el contador
    $archivoContador = "./data/visitaLog.txt";
    // Comprobar si el archivo existe y es legible
    if (is_readable($archivoContador)) {
        // Leer el valor actual del contador
        $contador = file_get_contents($archivoContador);
        // Incrementar el contador
        $contador++;
        // Abrir el archivo para escritura
        $fp = fopen($archivoContador, "w");
        // Escribir el nuevo valor del contador en el archivo
        fwrite($fp, $contador);
        // Cerrar el archivo
        fclose($fp);
        // Mostrar el contador de visitas
        return $contador;
    } else {
        return false;
    }

}
function ipLog()
{
    // Establecer la zona horaria
    date_default_timezone_set("Europe/Madrid");

    // Obtener la fecha y hora actual
    $fechaYHora = date("Y-m-d H:i:s");

    // Obtener la IP del visitante
    $ip = empty($_SERVER["REMOTE_ADDR"]) ? "Desconocida" : $_SERVER["REMOTE_ADDR"];

    // Formatear el mensaje a guardar
    $mensaje = sprintf("La IP %s accedió en %s%s", $ip, $fechaYHora, PHP_EOL);

    // Guardar el mensaje en el archivo ips.txt
    file_put_contents("./data/ipLog.txt", $mensaje, FILE_APPEND);
}

function comentario($mensaje)
{
    $file = $_SERVER['DOCUMENT_ROOT'] . '/xampp/visitas-ip-comentarios/data/comentario.txt';
    $comentario = file_put_contents($file, $mensaje, FILE_APPEND);
    if ($comentario) {
        echo "Comentario guardado con éxito.";
    } else {
        echo "Método de solicitud no válido.";
    }
}