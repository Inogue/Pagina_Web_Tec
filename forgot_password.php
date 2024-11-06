<?php
session_start(); // Iniciar sesión
require 'config.php'; // Incluir archivo de configuración para la conexión a la base de datos

$error_message = '';
$success_message = '';

// Inicializar idioma
$idioma = 'es'; // Español por defecto
if (isset($_GET['idioma'])) {
    $idioma = $_GET['idioma'];
}

// Cambia los textos según el idioma
$titulo = $idioma == 'es' ? 'Cambiar Contraseña' : 'Endre Passord';
$mensajeError = $idioma == 'es' ? "El usuario no existe." : "Bruker ikke funnet.";
$mensajeExito = $idioma == 'es' ? "Contraseña actualizada con éxito." : "Passord oppdatert.";
$errorActualizar = $idioma == 'es' ? "Error al actualizar la contraseña." : "Feil ved oppdatering av passord.";

// Lógica para actualizar la contraseña
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];

    // Verificar si el usuario existe
    $stmt = $conn->prepare('SELECT id_user FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        // Usuario encontrado, actualizar la contraseña
        $hash_password = password_hash($new_password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare('UPDATE users SET hash_password = ? WHERE username = ?');
        $stmt->bind_param('ss', $hash_password, $username);

        if ($stmt->execute()) {
            $success_message = $mensajeExito;
            // Redirigir a la página de inicio de sesión
            header("Location: login.php?idioma=$idioma");
            exit();
        } else {
            $error_message = $errorActualizar;
        }
    } else {
        $error_message = $mensajeError;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_1.css">
    <title><?php echo $titulo; ?></title>
</head>
<body>
    <!-- Menú desplegable de cambio de idioma -->
    <div class="language-switcher" id="languuage_options">
        <button class="dropdown-button">
            <?php echo $idioma == 'es' ? 'ESP' : 'NOR'; ?>
        </button>
        <div class="dropdown-content">
            <a href="?idioma=es">
                <img src="flag_es.png" alt="Español" class="flag-icon"> ESP
            </a>
            <a href="?idioma=no">
                <img src="flag_no.png" alt="Norsk" class="flag-icon"> NOR
            </a>
        </div>
    </div>
    <div class="container">
        <h2><?php echo $titulo; ?></h2>

        <!-- Mostrar mensaje de error si existe -->
        <?php if ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Mostrar mensaje de éxito si existe -->
        <?php if ($success_message): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <!-- Formulario para cambiar la contraseña -->
        <form method="POST" action="forgot_password.php?idioma=<?php echo $idioma; ?>">
            <label for="username"><?php echo $idioma == 'es' ? 'Usuario:' : 'Brukernavn:'; ?></label>
            <input type="text" id="username" name="username" required><br>

            <label for="new_password"><?php echo $idioma == 'es' ? 'Nueva Contraseña:' : 'Nytt Passord:'; ?></label>
            <input type="password" id="new_password" name="new_password" required><br>

            <input type="submit" value="<?php echo $titulo; ?>">
        </form>
        <p><?php echo $idioma == 'es' ? 'Después de actualizar tu contraseña, serás redirigido a la página de inicio de sesión.' : 'Etter at du har oppdatert passordet, vil du bli omdirigert til innloggingssiden.'; ?></p>
    </div>
</body>
</html>

