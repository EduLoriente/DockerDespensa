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

<?php
// Conexión
$conn = new mysqli("mysql_despensa", "root", "pr0s1n3k1", "despensa");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener categorías
$categorias = $conn->query("SELECT DISTINCT categoria FROM producto ORDER BY categoria");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Stock</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script>
        function cargarProductos() {
            var categoria = document.getElementById("categoria").value;
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "stock_categoria.php?categoria=" + categoria, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("productos").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>

<div class="menu">
    <a href="index.php">Volver</a>
    <a href="producto.php">Productos</a>
    <a href="lista_compra.php">Lista Compra</a>
    <a href="logout.php" class="boton-salir">Salir</a>
</div>

<h1>Stock</h1>

<div class="nuevo-producto">
    <h2>Añadir producto al stock</h2>
    <form method="POST" action="añadir_stock.php">
        <select name="categoria" id="categoria" onchange="cargarProductos()" required>
            <option value="">Selecciona categoría</option>
            <?php while ($row = $categorias->fetch_assoc()) {
                echo "<option value='".$row["categoria"]."'>".$row["categoria"]."</option>";
            } ?>
        </select>

        <select name="id_producto" id="productos" required>
            <option value="">Primero elige categoría</option>
        </select>

        <input type="date" name="fecha_caducidad" required>

        <button type="submit" class="btn">Añadir al stock</button>
    </form>
</div>

<div class="nuevo-producto">
    <h2>Productos actualmente en stock</h2>
    <div class="categorias-grid">
        <?php
        $categorias_stock = $conn->query("
            SELECT DISTINCT producto.categoria 
            FROM stock 
            JOIN producto ON stock.id_producto = producto.id
            ORDER BY producto.categoria
        ");

        while ($cat = $categorias_stock->fetch_assoc()) {
            $categoria = $cat["categoria"];
            echo "<div class='categoria-box'>";
            echo "<h3>" . htmlspecialchars($categoria) . "</h3>";
            echo "<ul>";

            $productos_en_categoria = $conn->prepare("
                SELECT producto.nombre, stock.fecha_caducidad
                FROM stock
                JOIN producto ON stock.id_producto = producto.id
                WHERE producto.categoria = ?
                ORDER BY producto.nombre, stock.fecha_caducidad
            ");
            $productos_en_categoria->bind_param("s", $categoria);
            $productos_en_categoria->execute();
            $resultado = $productos_en_categoria->get_result();

            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($row["nombre"]) . 
                         " <small>(cad: " . htmlspecialchars($row["fecha_caducidad"]) . ")</small></li>";
                }
            } else {
                echo "<li><em>Sin productos</em></li>";
            }

            echo "</ul></div>";
        }
        ?>
    </div>
</div>



<div class="nuevo-producto">
    <h2>Sacar producto del stock</h2>
    <form method="POST" action="sacar_stock.php">
        <select name="id_stock" required>
            <option value="">Elige producto en stock</option>
            <?php
            $stock = $conn->query("SELECT stock.id, producto.nombre, stock.fecha_caducidad 
                                   FROM stock 
                                   JOIN producto ON stock.id_producto = producto.id
                                   ORDER BY stock.fecha_caducidad");
            while ($row = $stock->fetch_assoc()) {
                echo "<option value='".$row["id"]."'>".$row["nombre"]." (cad: ".$row["fecha_caducidad"].")</option>";
            }
            ?>
        </select>

        <button type="submit" class="btn">Sacar del stock</button>
    </form>
</div>

</body>
</html>
