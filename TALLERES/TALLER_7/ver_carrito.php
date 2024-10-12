<?php
require_once 'config_sesion.php';

$productos = [
    1 => ['nombre' => 'Producto 1', 'precio' => 10],
    2 => ['nombre' => 'Producto 2', 'precio' => 15],
    3 => ['nombre' => 'Producto 3', 'precio' => 20],
    4 => ['nombre' => 'Producto 4', 'precio' => 25],
    5 => ['nombre' => 'Producto 5', 'precio' => 30],
];

echo "<h2>Carrito de Compras</h2><ul>";
$total = 0;
if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $id => $cantidad) {
        $producto = $productos[$id];
        $subtotal = $producto['precio'] * $cantidad;
        $total += $subtotal;
        echo "<li>{$producto['nombre']} - \${$producto['precio']} x $cantidad = \${$subtotal}
            <a href='eliminar_del_carrito.php?id={$id}'>Eliminar</a>
        </li>";
    }
    echo "</ul><p>Total: \${$total}</p>";
} else {
    echo "<p>El carrito está vacío.</p>";
}
echo "<a href='checkout.php'>Checkout</a>";
?>
