<?php
$conn = new mysqli("mysql_despensa","root","pr0s1n3k1","despensa");
if ($conn->connect_error) {
    die("conexion fallida: " . $conn->connect_error);
}

$categoria = $_GET['categoria'];
$resultado = $conn->query("select id, nombre from producto where categoria='$categoria' order by nombre");

echo "<option value=''>Selecciona producto</option>";
while ($row = $resultado->fetch_assoc()) {
    echo "<option value='".$row["id"]."'>".$row["nombre"]."</option>";
}
?>