<?php
session_start();
if (isset($_SESSION["usuario"])) {
    header("Location: index.php"); // Redirige a index, no a login otra vez
    exit();
}

$conn = new mysqli("mysql_despensa", "root", "pr0s1n3k1", "despensa");
if ($conn->connect_error) {
    die("conexion fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    $stmt = $conn->prepare("SELECT clave FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($clave_hash);
    $stmt->fetch();

    if (password_verify($clave, $clave_hash)) {
        $_SESSION["usuario"] = $usuario;
        header("Location: index.php");
        exit();
    } else {
        echo "Usuario o clave incorrectos.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css?v=3">
</head>
<body class="login-body">
    <div class="login-container">
        <h2>Iniciar sesión</h2>
        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="clave" placeholder="Clave" required>
            <button type="submit" class="btn">Entrar</button>
        </form>
    </div>
</body>
</html>

