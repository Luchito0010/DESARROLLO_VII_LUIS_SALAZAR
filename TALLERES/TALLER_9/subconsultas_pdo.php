<?php
require_once "config_pdo.php"; 

try {
    // 1. Productos que tienen un precio mayor al promedio de su categoría
    $sql = "SELECT p.nombre, p.precio, c.nombre as categoria,
            (SELECT AVG(precio) FROM productos WHERE categoria_id = p.categoria_id) as promedio_categoria
            FROM productos p
            JOIN categorias c ON p.categoria_id = c.id
            WHERE p.precio > (
                SELECT AVG(precio)
                FROM productos p2
                WHERE p2.categoria_id = p.categoria_id
            )";

    $stmt = $conn->query($sql);
    echo "<h3>Productos con precio mayor al promedio de su categoría:</h3>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Producto: {$row['nombre']}, Precio: {$row['precio']}, ";
        echo "Categoría: {$row['categoria']}, Promedio categoría: {$row['promedio_categoria']}<br>";
    }

    // 2. Clientes con compras superiores al promedio
    $sql = "SELECT c.nombre, c.email,
            (SELECT SUM(total) FROM ventas WHERE cliente_id = c.id) as total_compras,
            (SELECT AVG(total) FROM ventas) as promedio_ventas
            FROM clientes c
            WHERE (
                SELECT SUM(total)
                FROM ventas
                WHERE cliente_id = c.id
            ) > (
                SELECT AVG(total)
                FROM ventas
            )";

    $stmt = $conn->query($sql);
    echo "<h3>Clientes con compras superiores al promedio:</h3>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Cliente: {$row['nombre']}, Total compras: {$row['total_compras']}, ";
        echo "Promedio general: {$row['promedio_ventas']}<br>";
    }

    // Productos no vendidos
    $sql = "SELECT * FROM productos WHERE id NOT IN (SELECT producto_id FROM detalles_venta)";
    $stmt = $conn->query($sql);
    echo "<h3>Productos no vendidos:</h3>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: " . $row['id'] . " - Nombre: " . $row['nombre'] . "<br>";
    }

    // Listado de categorías
    $sql = "SELECT c.nombre AS categoria, 
                   COUNT(p.id) AS numero_productos, 
                   SUM(p.stock * p.precio) AS valor_total_inventario
            FROM categorias c
            JOIN productos p ON c.id = p.categoria_id
            GROUP BY c.nombre";
    
    $stmt = $conn->query($sql);
    echo "<h3>Categorías con número de productos y valor total del inventario:</h3>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo " {$row['numero_productos']}, {$row['categoria']}, ";
        echo "{$row['valor_total_inventario']}<br>";
    }

    // Clientes que han comprado productos
    $sql = "SELECT c.nombre 
            FROM clientes c
            JOIN ventas v ON v.cliente_id = c.id";
    
    $stmt = $conn->query($sql);
    echo "<h3>Clientes que han comprado todos los productos:</h3>";
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "Nombre: {$row['nombre']}<br>";
        }
    }

    // Porcentaje de ventas
    $sql = "SELECT p.id AS producto_id, 
                    p.nombre AS producto_nombre,
                    IFNULL(SUM(dv.cantidad), 0) AS total_vendido,
                    IFNULL((SUM(dv.cantidad) / (SELECT IFNULL(SUM(cantidad), 0) FROM detalles_venta) * 100), 0) AS porcentaje_ventas
             FROM productos p
             LEFT JOIN detalles_venta dv ON p.id = dv.producto_id
             GROUP BY p.id, p.nombre";
    
    $stmt = $conn->query($sql);
    echo "<h3>Porcentaje de ventas de cada producto respecto al total de ventas:</h3>";
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo " - Nombre: " . $row['producto_nombre'] . 
                 " - Total Vendido: " . $row['total_vendido'] . 
                 " - Porcentaje de Ventas: " . number_format($row['porcentaje_ventas'], 2) . "%<br>";
        }
    } else {
        echo "No se encontraron productos en el inventario.";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Cierre de la conexión
$conn = null;
?>
