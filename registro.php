<?php
  if (isset($_POST['boton'])){
    $conn = new mysqli("mysql_despensa", "root", "pr0s1n3k1", "despensa");
    if ($conn->connect_error) {
        die("conexion fallida: " . $conn->connect_error);
    }

    $usuario = $_POST['usuario'];
    $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("insert into usuarios (nombre_usuario, clave) values (?, ?)");
    $stmt->bind_param("ss", $usuario, $clave);
    $stmt->execute();

    echo "Usuario registrado correctamente.";
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>registro</title>
    <link rel="stylesheet" type="text/css" href="style.css?v=3">
</head>
<body class="register-body">
    <div class="register-container">
        <h2>Registrar usuario</h2>
        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="clave" placeholder="Clave" required>
            <button type="submit" name="boton">Registrar</button>
        </form>
    </div>
</body>
</html>