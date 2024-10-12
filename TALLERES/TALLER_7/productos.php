<?php
require_once 'config_sesion.php';

$productos = [
    ['id' => 1, 'nombre' => 'Producto 1', 'precio' => 10],
    ['id' => 2, 'nombre' => 'Producto 2', 'precio' => 15],
    ['id' => 3, 'nombre' => 'Producto 3', 'precio' => 20],
    ['id' => 4, 'nombre' => 'Producto 4', 'precio' => 25],
    ['id' => 5, 'nombre' => 'Producto 5', 'precio' => 30],
];

echo "<h2>Productos</h2><ul>";
foreach ($productos as $producto) {
    echo "<li>{$producto['nombre']} - \${$producto['precio']}
        <a href='agregar_al_carrito.php?id={$producto['id']}'>Agregar al carrito</a>
        </li>";
}

echo" <br>
        <a href='ver_carrito.php?id={$producto['id']}'>Ver Carrito</a>";

echo "</ul>";
?>
