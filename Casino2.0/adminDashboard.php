<?php

/**
 * The PHP code snippet is a dashboard for an admin user that allows them to manage and interact with a
 * list of players, including search functionality, player details display, editing, deleting, sending
 * statistics via email, and pagination.
 * 
 * The `pdo` parameter in your code represents a PDO (PHP Data Objects) object that is used
 * for connecting to a database and executing queries. It is commonly used in PHP to interact with
 * databases in a secure and efficient manner.
 * The `limite` parameter in the code represents the limit of users to display per page.
 * In this case, it is set to 10, meaning that the code will display up to 10 users per page when
 * listing users on the admin dashboard. This limit helps in organizing and presenting the user
 * The `offset` parameter in the code snippet you provided is used to determine the
 * starting point for retrieving a subset of records from a database query. It is typically used in
 * conjunction with the `LIMIT` clause to implement pagination.
 * The `termino` parameter in the code represents the search term input by the user in
 * the search form. It is used to filter the list of users (specifically players) based on their name
 * or nickname. When a user enters a search term and submits the form, the PHP code processes this
 * 
 * The code provided is a PHP script for an admin dashboard. Here's a summary of what the code
 * does:
 */
session_start();

// Verificar que el usuario sea admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php"); // Redirigir si no es admin
    exit();
}

// Incluir la conexión a la base de datos
include 'conexionBD.php';

// Incluir la función que envía el PDF
include 'enviarPdf/enviarEmail.php'; // Asegúrate de que la ruta sea correcta

// Función para listar solo los jugadores, excluyendo administradores
function listarUsuarios($pdo, $limite, $offset, $termino = null)
{
    if ($termino) {
        // Filtrar por término de búsqueda y rol de jugador
        $sql = "SELECT * FROM jugador WHERE (nombre LIKE :termino OR apodo LIKE :termino) AND rol = 'jugador' LIMIT :limite OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':termino', '%' . $termino . '%');
    } else {
        // Mostrar solo jugadores
        $sql = "SELECT * FROM jugador WHERE rol = 'jugador' LIMIT :limite OFFSET :offset";
        $stmt = $pdo->prepare($sql);
    }
    // Vincular los parámetros límite y offset
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retornar los resultados
}

// Verificar si se ha enviado un término de búsqueda
$termino = isset($_POST['termino']) ? $_POST['termino'] : null;

// Obtener el número total de jugadores (excluyendo admins)
$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM jugador WHERE rol = 'jugador'")->fetchColumn();

// Definir el límite de usuarios por página
$limite = 10; // Cantidad de jugadores a mostrar por página
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; // Página actual
$offset = ($pagina - 1) * $limite; // Cálculo del desplazamiento

// Llamar a la función para obtener los jugadores
$usuarios = listarUsuarios($pdo, $limite, $offset, $termino);

// Calcular el número total de páginas
$totalPaginas = ceil($totalUsuarios / $limite); // Redondear hacia arriba para obtener el total de páginas

// Verificar si se ha enviado el formulario para enviar el PDF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_pdf'])) {
    $id_jugador = $_POST['id_jugador'];
    $emailRegistro = $_POST['emailRegistro'];

    // Llamar a la función que genera y envía el PDF
    if (generarYEnviarPDF($id_jugador, $emailRegistro, $pdo)) {
        echo "<script>alert('PDF enviado correctamente al jugador ID $id_jugador.');</script>";
    } else {
        echo "<script>alert('Error al generar el PDF para el jugador ID $id_jugador.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard de Admin</title>
    <link rel="stylesheet" href="estilos.css"> <!-- Verifica que el archivo de estilos existe -->
</head>

<body>
    <h1>Dashboard de Administrador</h1>

    <!-- Formulario de búsqueda -->
    <form method="POST">
        <input type="text" name="termino" placeholder="Buscar por nombre o apodo" value="<?php echo htmlspecialchars($termino); ?>">
        <button type="submit">Buscar</button>
    </form>

    <h2>Lista de Jugadores</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apodo</th>
                <th>Rol</th>
                <th>Saldo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario['id_jugador']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['apodo']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['saldo']); ?></td>
                    <td>
                        <a href="modificarJugadorAdmin.php?id=<?php echo $usuario['id_jugador']; ?>">Editar</a>

                        <form action="eliminarUsuario.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este jugador?');">
                            <input type="hidden" name="id_jugador" value="<?php echo $usuario['id_jugador']; ?>">
                            <button type="submit" style="background:none; border:none; color:red; cursor:pointer;">Eliminar</button>
                        </form>

                        <!-- Formulario para enviar estadísticas por correo -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_jugador" value="<?php echo $usuario['id_jugador']; ?>">
                            <input type="hidden" name="emailRegistro" value="<?php echo $usuario['emailRegistro']; ?>"> <!-- Asegúrate que 'emailRegistro' es correcto -->
                            <input type="hidden" name="enviar_pdf" value="1">
                            <button type="submit" style="background:none; border:none; color:green; cursor:pointer;">Enviar Estadísticas</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="paginacion">
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="?pagina=<?php echo $i; ?>" <?php if ($i == $pagina) echo 'style="font-weight:bold;"'; ?>><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>

    <!-- Enlace para cerrar sesión -->
    <a href="salir.php">Salir</a>
</body>

</html>