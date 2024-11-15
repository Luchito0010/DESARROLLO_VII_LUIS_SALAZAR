<?php
// Incluir la configuración de conexión
require_once "config_mysqli.php"; // Asegúrate de que este archivo esté en la misma carpeta o ajusta la ruta

// Función para verificar y registrar cambios de precio
function verificarCambiosPrecio($mysqli, $producto_id, $nuevo_precio) {
    try {
        // Actualizar precio
        $stmt = $mysqli->prepare("UPDATE productos SET precio = ? WHERE id = ?");
        $stmt->bind_param("di", $nuevo_precio, $producto_id); // "di" indica un double y un int
        $stmt->execute();

        // Verificar log de cambios
        $stmt = $mysqli->prepare("SELECT * FROM historial_precios WHERE producto_id = ? ORDER BY fecha_cambio DESC LIMIT 1");
        $stmt->bind_param("i", $producto_id); // "i" indica un int
        $stmt->execute();
        $result = $stmt->get_result();
        $log = $result->fetch_assoc();

        echo "<h3>Cambio de Precio Registrado:</h3>";
        echo "Precio anterior: $" . $log['precio_anterior'] . "<br>";
        echo "Precio nuevo: $" . $nuevo_precio . "<br>"; // Mostrar el nuevo precio
        echo "Fecha del cambio: " . $log['fecha_cambio'] . "<br>";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Función para verificar y registrar movimientos de inventario
function verificarMovimientoInventario($mysqli, $producto_id, $nueva_cantidad) {
    try {
        // Actualizar stock
        $stmt = $mysqli->prepare("UPDATE productos SET stock = ? WHERE id = ?");
        $stmt->bind_param("ii", $nueva_cantidad, $producto_id); // "ii" indica dos enteros
        $stmt->execute();

        // Verificar movimientos de inventario
        $stmt = $mysqli->prepare("SELECT * FROM movimientos_inventario WHERE producto_id = ? ORDER BY fecha_movimiento DESC LIMIT 1");
        $stmt->bind_param("i", $producto_id); // "i" indica un int
        $stmt->execute();
        $result = $stmt->get_result();
        $movimiento = $result->fetch_assoc();

        echo "<h3>Movimiento de Inventario Registrado:</h3>";
        echo "Tipo de movimiento: " . $movimiento['tipo_movimiento'] . "<br>";
        echo "Cantidad: " . $movimiento['cantidad'] . "<br>";
        echo "Stock anterior: " . $movimiento['stock_anterior'] . "<br>";
        echo "Stock nuevo: " . $nueva_cantidad . "<br>"; // Mostrar la nueva cantidad
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Probar los triggers
// Cambia los ID y los valores según tus datos reales en la base de datos
$producto_id = 1; // ID del producto que deseas actualizar
$nuevo_precio = 999.99; // Nuevo precio del producto
$nueva_cantidad = 15; // Nueva cantidad de stock del producto

// Verificar cambios de precio y movimientos de inventario
verificarCambiosPrecio($mysqli, $producto_id, $nuevo_precio);
verificarMovimientoInventario($mysqli, $producto_id, $nueva_cantidad);

// Cerrar la conexión
$mysqli->close();
?>
