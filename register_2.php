<?php
require 'config.php'; // Incluir archivo de configuraci�n para la conexi�n a la base de datos

// Inicializar variables para mensajes de error y �xito
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar si el usuario ya existe
    $stmt = $conn->prepare('SELECT id_user FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Si el usuario ya existe, mostrar mensaje de error
        $error_message = "El usuario ya existe.";
    } else {
        // Encriptar la contrase�a
        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        // Insertar usuario en la base de datos
        $stmt = $conn->prepare('INSERT INTO users (username, hash_password) VALUES (?, ?)');
        $stmt->bind_param('ss', $username, $hash_password);

        if ($stmt->execute()) {
            // Si el registro es exitoso, mostrar mensaje de �xito
            $success_message = "Usuario registrado exitosamente.";
        } else {
            // Si hay un error al insertar en la base de datos, mostrar mensaje de error
            $error_message = "Error al registrar usuario.";
        }
    }
    // Cerrar la consulta
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"> <!-- Aseguramos que la p�gina est� codificada en UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> <!-- Enlace a la hoja de estilos -->
    <title>Registro</title> <!-- T�tulo de la p�gina -->
</head>
<body>
    <div class="container">
        <h2>Registro</h2>
        
        <!-- Mostrar mensaje de �xito si existe -->
        <?php if ($success_message): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <!-- Mostrar mensaje de error si existe -->
        <?php if ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        
        <!-- Formulario de registro -->
        <form method="POST" action="register.php">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Contraseña:</label> <!-- Correctamente escrito "Contrase�a" -->
            <input type="password" id="password" name="password" required><br>

            <input type="submit" value="Registrar"> <!-- Bot�n de enviar formulario -->

            <a href="login.php">¿Tienes cuenta? Inicia sesión</a> <!-- Enlace para iniciar sesi�n si ya tiene cuenta -->
        </form>
    </div>
</body>
</html>
