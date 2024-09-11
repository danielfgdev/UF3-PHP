<?php require_once("modelo/funciones.php"); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currículum Vitae de Rasmus Lerdorf</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>

<body>
    <h1>Contador de visitas</h1>
    <h2>Currículum Vitae de Rasmus Lerdorf</h2>
    <p><strong>Fecha de nacimiento:</strong> 22 de noviembre de 1968</p>
    <p><strong>Lugar de nacimiento:</strong> Qeqertarsuaq, Groenlandia</p>

    <div class="section">
        <h2>Educación</h2>
        <ul>
            <li>King City Secondary School, 1988</li>
            <li>Ingeniero en Diseño de Sistemas Informáticos, Universidad de Waterloo, 1993</li>
        </ul>
    </div>

    <div class="section">
        <h2>Experiencia Profesional</h2>
        <ul>
            <li>Investigador en Linuxcare Inc.</li>
            <li>Ingeniero de arquitectura e infraestructura en Yahoo! (2002-2009)</li>
            <li>Ingeniero en Etsy (desde 2012)</li>
            <li>Asesor principal de nuevas tecnologías en Jelastic (2013)</li>
        </ul>
    </div>

    <div class="section">
        <h2>Logros Notables</h2>
        <ul>
            <li>Creador de la primera versión del lenguaje de programación PHP</li>
            <li>Contribuciones al servidor Apache HTTP</li>
            <li>Nombrado en el MIT Technology Review TR100 como uno de los 100 principales innovadores del mundo menores
                de 35 años (2003)</li>
        </ul>
    </div>

    <div class="section">
        <h2>Proyectos y Contribuciones</h2>
        <ul>
            <li>Desarrollo de PHP, una herramienta de software libre</li>
            <li>Conectividad con bases de datos MySQL, Oracle y Sybase</li>
        </ul>
    </div>

    <div class="section">
        <h2>Enlaces de Interés</h2>
        <ul>
            <li><a href="https://es.wikipedia.org/wiki/Rasmus_Lerdorf" target="_blank">Wikipedia</a></li>
            <li><a href="https://www.buscabiografias.com/biografia/verDetalle/11044/Rasmus%20Lerdorf"
                    target="_blank">Biografía en Buscabiografías</a></li>
        </ul>
    </div>

    <div class="section">
        <h2>Enviar comentarios</h2>
        <form action="modelo/funciones.php" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br><br>
            <label for="comentario">Comentario:</label><br>
            <textarea id="comentario" name="comentario" rows="4" cols="50" required></textarea><br><br>
            <input type="submit" value="Enviar">
        </form>
    </div>

    <div class="section">
        <h2>Contador de visitas</h2>
        <?php
        ipLog();
        $contador = visitaLog();
        if ($contador) {
            echo "<p>Número de visitas: " . $contador . "</p>";
        } else {
            echo "<p>El archivo de contador no existe o no se puede leer.</p>";
        }
        ?>
    </div>

</body>

</html>