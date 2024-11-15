<?php
require_once "config_pdo.php";

function obtenerEstadisticasTabla($pdo, $tabla) {
    try {
        $sql = "SHOW TABLE STATUS LIKE :tabla";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':tabla' => $tabla]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function obtenerEstadisticasIndices($pdo, $tabla) {
    try {
        $sql = "SHOW INDEX FROM " . $tabla;
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function mostrarVariablesRendimiento($pdo) {
    try {
        $variables = [
            'innodb_buffer_pool_size',
            'key_buffer_size',
            'max_connections',
            'query_cache_size',
            'tmp_table_size',
            'max_heap_table_size'
        ];
        
        $sql = "SHOW VARIABLES WHERE Variable_name IN ('" . implode("','", $variables) . "')";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Mostrar estadísticas
$tablas = ['productos', 'ventas', 'detalles_venta'];
foreach ($tablas as $tabla) {
    $stats = obtenerEstadisticasTabla($pdo, $tabla);
    echo "<h3>Estadísticas de la tabla $tabla:</h3>";
    print_r($stats);
    
    $indices = obtenerEstadisticasIndices($pdo, $tabla);
    echo "<h3>Índices de la tabla $tabla:</h3>";
    print_r($indices);
}

$variables = mostrarVariablesRendimiento($pdo);
echo "<h3>Variables de rendimiento:</h3>";
print_r($variables);

$pdo = null;
?>
