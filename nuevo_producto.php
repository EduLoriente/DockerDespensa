<?
$conn = new mysqli("mysql_despensa", "root", "pr0s1n3k1", "despensa");
if ($conn->connect_error) {
    die("Conesion fallida: " . $conn->connect_error);
}

$nombre = $_POST['nombre'];
$categoria = $_POST['categoria'];

$stmt = $conn->prepare("insert into producto (nombre, categoria) values (?,?)");
$stmt->bind_param("ss", $nombre, $categoria);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: producto.php");
exit();
?>