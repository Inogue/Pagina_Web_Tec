<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar si el usuario ya existe
    $stmt = $conn->prepare('SELECT id_user FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "El usuario ya existe.";
    } else {
        // Encriptar la contraseña
        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        // Insertar usuario en la base de datos
        $stmt = $conn->prepare('INSERT INTO users (username, hash_password) VALUES (?, ?)');
        $stmt->bind_param('ss', $username, $hash_password);

        if ($stmt->execute()) {
            header('Location: register_conf.php');
            exit(); // Importante para asegurar que el script se detenga después de la redirección
        } else {
            echo "Error al registrar usuario.";
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Registro</title>
</head>
<body>
    <div class="container">
    <h2>Registro</h2>
    <form method="POST" action="register.php">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Registrar">
        <a href="login.php">¿Tienes cuenta? Inicia sesión</a>

    </form>
    </div>
</body>
</html>
