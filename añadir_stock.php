<?php
$conn = new mysqli("mysql_despensa","root","pr0s1n3k1","despensa");
if ($conn->connect_error) {
    die("conexion fallida: " . $conn->connect_error);
}

$id_producto = $_POST['id_producto'];
$fecha_caducidad = $_POST['fecha_caducidad'];

$stmt = $conn->prepare("insert into stock (id_producto, fecha_caducidad) values (?, ?)");
$stmt->bind_param("is", $id_producto, $fecha_caducidad);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: stock.php");
exit();
?>