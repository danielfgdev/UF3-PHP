<?php
session_start();

// Verificar que el usuario sea admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php"); // Redirigir si no es admin
    exit();
}

// Incluir la conexión a la base de datos
include 'conexionBD.php';

// Función para listar usuarios o buscar
function listarUsuarios($pdo, $limite, $offset, $termino = null)
{
    if ($termino) {
        $sql = "SELECT * FROM jugador WHERE nombre LIKE :termino OR apodo LIKE :termino LIMIT :limite OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':termino', '%' . $termino . '%');
    } else {
        $sql = "SELECT * FROM jugador LIMIT :limite OFFSET :offset";
        $stmt = $pdo->prepare($sql);
    }
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Verificar si se ha enviado un término de búsqueda
$termino = isset($_POST['termino']) ? $_POST['termino'] : null;

// Obtener el número total de usuarios
$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM jugador")->fetchColumn();

// Definir el límite de usuarios por página
$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $limite;

// Llamar a la función para obtener los usuarios
$usuarios = listarUsuarios($pdo, $limite, $offset, $termino);

// Calcular el número total de páginas
$totalPaginas = ceil($totalUsuarios / $limite);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard de Admin</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <h1>Dashboard de Administrador</h1>

    <!-- Formulario de búsqueda -->
    <form method="POST">
        <input type="text" name="termino" placeholder="Buscar por nombre o apodo" value="<?php echo htmlspecialchars($termino); ?>">
        <button type="submit">Buscar</button>
    </form>

    <h2>Lista de Usuarios</h2>
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
                        <a href="editar_usuario.php?id=<?php echo $usuario['id_jugador']; ?>">Editar</a>
                        <form action="eliminar_usuario.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este usuario?');">
                            <input type="hidden" name="id_jugador" value="<?php echo $usuario['id_jugador']; ?>">
                            <button type="submit" style="background:none; border:none; color:red; cursor:pointer;">Eliminar</button>
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

    <a href="salir.php">Salir</a>
</body>

</html>