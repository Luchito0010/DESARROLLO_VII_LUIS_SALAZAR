<?php
require_once "config_mysqli.php";

function mostrarResumenCategorias($conn) {
    $sql = "SELECT * FROM vista_resumen_categorias";
    $result = mysqli_query($conn, $sql);

    echo "<h3>Resumen por Categorías:</h3>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Categoría</th>
            <th>Total Productos</th>
            <th>Stock Total</th>
            <th>Precio Promedio</th>
            <th>Precio Mínimo</th>
            <th>Precio Máximo</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['categoria']}</td>";
        echo "<td>{$row['total_productos']}</td>";
        echo "<td>{$row['total_stock']}</td>";
        echo "<td>{$row['precio_promedio']}</td>";
        echo "<td>{$row['precio_minimo']}</td>";
        echo "<td>{$row['precio_maximo']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    mysqli_free_result($result);
}

function mostrarProductosPopulares($conn) {
    $sql = "SELECT * FROM vista_productos_populares LIMIT 5";
    $result = mysqli_query($conn, $sql);

    echo "<h3>Top 5 Productos Más Vendidos:</h3>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Producto</th>
            <th>Categoría</th>
            <th>Total Vendido</th>
            <th>Ingresos Totales</th>
            <th>Compradores Únicos</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['producto']}</td>";
        echo "<td>{$row['categoria']}</td>";
        echo "<td>{$row['total_vendido']}</td>";
        echo "<td>{$row['ingresos_totales']}</td>";
        echo "<td>{$row['compradores_unicos']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    mysqli_free_result($result);
}
// Función para mostrar productos con bajo stock
function mostrarProductosBajoStock($conn) {
    $sql = "SELECT p.id, p.nombre, p.stock, 
                   COALESCE(SUM(dv.cantidad), 0) AS total_vendido, 
                   COALESCE(SUM(dv.subtotal), 0) AS ingresos_totales
            FROM productos p
            LEFT JOIN detalles_venta dv ON p.id = dv.producto_id
            WHERE p.stock < 5
            GROUP BY p.id, p.nombre, p.stock";

    $result = mysqli_query($conn, $sql);
    
    echo "<h3>Productos con Bajo Stock (menos de 5 unidades):</h3>";
    echo "<table border='1'>";
    echo "<tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Stock</th>
            <th>Total Vendido</th>
            <th>Ingresos Totales</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['nombre']}</td>";
        echo "<td>{$row['stock']}</td>";
        echo "<td>{$row['total_vendido']}</td>";
        echo "<td>\${$row['ingresos_totales']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    mysqli_free_result($result);
}

// Función para mostrar el historial completo de cada cliente
function mostrarHistorialClientes($conn) {
    $sql = "SELECT * FROM vista_detalles_ventas";

    $result = mysqli_query($conn, $sql);
    
    echo "<h3>Historial Completo de Clientes:</h3>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Cliente</th>
            <th>Email</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
            <th>Fecha Venta</th>
            <th>Estado</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['cliente']}</td>";
        echo "<td>{$row['cliente_email']}</td>";
        echo "<td>{$row['producto']}</td>";
        echo "<td>{$row['cantidad']}</td>";
        echo "<td>\${$row['precio_unitario']}</td>";
        echo "<td>\${$row['subtotal']}</td>";
        echo "<td>{$row['fecha_venta']}</td>";
        echo "<td>{$row['estado']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    mysqli_free_result($result);
}

// Función para mostrar métricas de rendimiento por categoría
function mostrarMetricasPorCategoria($conn) {
    $sql = "SELECT c.nombre AS categoria, 
                   COUNT(p.id) AS total_productos, 
                   SUM(dv.cantidad) AS total_vendido,
                   SUM(dv.subtotal) AS ingresos_totales
            FROM categorias c
            JOIN productos p ON c.id = p.categoria_id
            LEFT JOIN detalles_venta dv ON p.id = dv.producto_id
            GROUP BY c.nombre";

    $result = mysqli_query($conn, $sql);

    echo "<h3>Métricas de Rendimiento por Categoría:</h3>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Categoría</th>
            <th>Total Productos</th>
            <th>Total Vendido</th>
            <th>Ingresos Totales</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['categoria']}</td>";
        echo "<td>{$row['total_productos']}</td>";
        echo "<td>{$row['total_vendido']}</td>";
        echo "<td>\${$row['ingresos_totales']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    mysqli_free_result($result);
}

// Función para mostrar tendencias de ventas por mes
function mostrarTendenciasVentas($conn) {
    $sql = "SELECT DATE_FORMAT(v.fecha_venta, '%Y-%m') AS mes, 
                   SUM(dv.cantidad) AS total_vendido, 
                   SUM(dv.subtotal) AS ingresos_totales
            FROM ventas v
            JOIN detalles_venta dv ON v.id = dv.venta_id
            GROUP BY mes
            ORDER BY mes";

    $result = mysqli_query($conn, $sql);

    echo "<h3>Tendencias de Ventas por Mes:</h3>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Mes</th>
            <th>Total Vendido</th>
            <th>Ingresos Totales</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['mes']}</td>";
        echo "<td>{$row['total_vendido']}</td>";
        echo "<td>\${$row['ingresos_totales']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    mysqli_free_result($result);
}

// Mostrar los resultados
mostrarResumenCategorias($conn);
mostrarProductosPopulares($conn);
mostrarProductosBajoStock($conn);
mostrarHistorialClientes($conn);
mostrarMetricasPorCategoria($conn);
mostrarTendenciasVentas($conn);

mysqli_close($conn);
?>

