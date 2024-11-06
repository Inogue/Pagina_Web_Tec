<?php
session_start();

// Función para encriptar el mensaje sumando +1 a cada letra
function encriptar($mensaje) {
    $abecedario = 'abcdefghijklmnopqrstuvwxyza';
    $abecedario_mayus = 'ABCDEFGHIJKLMNOPQRSTUVWXYZA';
    $mensaje_encriptado = '';

    for ($i = 0; $i < strlen($mensaje); $i++) {
        $caracter = $mensaje[$i];
        if (strpos($abecedario, $caracter) !== false) {
            $pos = strpos($abecedario, $caracter);
            $mensaje_encriptado .= $abecedario[$pos + 1];
        } elseif (strpos($abecedario_mayus, $caracter) !== false) {
            $pos = strpos($abecedario_mayus, $caracter);
            $mensaje_encriptado .= $abecedario_mayus[$pos + 1];
        } else {
            $mensaje_encriptado .= $caracter;
        }
    }
    return $mensaje_encriptado;
}

// Función para desencriptar el mensaje restando -1 a cada letra
function desencriptar($mensaje) {
    $abecedario = 'zabcdefghijklmnopqrstuvwxy';
    $abecedario_mayus = 'ZABCDEFGHIJKLMNOPQRSTUVWXY';
    $mensaje_desencriptado = '';

    for ($i = 0; $i < strlen($mensaje); $i++) {
        $caracter = $mensaje[$i];
        if (strpos($abecedario, $caracter) !== false) {
            $pos = strpos($abecedario, $caracter);
            $mensaje_desencriptado .= $abecedario[$pos - 1];
        } elseif (strpos($abecedario_mayus, $caracter) !== false) {
            $pos = strpos($abecedario_mayus, $caracter);
            $mensaje_desencriptado .= $abecedario_mayus[$pos - 1];
        } else {
            $mensaje_desencriptado .= $caracter;
        }
    }
    return $mensaje_desencriptado;
}

// Verificar si se ha enviado el formulario para encriptar o desencriptar
$response = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['mensaje'])) {
        $mensaje = $_POST['mensaje'];
        $response['mensaje_encriptado'] = encriptar($mensaje);
    } elseif (isset($_POST['mensaje_encriptado'])) {
        $mensaje_cifrado = $_POST['mensaje_encriptado'];
        $response['mensaje_desencriptado'] = desencriptar($mensaje_cifrado);
    }
    echo json_encode($response);
    exit;
}

// Simular una sesión con un nombre de usuario si no existe
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = 'UsuarioDemo';
}

// Cerrar sesión
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Determinar el idioma
$idioma = 'es'; // Español por defecto
if (isset($_GET['idioma'])) {
    $idioma = $_GET['idioma'];
}

// Cambiar los textos según el idioma
$titulo = $idioma == 'es' ? 'Encriptador y Desencriptador' : 'Kryptering og Dekryptering';
$mensajePlaceholder = $idioma == 'es' ? 'Escribe tu mensaje' : 'Skriv din melding';
$mensajeEncriptado = $idioma == 'es' ? 'Mensaje encriptado:' : 'Kryptert melding:';
$mensajeDesencriptado = $idioma == 'es' ? 'Mensaje desencriptado:' : 'Dekryptert melding:';
$cerrarSesion = $idioma == 'es' ? 'Cerrar sesión' : 'Logg ut';
?>

<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_1.css">
    <title><?php echo $titulo; ?></title>
    <script>
        async function enviarFormulario(event, tipo) {
            event.preventDefault();
            const formData = new FormData(event.target.form);
            const response = await fetch("", {
                method: "POST",
                body: formData
            });
            const result = await response.json();
            if (tipo === "encriptar" && result.mensaje_encriptado) {
                document.getElementById("resultado-encriptado").textContent = result.mensaje_encriptado;
            } else if (tipo === "desencriptar" && result.mensaje_desencriptado) {
                document.getElementById("resultado-desencriptado").textContent = result.mensaje_desencriptado;
            }
        }
    </script>
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
        <h1><?php echo $titulo; ?></h1>

        <!-- Formulario para encriptar el mensaje -->
        <form id="form-encriptar">
            <label for="mensaje"><?php echo $mensajePlaceholder; ?>:</label>
            <input type="text" id="mensaje" name="mensaje" required>
            <button type="button" onclick="enviarFormulario(event, 'encriptar')">Encriptar</button>
        </form>
        <h2><?php echo $mensajeEncriptado; ?></h2>
        <p id="resultado-encriptado"></p>

        <!-- Formulario para desencriptar el mensaje -->
        <form id="form-desencriptar">
            <label for="mensaje_encriptado"><?php echo $mensajeDesencriptado; ?>:</label>
            <input type="text" id="mensaje_encriptado" name="mensaje_encriptado" required>
            <button type="button" onclick="enviarFormulario(event, 'desencriptar')">Desencriptar</button>
        </form>
        <h2><?php echo $mensajeDesencriptado; ?></h2>
        <p id="resultado-desencriptado"></p>

        <!-- Botón de cerrar sesión -->
        <a href="?logout=true" class="logout-btn"><?php echo $cerrarSesion; ?></a>
    </div>
</body>
</html>
