<?php
session_start();

$conn = new mysqli("mysql_despensa", "root", "pr0s1n3k1", "despensa");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$id_stock = $_POST['id_stock'];

// Obtener el nombre del producto antes de eliminar
$consulta = $conn->query("SELECT producto.nombre 
                          FROM stock 
                          JOIN producto ON stock.id_producto = producto.id 
                          WHERE stock.id = $id_stock");

$nombre = $consulta->fetch_assoc()["nombre"];
$usuario = $_SESSION["usuario"];

// Eliminar del stock
$stmt = $conn->prepare("DELETE FROM stock WHERE id = ?");
$stmt->bind_param("i", $id_stock);
$stmt->execute();
$stmt->close();

// Registrar en historial
$stmt2 = $conn->prepare("INSERT INTO historial (usuario, accion, producto) VALUES (?, 'sacar', ?)");
$stmt2->bind_param("ss", $usuario, $nombre);
$stmt2->execute();
$stmt2->close();

$conn->close();
header("Location: stock.php");
exit();
