<?php
require 'config.php'; // Incluir archivo de configuración para la conexión a la base de datos

// Inicializar variables para mensajes de error y éxito
$error_message = '';
$success_message = '';

// Cambia el idioma de forma similar al código de inicio de sesión
$idioma = 'es'; // Español por defecto
if (isset($_GET['idioma'])) {
    $idioma = $_GET['idioma'];
}

// Cambia los textos según el idioma
$titulo = $idioma == 'es' ? 'Registro' : 'Registrering';
$placeholderUsuario = $idioma == 'es' ? 'Usuario' : 'Brukernavn';
$placeholderContraseña = $idioma == 'es' ? 'Contraseña' : 'Passord';
$mensajeExito = $idioma == 'es' ? 'Usuario registrado exitosamente.' : 'Brukeren ble registrert.';
$mensajeErrorUsuario = $idioma == 'es' ? 'El usuario ya existe.' : 'Brukeren eksisterer allerede.';
$mensajeErrorRegistro = $idioma == 'es' ? 'Error al registrar usuario.' : 'Feil ved registrering av bruker.';

// Lógica del formulario de registro
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
        $error_message = $mensajeErrorUsuario;
    } else {
        // Encriptar la contraseña
        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        // Insertar usuario en la base de datos
        $stmt = $conn->prepare('INSERT INTO users (username, hash_password) VALUES (?, ?)');
        $stmt->bind_param('ss', $username, $hash_password);

        if ($stmt->execute()) {
            // Si el registro es exitoso, mostrar mensaje de éxito
            $success_message = $mensajeExito;
        } else {
            // Si hay un error al insertar en la base de datos, mostrar mensaje de error
            $error_message = $mensajeErrorRegistro;
        }
    }
    // Cerrar la consulta
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">
<head>
    <meta charset="UTF-8"> <!-- Aseguramos que la página esté codificada en UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_1.css"> <!-- Enlace a la hoja de estilos -->
    <title><?php echo $titulo; ?></title> <!-- Título de la página -->
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

        <!-- Mostrar mensaje de éxito si existe -->
        <?php if ($success_message): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <!-- Mostrar mensaje de error si existe -->
        <?php if ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Formulario de registro -->
        <form method="POST" action="register.php?idioma=<?php echo $idioma; ?>">
            <label for="username"><?php echo $placeholderUsuario; ?>:</label>
            <input type="text" id="username" name="username" required placeholder="<?php echo $placeholderUsuario; ?>">

            <label for="password"><?php echo $placeholderContraseña; ?>:</label>
            <input type="password" id="password" name="password" required placeholder="<?php echo $placeholderContraseña; ?>">

            <input type="submit" value="<?php echo $titulo; ?>"> <!-- Botón de enviar formulario -->

            <a href="login.php"><?php echo $idioma == 'es' ? '¿Tienes cuenta? Inicia sesión' : 'Har du en konto? Logg inn'; ?></a> <!-- Enlace para iniciar sesión si ya tiene cuenta -->
        </form>
    </div>
</body>
</html>

