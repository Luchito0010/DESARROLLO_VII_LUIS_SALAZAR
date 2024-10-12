<?php
require_once 'config_sesion.php';
require_once 'ver_carrito.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Simula el proceso de compra
    $_SESSION['carrito'] = [];

    // Guarda el nombre del usuario en una cookie por 24 horas
    if (!empty($_POST['nombre'])) {
        setcookie('nombre_usuario', htmlspecialchars($_POST['nombre']), time() + 86400, '', '', true, true);
    }

    echo "<h2>Compra finalizada</h2>";
    echo "<p>Gracias por tu compra, {$_POST['nombre']}!</p>";
    echo "<p>Total: \${$_POST['total']}</p>";
    echo "<a href='productos.php'>Volver a productos</a>";
    echo "<br> 
        <a href=logout.php>cerrar sesion</a>";
} else {
    echo "<h2>Checkout</h2>";
    echo "<form method='POST' action='checkout.php'>";
    echo "<label for='nombre'>Nombre: </label>";
    echo "<input type='text' name='nombre' required>";
    echo "<input type='hidden' name='total' value='{$total}'>";
    echo "<button type='submit'>Finalizar Compra</button>";
    echo "</form>";
}

if(!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

?>
