<?php


/* `session_start();` is a PHP function that initializes a new session or resumes the existing session
based on a session identifier passed via a GET or POST request, or a cookie. Sessions are a way to
store information (in variables) to be used across multiple pages. By calling `session_start();`,
you are starting a new session or resuming an existing session, allowing you to store and access
session variables throughout your PHP scripts. This is commonly used for tasks like user
authentication, storing user-specific data, and maintaining user sessions. */
session_start();


/* The code snippet `if (!isset(['rol']) || ['rol'] !== 'admin')` is checking whether
the session variable `['rol']` is not set or if it is set to a value other than `'admin'`. */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php"); // Redirigir si no es admin
    exit();
}


/* The line `include 'conexionBD.php';` is including a PHP file named `conexionBD.php` into the current
script. This means that the code within `conexionBD.php` will be executed as if it were part of the
current script at that point. */
include 'conexionBD.php';


/* The line `include 'enviarPdf/enviarEmail.php';` is including a PHP file named `enviarEmail.php` from
a directory named `enviarPdf` into the current script. This means that the code within
`enviarEmail.php` will be executed as if it were part of the current script at that point. */
include 'enviarPdf/enviarEmail.php';

/**
 * The function `listarUsuarios` retrieves a list of users from a database based on specified criteria
 * such as search term, role, limit, and offset.
 * 
 * param pdo The `` parameter in the `listarUsuarios` function is expected to be a PDO object
 * representing a connection to a database. This object is used to prepare and execute SQL statements
 * within the function. It allows the function to interact with the database to fetch user data based
 * on the provided criteria.
 * param limite The `` parameter in the `listarUsuarios` function represents the number of
 * records to retrieve in a single query. It is used to limit the number of results returned from the
 * database query. This parameter helps in paginating the results, allowing you to control how many
 * records are fetched at
 * param offset The `offset` parameter in the `listarUsuarios` function is used to specify the
 * starting point from where the records should be fetched in the database query result. It determines
 * how many initial records to skip before fetching the next set of records.
 * param termino The `` parameter in the `listarUsuarios` function is used for filtering the
 * results based on a search term. If a search term is provided, the function will filter the results
 * to include only records where the `nombre` or `apodo` columns contain the search term and the
 * 
 * return The function `listarUsuarios` returns an array of associative arrays containing the results
 * of the SQL query executed based on the provided parameters. The results are fetched using
 * `PDO::FETCH_ASSOC` mode, which fetches each row as an associative array where the keys represent the
 * column names.
 */

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


/* The line ` = isset(['termino']) ? ['termino'] : null;` is a ternary operator in
PHP that is used to assign a value to the variable `` based on the existence of a POST
parameter named `'termino'`. */
$termino = isset($_POST['termino']) ? $_POST['termino'] : null;

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

<?php include 'header.php'; ?>

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
<div class="paginacion-container">
    <div class="paginacion">
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="?pagina=<?php echo $i; ?>" <?php if ($i == $pagina) echo 'style="font-weight:bold;"'; ?>><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
    <div class="salir">
        <a href="salir.php" class="btn-salir">Salir</a>
    </div>
</div>




<?php include 'footer.php'; ?>