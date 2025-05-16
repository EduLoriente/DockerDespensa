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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PantryDesk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>PantryDesk</h1>
    <div class="menu">
        <a href="producto.php" class="menuboton">Productos</a>
        <a href="stock.php" class="menuboton">Stock</a>
        <a href="lista_compra.php" class="menuboton">Lista Compra</a>
        <a href="logout.php" class="boton-salir">Salir</a>
    </div>
</body>
</html>