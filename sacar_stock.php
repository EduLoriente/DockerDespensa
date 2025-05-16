<?php
$conn = new mysqli("mysql_despensa","root","pr0s1n3k1","despensa");
if ($conn->connect_error) {
    die("conexion fallida: " . $conn->connect_error);
}

$id_stock = $_POST['id_stock'];

$stmt = $conn->prepare("delete from stock where id = ?");
$stmt->bind_param("i", $id_stock);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: stock.php");
exit();
?>