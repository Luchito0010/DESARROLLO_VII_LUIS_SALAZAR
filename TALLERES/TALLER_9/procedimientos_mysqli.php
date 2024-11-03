<?php
require_once "config_mysqli.php";

function registrarVenta($conn, $cliente_id, $producto_id, $cantidad) {
    $query = "CALL sp_registrar_venta(?, ?, ?, @venta_id)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iii", $cliente_id, $producto_id, $cantidad);
    
    try {
        mysqli_stmt_execute($stmt);
        $result = mysqli_query($conn, "SELECT @venta_id as venta_id");
        $row = mysqli_fetch_assoc($result);
        $venta_id = $row['venta_id'];
        echo "Venta registrada con éxito. ID de venta: " . $venta_id . "<br>";
        return $venta_id;
    } catch (Exception $e) {
        echo "Error al registrar la venta: " . $e->getMessage() . "<br>";
    }
    
    mysqli_stmt_close($stmt);
    return null;
}

function obtenerEstadisticasCliente($conn, $cliente_id) {
    $query = "CALL sp_estadisticas_cliente(?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $cliente_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $estadisticas = mysqli_fetch_assoc($result);
        
        echo "<h3>Estadísticas del Cliente</h3>";
        echo "Nombre: " . $estadisticas['nombre'] . "<br>";
        echo "Membresía: " . $estadisticas['nivel_membresia'] . "<br>";
        echo "Total compras: " . $estadisticas['total_compras'] . "<br>";
        echo "Total gastado: $" . $estadisticas['total_gastado'] . "<br>";
        echo "Promedio de compra: $" . $estadisticas['promedio_compra'] . "<br>";
        echo "Últimos productos: " . $estadisticas['ultimos_productos'] . "<br>";
    }
    
    mysqli_stmt_close($stmt);
}

function procesarDevolucion($conn, $venta_id, $producto_id, $cantidad) {
    $query = "CALL sp_procesar_devolucion(?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iii", $venta_id, $producto_id, $cantidad);
    
    try {
        mysqli_stmt_execute($stmt);
        echo "Devolución procesada con éxito.<br>";
    } catch (Exception $e) {
        echo "Error al procesar la devolución: " . $e->getMessage() . "<br>";
    }
    
    mysqli_stmt_close($stmt);
}

function aplicarDescuento($conn, $cliente_id) {
    $query = "CALL sp_aplicar_descuento(?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $cliente_id);
    
    mysqli_stmt_close($stmt);
}

function generarReporteBajoStock($conn) {
    $query = "SELECT id, nombre, stock FROM productos WHERE stock < 10";
    $stmt = mysqli_prepare($conn, $query);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "Producto ID: " . $row['id'] . " - Nombre: " . $row['nombre'] . " - Stock: " . $row['stock'] . "<br>";
        }
    }
    
    mysqli_stmt_close($stmt);
}

function calcularComision($conn, $venta_id) {
    $query = "SELECT total FROM ventas WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $venta_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $comision = $row['total'] * 0.1;
            echo "Comisión calculada: $" . $comision . "<br>";
        } else {
            echo "No se encontró ninguna venta con el ID proporcionado.<br>";
        }
    } else {
        echo "Error al calcular la comisión: " . mysqli_error($conn) . "<br>";
    }
    
    mysqli_stmt_close($stmt);
}

$venta_id = registrarVenta($conn, 1, 1, 2);
obtenerEstadisticasCliente($conn, 1);
procesarDevolucion($conn, 1, 1, 1);
aplicarDescuento($conn, 1);
generarReporteBajoStock($conn);

if ($venta_id) {
    calcularComision($conn, $venta_id);
} 

mysqli_close($conn);
?>
