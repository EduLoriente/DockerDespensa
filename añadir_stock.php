<?php
session_start();

$conn = new mysqli("mysql_despensa", "root", "pr0s1n3k1", "despensa");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$id_producto = $_POST['id_producto'];
$fecha_caducidad = $_POST['fecha_caducidad'];

// 1. Insertar en stock
$stmt = $conn->prepare("INSERT INTO stock (id_producto, fecha_caducidad) VALUES (?, ?)");
$stmt->bind_param("is", $id_producto, $fecha_caducidad);
$stmt->execute();
$stmt->close();

// 2. Obtener nombre del producto
$consulta = $conn->query("SELECT nombre FROM producto WHERE id = $id_producto");
$nombre = $consulta->fetch_assoc()["nombre"];

// 3. Insertar en historial
$usuario = $_SESSION["usuario"];
$stmt2 = $conn->prepare("INSERT INTO historial (usuario, accion, producto) VALUES (?, 'añadir', ?)");
$stmt2->bind_param("ss", $usuario, $nombre);
$stmt2->execute();
$stmt2->close();

$conn->close();
header("Location: stock.php");
exit();
