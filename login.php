<?php
// Iniciar sesión
session_start();

// Configuración de conexión a la base de datos
$host = "localhost"; // Cambia según tu configuración
$dbname = "paginaweb"; // Nombre de la base de datos
$user = "root"; // Usuario de MySQL
$password = ""; // Contraseña de MySQL

// Conectar a la base de datos
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

// Comprobar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Buscar el usuario en la base de datos
    $stmt = $conn->prepare("SELECT id_user, hash_password FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si el usuario existe y la contraseña es correcta
    if ($user && password_verify($password, $user['hash_password'])) {
        // Iniciar sesión
        $_SESSION['username'] = $username;
        $_SESSION['id_user'] = $user['id_user'];
        header("Location: dashboard.php"); // Redirigir a la página principal
        exit();
    } else {
        // Mensaje de error si las credenciales no coinciden
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            
            <input type="submit" value="Iniciar sesión">
        </form>
    </div>
</body>
</html>
