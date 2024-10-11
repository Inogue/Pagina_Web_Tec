<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Preparar consulta SQL para evitar SQL Injection
    $stmt = $conn->prepare('SELECT id_user, hash_password FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verificar la contraseña con hash
        if (password_verify($password, $row['hash_password'])) {
            // Iniciar sesión
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');  // Redirigir a dashboard
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Iniciar sesión</h2>
        <form method="POST" action="login.php">
            <input type="text" id="username" name="username" placeholder="Usuario" required>
            <input type="password" id="password" name="password" placeholder="Contraseña" required>
            <input type="submit" value="Iniciar sesión">
        </form>
        <a href="register.php">¿No tienes cuenta? Regístrate</a>
    </div>

</body>
</html>