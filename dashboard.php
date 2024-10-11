<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Dashboard</title>
</head>
<body>
    <div class="container">
    <h1>Bienvenido, <?php echo $_SESSION['username']; ?>!</h1>
    <a href="logout.php">Cerrar sesión</a>
    </div>
</body>
</html>
