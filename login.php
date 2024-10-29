<?php
session_start();
require 'config.php';

// Inicializar variable para el mensaje de error
$error_message = '';

// Lógica para cambiar de idioma
$idioma = 'es'; // Español por defecto
if (isset($_GET['idioma'])) {
    $idioma = $_GET['idioma'];
}

// Cambia los textos según el idioma
$titulo = $idioma == 'es' ? 'Iniciar sesión' : 'Logg inn';
$placeholderUsuario = $idioma == 'es' ? 'Usuario' : 'Brukernavn';
$placeholderContraseña = $idioma == 'es' ? 'Contraseña' : 'Passord';
$mensajeRegistro = $idioma == 'es' ? '¿No tienes cuenta? Regístrate' : 'Har du ikke en konto? Registrer deg';
$mensajeErrorContraseña = $idioma == 'es' ? "Contraseña incorrecta." : "Feil passord.";
$mensajeErrorUsuario = $idioma == 'es' ? "Usuario no encontrado." : "Bruker ikke funnet.";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT id_user, hash_password FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['hash_password'])) {
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit();
        } else {
            $error_message = $mensajeErrorContraseña;
        }
    } else {
        $error_message = $mensajeErrorUsuario;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="style_1.css">
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

        <?php if ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php?idioma=<?php echo $idioma; ?>">
            <input type="text" id="username" name="username" placeholder="<?php echo $placeholderUsuario; ?>" required>
            <input type="password" id="password" name="password" placeholder="<?php echo $placeholderContraseña; ?>" required>
            <input type="submit" value="<?php echo $titulo; ?>">
        </form>
        <a href="register.php"><?php echo $mensajeRegistro; ?></a>
	<!-- Enlace para recuperar contraseña -->
        <a href="forgot_password.php" class="forgot-password">¿Has olvidado tu contraseña?</a>
    </div>

</body>
</html>
