<?php
require_once 'config_sesion.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Agrega el producto al carrito
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Si ya está en el carrito, incrementa la cantidad
    if (isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id]++;
    } else {
        $_SESSION['carrito'][$id] = 1;
    }
}

header('Location: productos.php');
exit();
?>
