<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<?php //conexion
$conn = new mysqli("mysql_despensa","root","pr0s1n3k1", "despensa");
if ($conn->connect_error) {
    die("Conexion fallida: " . $conn->connect_error);
}
//sacar datos por categorias
$result =$conn->query("select * from producto order by categoria, nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prodcutos</title>
    <link rel="stylesheet" type="text/css" href="style.css?v=2">
</head>
<body>
    <div class="menu">
        <a href="index.php">Volver</a>
        <a href="stock.php">Stock</a>
        <a href="lista_compra.php">Lista Compra</a>
        <a href="historial.php">Historial</a>
        <a href="logout.php" class="boton-salir">Salir</a>
    </div>

    <h1>Productos</h1>

    <div class="nuevo-producto-container">
        <?php
        $categoria_actual = "";
        while($row = $result->fetch_assoc()) {
            if ($categoria_actual != $row["categoria"]) {
                if ($categoria_actual != "") echo "</div>";
                $categoria_actual = $row["categoria"];
                echo "<div class='categoria-columna'>";
                echo "<div class='categoria-titulo'>{$categoria_actual}</div>";
            }
            echo "<div class='producto'>{$row["nombre"]}</div>";
        }
        if ($categoria_actual != "") echo "</div>";
        ?>
    </div>
        <div class="nuevo-producto">
            <h2>Añadir producto</h2>
            <form method="POST" action="nuevo_producto.php">
                <input type="text" name="nombre" placeholder="nombre del producto" required>
                <select name="categoria" required>
                    <option value="">selecciona categoria</option>
                    <option value="proteinas">Proteinas</option>
                    <option value="hidratos">Hidratos</option>
                    <option value="vegetales">Vegetales</option>
                    <option value="menaje">Menaje</option>
                    <option value="caprichos">Caprichos</option>
                </select>
                <button type="submit" class="btn">Añadir</button>
            </form>
        </div>
        <div class="nuevo-producto">
            <h2>Eliminar producto</h2>
            <form method="POST" action="eliminar_producto.php">
                <input type="text" name="nombre" placeholder="nombre exacto del producto" required>
                <button type="submit" class="btn">Eliminar</button>
            </form>
        </div>

</body>
</html>