<?php
function leerInventario($archivo) {
    $contenido = file_get_contents($archivo);
    return json_decode($contenido, true);
}

function ordenarPorNombre(&$inventario) {
    usort($inventario, function($a, $b) {
        return strcmp($a['nombre'], $b['nombre']);
    });
}

function mostrarResumenInventario($inventario) {
    echo "Resumen del Inventario:\n";
    foreach ($inventario as $producto) {
        echo "Nombre: " . $producto['nombre'] . "\n";
        echo "Precio: $" . number_format($producto['precio'], 2) . "\n";
        echo "Cantidad: " . $producto['cantidad'] . "\n";
        echo "-------------------\n";
    }
}

function calcularValorTotal($inventario) {
    $valorTotal = array_sum(array_map(function($producto) {
        return $producto['precio'] * $producto['cantidad'];
    }, $inventario));
    
    return number_format($valorTotal, 2);
}

function generarInformeStockBajo($inventario) {
    $productosBajos = array_filter($inventario, function($producto) {
        return $producto['cantidad'] < 5;
    });
    
    if (empty($productosBajos)) {
        echo "No hay productos con stock bajo.\n";
    } else {
        echo "Informe de Productos con Stock Bajo:\n";
        foreach ($productosBajos as $producto) {
            echo "Nombre: " . $producto['nombre'] . "\n";
            echo "Cantidad: " . $producto['cantidad'] . "\n";
            echo "-------------------\n";
        }
    }
}

// Incluimos las funciones
include __DIR__ . 'C:\laragon\www\TALLERES\TALLER_2\funciones.php';

// Definimos el archivo JSON
$archivo = 'inventario.json';

// Leemos el inventario
$inventario = leerInventario($archivo);

// Ordenamos el inventario por nombre
ordenarPorNombre($inventario);

// Mostramos el resumen del inventario
mostrarResumenInventario($inventario);

// Calculamos el valor total del inventario
$valorTotal = calcularValorTotal($inventario);
echo "Valor Total del Inventario: $" . $valorTotal . "\n";

// Generamos el informe de productos con stock bajo
generarInformeStockBajo($inventario);

