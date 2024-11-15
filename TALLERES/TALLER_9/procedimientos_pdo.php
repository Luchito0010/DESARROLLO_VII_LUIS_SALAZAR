<?php
require_once "config_pdo.php";

function registrarVenta($conn, $cliente_id, $producto_id, $cantidad) {
    $query = "CALL sp_registrar_venta(?, ?, ?, @venta_id)";
    $stmt = $conn->prepare($query);
    
    try {
        $stmt->execute([$cliente_id, $producto_id, $cantidad]);
        $result = $conn->query("SELECT @venta_id as venta_id");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $venta_id = $row['venta_id'];
        echo "Venta registrada con éxito. ID de venta: " . $venta_id . "<br>";
        return $venta_id;
    } catch (PDOException $e) {
        echo "Error al registrar la venta: " . $e->getMessage() . "<br>";
    }
    
    return null;
}

function obtenerEstadisticasCliente($conn, $cliente_id) {
    $query = "CALL sp_estadisticas_cliente(?)";
    $stmt = $conn->prepare($query);
    
    if ($stmt->execute([$cliente_id])) {
        $estadisticas = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h3>Estadísticas del Cliente</h3>";
        echo "Nombre: " . $estadisticas['nombre'] . "<br>";
        echo "Membresía: " . $estadisticas['nivel_membresia'] . "<br>";
        echo "Total compras: " . $estadisticas['total_compras'] . "<br>";
        echo "Total gastado: $" . $estadisticas['total_gastado'] . "<br>";
        echo "Promedio de compra: $" . $estadisticas['promedio_compra'] . "<br>";
        echo "Últimos productos: " . $estadisticas['ultimos_productos'] . "<br>";
    }
}

function procesarDevolucion($conn, $venta_id, $producto_id, $cantidad) {
    $query = "CALL sp_procesar_devolucion(?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    try {
        $stmt->execute([$venta_id, $producto_id, $cantidad]);
        echo "Devolución procesada con éxito.<br>";
    } catch (PDOException $e) {
        echo "Error al procesar la devolución: " . $e->getMessage() . "<br>";
    }
}

function aplicarDescuento($conn, $cliente_id) {
    $query = "CALL sp_aplicar_descuento(?)";
    
    $stmt = $conn->prepare($query);
    if ($stmt) {
            echo "Descuento aplicado con éxito.<br>"; 
    }
}


function generarReporteBajoStock($conn) {
    $query = "SELECT id, nombre, stock FROM productos WHERE stock < 10";
    $stmt = $conn->prepare($query);
    
    if ($stmt->execute()) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "Producto ID: " . $row['id'] . " - Nombre: " . $row['nombre'] . " - Stock: " . $row['stock'] . "<br>";
        }
    }
}

function calcularComision($conn, $venta_id) {
    $query = "SELECT total FROM ventas WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt->execute([$venta_id])) {
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comision = $row['total'] * 0.1;
            echo "Comisión calculada: $" . $comision . "<br>";
        } 
}
}

$venta_id = registrarVenta($conn, 1, 1, 2);
obtenerEstadisticasCliente($conn, 1);
procesarDevolucion($conn, 1, 1, 1);
aplicarDescuento($conn, 1);
generarReporteBajoStock($conn);

if ($venta_id) {
    calcularComision($conn, $venta_id);
}

$conn = null;
?>
