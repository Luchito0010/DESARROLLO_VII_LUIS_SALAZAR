<?php
if(isset($_COOKIE['usuario'])) {
    echo "Bienvenido, " . htmlspecialchars($_COOKIE['usuario']) . "!";
} else {
    echo "No se ha encontrado la cookie 'usuario'.";
}

    $estudiantes = [
        ["nombre" => "Ana", "calificaciones" => [85, 92, 78, 96, 88]],
        ["nombre" => "Juan", "calificaciones" => [75, 84, 91, 79, 86]],
        ["nombre" => "María", "calificaciones" => [92, 95, 89, 97, 93]],
        ["nombre" => "Pedro", "calificaciones" => [70, 72, 78, 75, 77]],
        ["nombre" => "Laura", "calificaciones" => [88, 86, 90, 85, 89]],
        ["nombre" => "Luis", "calificaciones" =>  [100,50,78,95,61]]
    ];
    
    // 2. Función para calcular el promedio de calificaciones
    function calcularPromedio($calificaciones) {
        return array_sum($calificaciones) / count($calificaciones);
    }
    
    // 3. Función para asignar una letra de calificación basada en el promedio
    function asignarLetraCalificacion($promedio) {
        if ($promedio >= 90) return 'A';
        if ($promedio >= 80) return 'B';
        if ($promedio >= 70) return 'C';
        if ($promedio >= 60) return 'D';
        return 'F';
    }
    
    // 4. Procesar y mostrar información de estudiantes
    echo "Información de estudiantes:\n";
    foreach ($estudiantes as &$estudiante) {
        $promedio = calcularPromedio($estudiante["calificaciones"]);
        $estudiante["promedio"] = $promedio;
        $estudiante["letra_calificacion"] = asignarLetraCalificacion($promedio);
        
        echo "{$estudiante['nombre']}:\n";
        echo "  Calificaciones: " . implode(", ", $estudiante["calificaciones"]) . "\n";
        echo "  Promedio: " . number_format($promedio, 2) . "\n";
        echo "  Calificación: {$estudiante['letra_calificacion']}\n\n";
    }
    echo "No se ha encontrado la cookie 'usuario'."
?>