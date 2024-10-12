<?php
// 1. Crear un arreglo de 10 nombres de ciudades
$ciudades = ["Nueva York", "Tokio", "Londres", "París", "Sídney", "Río de Janeiro", "Moscú", "Berlín", "Ciudad del Cabo", "Toronto"];

// 2. Imprimir el arreglo original
echo "Ciudades originales:\n";
print_r($ciudades);

// 3. Agregar 2 ciudades más al final del arreglo
array_push($ciudades, "Dubái", "Singapur");

// 4. Eliminar la tercera ciudad del arreglo
array_splice($ciudades, 2, 1);

// 5. Insertar una nueva ciudad en la quinta posición
array_splice($ciudades, 4, 0, "Mumbai");

// 6. Imprimir el arreglo modificado
echo "\nCiudades modificadas:\n";
print_r($ciudades);

// 7. Crear una función que imprima las ciudades en orden alfabético
function imprimirCiudadesOrdenadas($arr) {
    $ordenado = $arr;
    sort($ordenado);
    echo "Ciudades en orden alfabético:\n";
    foreach ($ordenado as $ciudad) {
        echo "- $ciudad\n";
    }
}

// 8. Llamar a la función
imprimirCiudadesOrdenadas($ciudades);
function contarCiudadesPorInicial($ciudades, $letra) {
    $contador = 0; // Inicia un contador en 0
    foreach ($ciudades as $ciudad) { // Recorre el array de ciudades
        if (substr($ciudad, 0, 1) === $letra) { // Verifica si la primera letra coincide
            $contador++; // Si coincide, incrementa el contador
        }
    }
    return $contador; // Retorna el número de coincidencias
}

// Ejemplo de uso:
$ciudades = ['Londres', 'París', 'Singapur', 'Sidney', 'Tokio'];
echo contarCiudadesPorInicial($ciudades, 'P'); // Debería retornar 2 (Singapur, Sidney)

  