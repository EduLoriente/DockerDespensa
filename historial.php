<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("mysql_despensa", "root", "pr0s1n3k1", "despensa");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener filtros del formulario
$filtro_usuario = $_GET['usuario'] ?? '';
$filtro_accion = $_GET['accion'] ?? '';

// Construir consulta
$consulta = "SELECT usuario, accion, producto, fecha FROM historial WHERE 1=1";

if ($filtro_usuario !== '') {
    $consulta .= " AND usuario = '" . $conn->real_escape_string($filtro_usuario) . "'";
}
if ($filtro_accion !== '') {
    $consulta .= " AND accion = '" . $conn->real_escape_string($filtro_accion) . "'";
}
$consulta .= " ORDER BY fecha DESC";

$resultado = $conn->query($consulta);

// Obtener usuarios únicos para el select
$usuarios = $conn->query("SELECT DISTINCT usuario FROM historial ORDER BY usuario");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas</title>
    <link rel="stylesheet" href="style.css?v=4">
</head>
<body>
    <div class="menu">
        <a href="index.php">Volver</a>
        <a href="stock.php">Stock</a>
        <a href="producto.php">Productos</a>
        <a href="lista_compra.php">Lista Compra</a>
        <a href="logout.php" class="boton-salir">Salir</a>
    </div>

    <h1>Estadísticas</h1>

    <div class="nuevo-producto">
        <form method="GET">
            <select name="usuario">
                <option value="">Todos los usuarios</option>
                <?php
                while ($row = $usuarios->fetch_assoc()) {
                    $selected = ($filtro_usuario == $row['usuario']) ? "selected" : "";
                    echo "<option value='{$row['usuario']}' $selected>{$row['usuario']}</option>";
                }
                ?>
            </select>

            <select name="accion">
                <option value="">Todas las acciones</option>
                <option value="añadir" <?= $filtro_accion == 'añadir' ? 'selected' : '' ?>>Añadir</option>
                <option value="sacar" <?= $filtro_accion == 'sacar' ? 'selected' : '' ?>>Sacar</option>
            </select>

            <button type="submit" class="btn">Filtrar</button>
        </form>
    </div>

    <div class="nuevo-producto-container">
        <div class="categoria-columna">
            <div class="categoria-titulo">Resultados</div>
            <?php
            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    $fecha = date("d/m/Y H:i", strtotime($row["fecha"]));
                    echo "<div class='producto'><strong>{$row["usuario"]}</strong> {$row["accion"]} <em>{$row["producto"]}</em> el $fecha</div>";
                }
            } else {
                echo "<div class='producto'>No hay resultados.</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
