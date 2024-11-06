<?php
require 'config.php'; // Incluir archivo de configuración para la conexión a la base de datos

// Inicializar variables para mensajes de error y éxito
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar si el usuario existe
    $stmt = $conn->prepare('SELECT id_user, hash_password FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        // Si el usuario existe, verificar la contraseña
        $stmt->bind_result($id_user, $hash_password);
        $stmt->fetch();

        if (password_verify($password, $hash_password)) {
            // Iniciar sesión
            session_start();
            $_SESSION['username'] = $username;
            header('Location: dashboard.php'); // Redirigir al usuario a la página de dashboard
            exit();
        } else {
            $error_message = "Contraseña incorrecta.";
        }
    } else {
        $error_message = "El usuario no existe.";
    }
    // Cerrar la consulta
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Iniciar Sesión</title>
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>

        <!-- Mostrar mensaje de error si existe -->
        <?php if ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Formulario de inicio de sesión -->
        <form method="POST" action="login.php">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required><br>

            <input type="submit" value="Iniciar Sesión"><!-- Botón de enviar formulario -->
            <a href="register.php">¿No tienes cuenta? Regístrate</a><!-- Enlace para registrarse -->

            <!-- Enlace para recuperar contraseña -->
            <a href="forgot_password.php" class="forgot-password">¿Has olvidado tu contraseña?</a>
        </form>
    </div>
</body>
</html>

