<?php
$conn = new mysqli("mysql_despensa", "root", "pr0s1n3k1", "despensa");
if ($conn->connect_error) {
    die("conexion fallida: " . $conn->connect_error);
}

$nombre = $_POST['nombre'];

$stmt = $conn->prepare("delete from producto where nombre = ?");
$stmt->bind_param("s", $nombre);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: producto.php");
exit();
?>