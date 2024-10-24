<?php
session_start();

// Función para encriptar el mensaje sumando +1 a cada letra
function encriptar($mensaje) {
    $abecedario = 'abcdefghijklmnopqrstuvwxyza'; // No incluye la ñ
    $abecedario_mayus = 'ABCDEFGHIJKLMNOPQRSTUVWXYZA';
    $mensaje_encriptado = '';

    // Recorrer cada carácter del mensaje
    for ($i = 0; $i < strlen($mensaje); $i++) {
        $caracter = $mensaje[$i];

        // Si es una letra minúscula
        if (strpos($abecedario, $caracter) !== false) {
            $pos = strpos($abecedario, $caracter);
            $mensaje_encriptado .= $abecedario[$pos + 1]; // Suma +1
        }
        // Si es una letra mayúscula
        elseif (strpos($abecedario_mayus, $caracter) !== false) {
            $pos = strpos($abecedario_mayus, $caracter);
            $mensaje_encriptado .= $abecedario_mayus[$pos + 1];
        }
        // Si no es una letra, se mantiene igual
        else {
            $mensaje_encriptado .= $caracter;
        }
    }

    return $mensaje_encriptado;
}

// Función para desencriptar el mensaje restando -1 a cada letra
function desencriptar($mensaje) {
    $abecedario = 'zabcdefghijklmnopqrstuvwxy'; // No incluye la ñ, empieza por 'z'
    $abecedario_mayus = 'ZABCDEFGHIJKLMNOPQRSTUVWXY';
    $mensaje_desencriptado = '';

    // Recorrer cada carácter del mensaje
    for ($i = 0; $i < strlen($mensaje); $i++) {
        $caracter = $mensaje[$i];

        // Si es una letra minúscula
        if (strpos($abecedario, $caracter) !== false) {
            $pos = strpos($abecedario, $caracter);
            $mensaje_desencriptado .= $abecedario[$pos - 1]; // Esto resta -1
        }
        // Si es una letra mayúscula
        elseif (strpos($abecedario_mayus, $caracter) !== false) {
            $pos = strpos($abecedario_mayus, $caracter);
            $mensaje_desencriptado .= $abecedario_mayus[$pos - 1];
        }
        // Si no es una letra, se mantiene igual
        else {
            $mensaje_desencriptado .= $caracter;
        }
    }

    return $mensaje_desencriptado;
}

// Verificar si se ha enviado el formulario para encriptar
$mensaje_encriptado = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mensaje'])) {
    $mensaje = $_POST['mensaje'];
    $mensaje_encriptado = encriptar($mensaje);
}

// Verificar si se ha enviado el formulario para desencriptar
$mensaje_desencriptado = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mensaje_encriptado'])) {
    $mensaje_cifrado = $_POST['mensaje_encriptado'];
    $mensaje_desencriptado = desencriptar($mensaje_cifrado);
}

// Simulamos una sesión con un nombre de usuario si no existe
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = 'UsuarioDemo'; // Este es un nombre de usuario de ejemplo
}

// Si se quiere cerrar sesión
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php"); // Redirigir a la página de login
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Encriptador y Desencriptador</title>
</head>
<body>
    <div class="container">
        <h1>Encriptador y Desencriptador de Mensajes</h1>
        
        <!-- Formulario para encriptar el mensaje -->
        <form action="" method="POST">
            <label for="mensaje">Escribe tu mensaje:</label>
            <input type="text" id="mensaje" name="mensaje" required>
            <button type="submit">Encriptar</button>
        </form>

        <!-- Mostrar el mensaje encriptado -->
        <?php if (!empty($mensaje_encriptado)): ?>
            <h2>Mensaje encriptado:</h2>
            <p class="mensaje-encriptado"><?php echo $mensaje_encriptado; ?></p>
        <?php endif; ?>

        <!-- Formulario para desencriptar el mensaje -->
        <form action="" method="POST">
            <label for="mensaje_encriptado">Escribe el mensaje encriptado:</label>
            <input type="text" id="mensaje_encriptado" name="mensaje_encriptado" required>
            <button type="submit">Desencriptar</button>
        </form>

        <!-- Mostrar el mensaje desencriptado -->
        <?php if (!empty($mensaje_desencriptado)): ?>
            <h2>Mensaje desencriptado:</h2>
            <p class="mensaje-desencriptado"><?php echo $mensaje_desencriptado; ?></p>
        <?php endif; ?>

        <!-- Botón de cerrar sesión -->
        <a href="?logout=true" class="logout-btn">Cerrar sesión</a>
    </div>
</body>
</html>
