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

// Cambiar los textos según el idioma
$titulo = $idioma == 'es' ? 'Iniciar sesión' : 'Logg inn';
$placeholderEmail = $idioma == 'es' ? 'Email' : 'E-mail';
$placeholderContraseña = $idioma == 'es' ? 'Contraseña' : 'Passord';
$mensajeRegistro = $idioma == 'es' ? '¿No tienes cuenta? Regístrate' : 'Har du ikke en konto? Registrer deg';
$mensajeErrorContraseña = $idioma == 'es' ? "Contraseña incorrecta." : "Feil passord.";
$mensajeErrorEmail = $idioma == 'es' ? "Correo electrónico no encontrado." : "E-post ikke funnet.";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Usar 'email' en lugar de 'username'
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Buscar el usuario por email
    $stmt = $conn->prepare('SELECT id_user, hash_password FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si se encuentra el correo electrónico
        $row = $result->fetch_assoc();
        // Verificar la contraseña
        if (password_verify($password, $row['hash_password'])) {
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['email'] = $email;  // Guardar el email en la sesión
            header('Location: dashboard.php');
            exit();
        } else {
            $error_message = $mensajeErrorContraseña; // Contraseña incorrecta
        }
    } else {
        $error_message = $mensajeErrorEmail; // Correo no encontrado
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

        <!-- Mostrar mensaje de error si existe -->
        <?php if ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Cambié el campo 'username' por 'email' en el formulario -->
        <form method="POST" action="login.php?idioma=<?php echo $idioma; ?>">
            <input type="email" id="email" name="email" placeholder="<?php echo $placeholderEmail; ?>" required>
            <input type="password" id="password" name="password" placeholder="<?php echo $placeholderContraseña; ?>" required>
            <input type="submit" value="<?php echo $titulo; ?>">
        </form>
        
        <a href="register.php"><?php echo $mensajeRegistro; ?></a>
        <a href="forgot_password.php" class="forgot-password"><?php echo $idioma == 'es' ? '¿Has olvidado tu contraseña?' : 'Har du glemt passordet?'; ?></a>
    </div>

</body>
</html>
