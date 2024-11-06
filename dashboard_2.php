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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Encriptador y Desencriptador</title>
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
    <div class="container">
        <h1>Encriptador y Desencriptador de Mensajes</h1>
        
        <!-- Formulario para encriptar el mensaje -->
        <form id="form-encriptar">
            <label for="mensaje">Escribe tu mensaje:</label>
            <input type="text" id="mensaje" name="mensaje" required>
            <button type="button" onclick="enviarFormulario(event, 'encriptar')">Encriptar</button>
        </form>
        <h2>Mensaje encriptado:</h2>
        <p id="resultado-encriptado"></p>

        <!-- Formulario para desencriptar el mensaje -->
        <form id="form-desencriptar">
            <label for="mensaje_encriptado">Escribe el mensaje encriptado:</label>
            <input type="text" id="mensaje_encriptado" name="mensaje_encriptado" required>
            <button type="button" onclick="enviarFormulario(event, 'desencriptar')">Desencriptar</button>
        </form>
        <h2>Mensaje desencriptado:</h2>
        <p id="resultado-desencriptado"></p>

        <!-- Botón de cerrar sesión -->
        <a href="?logout=true" class="logout-btn">Cerrar sesión</a>
    </div>
</body>
</html>
