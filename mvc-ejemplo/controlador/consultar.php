<?php
include("../modelo/modelo.php");
require_once("../vista/cabecera.php");
// $sql = "SELECT nombre_paciente, apellido_paciente FROM paciente";
// try {
//     $resultadoSQL = $db->query($sql);
//     echo 'Total pacientes: ' . $resultadoSQL->rowCount() . '<br>';
//     foreach ($resultadoSQL as $row) {
//         print $row['nombre_paciente'] . ' ' . $row['apellido_paciente'] . '<br>';
//     }
// } catch (PDOException $e) {
//     echo 'Error con la base de datos: ' . $e->getMessage();
// }


// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recoger el dato que el usuario ingresó
$buscar = "";
$resultado_html = "";

if (isset($_POST['buscar'])) {
    $buscar = $_POST['buscar'];

    // Consulta SQL con búsqueda parcial
    $sql = "SELECT * FROM paciente WHERE nombre_paciente LIKE ? OR apellido_paciente LIKE ?";
    $stmt = $conn->prepare($sql);
    $buscar_param = "%" . $buscar . "%";
    $stmt->bind_param("ss", $buscar_param, $buscar_param);

    // Ejecutar y obtener los resultados
    $stmt->execute();
    $result = $stmt->get_result();

    // Generar resultados
    if ($result->num_rows > 0) {
        $resultado_html = "<h2>Resultados encontrados:</h2><ul>";
        while ($row = $result->fetch_assoc()) {
            $resultado_html .= "<li>" . $row['nombre_paciente'] . " " . $row['apellido_paciente'] . "</li>";
        }
        $resultado_html .= "</ul>";
    } else {
        $resultado_html = "No se encontraron resultados.";
    }

    // Cerrar conexión
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Nombres</title>
</head>

<body>
    <h1>Buscar Nombre o Apellido</h1>

    <!-- Formulario HTML -->
    <form method="POST" action="consultar.php">
        <label for="buscar">Buscar nombre o apellido:</label>
        <input type="text" name="buscar" id="buscar" value="<?php echo htmlspecialchars($buscar); ?>" required>
        <button type="submit">Buscar</button>
    </form>

    <!-- Mostrar los resultados -->
    <?php echo $resultado_html; ?>
</body>

</html>