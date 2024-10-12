<?php
require_once 'config_sesion.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Elimina el producto del carrito
    if (isset($_SESSION['carrito'][$id])) {
        unset($_SESSION['carrito'][$id]);
    }
}

header('Location: ver_carrito.php');
exit();
?>
