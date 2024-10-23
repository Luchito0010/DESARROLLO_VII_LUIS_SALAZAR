<?php
session_start(); 
$error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    if (($usuario === "profesor" && $contrasena === "12345")) {
        $_SESSION['usuario'] = $usuario;
        setcookie("usuario", $usuario, [
            'expires' => time() + 3600,
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        header("Location: notas.php");
        exit();

    } elseif (($usuario === "lu1s" && $contrasena === "12345")){
        $_SESSION['usuario'] = $usuario;
        setcookie("usuario", $usuario, [
            'expires' => time() + 3600,
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        header("Location: dashboardP.php");
        exit();
    }
    $error = "Usuario o contraseña incorrectos";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Formulario de login</h2>
    <form action="" method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required><br><br>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required><br><br>

        <input type="submit" value="Ingresar"><br>
    </form>
</body>
</html>
