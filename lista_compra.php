<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$conn = new mysqli("mysql_despensa","root","pr0s1n3k1","despensa");
if ($conn->connect_error) {
    die("Conexion fallida: " . $conn->connect_error);
}

$resultado = $conn->query("select nombre_producto, fecha_uso from lista_compra order by fecha_uso desc");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de la Compra</title>
    <link rel="stylesheet" type="text/css" href="style.css?v=2">
</head>
<body>
    <div class="menu">
        <a href="index.php">Volver</a>
        <a href="producto.php">Productos</a>
        <a href="stock.php">Stock</a>
        <a href="historial.php">Historial</a>
        <a href="logout.php" class="boton-salir">Salir</a>
    </div>

    <h1>Lista de la Compra</h1>

    <div class="nuevo-producto-container">
        <div class="categoria-columna">
            <div class="categoria-titulo">Productos pendientes</div>
            <?php
            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    $fecha = date("d/m/Y", strtotime($row["fecha_uso"]));
                    echo "<div class='producto'>{$row["nombre_producto"]} <span style='font-size: 14px; color: #555;'>(usado: $fecha)</span></div>";
                }
            } else {
                echo "<div class='producto'>No hay productos en la lista.</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>