<?php
session_start();

// Verificar que el usuario sea admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php"); // Redirigir si no es admin
    exit();
}

// Incluir la conexión a la base de datos
include 'conexionBD.php';

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
                    <td><?php echo htmlspecialchars($usuario['id_jugador']); ?></td> <!-- Verifica que 'id_jugador' sea el nombre correcto -->
                    <td><?php echo htmlspecialchars($usuario['nombre']); ?></td> <!-- Verifica que 'nombre' sea el nombre correcto -->
                    <td><?php echo htmlspecialchars($usuario['apodo']); ?></td> <!-- Verifica que 'apodo' sea el nombre correcto -->
                    <td><?php echo htmlspecialchars($usuario['rol']); ?></td> <!-- Verifica que 'rol' sea el nombre correcto -->
                    <td><?php echo htmlspecialchars($usuario['saldo']); ?></td> <!-- Verifica que 'saldo' sea el nombre correcto -->
                    <td>
                        <!-- Enlace para editar los datos del jugador -->
                        <a href="modificarJugadorAdmin.php?id=<?php echo $usuario['id_jugador']; ?>">Editar</a>

                        <!-- Formulario para eliminar un jugador -->
                        <form action="eliminarUsuario.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este jugador?');">
                            <input type="hidden" name="id_jugador" value="<?php echo $usuario['id_jugador']; ?>">
                            <button type="submit" style="background:none; border:none; color:red; cursor:pointer;">Eliminar</button>
                        </form>

                        <!-- Formulario para enviar estadísticas por correo -->
                        <form action="enviarPdf/controlador.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id_jugador" value="<?php echo $usuario['id_jugador']; ?>">
                            <input type="hidden" name="emailRegistro" value="<?php echo $usuario['emailRegistro']; ?>"> <!-- Verifica que 'emailRegistro' sea la columna correcta -->
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