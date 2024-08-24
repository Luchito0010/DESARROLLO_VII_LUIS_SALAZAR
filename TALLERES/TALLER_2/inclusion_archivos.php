<?php
echo "<h2>Inclusión de Archivos</h2>";

// Usando include
echo "<h3>Usando include:</h3>";
include 'funciones_utiles.php'; // Incluye el archivo (no protege contra múltiples inclusiones)
echo "El doble de 5 es: " . doblar(5) . "<br>";
echo saludoPersonalizado("Ana") . "<br>";

// Usando require
echo "<h3>Usando require:</h3>";
require 'funciones_utiles.php'; // Incluye el archivo (termina el script si falla)
echo "El doble de 10 es: " . doblar(10) . "<br>";

// Usando include_once
echo "<h3>Usando include_once:</h3>";
include_once 'funciones_utiles.php'; // Incluye el archivo solo una vez
echo saludoPersonalizado("Carlos") . "<br>";

// Usando require_once
echo "<h3>Usando require_once:</h3>";
require_once 'funciones_utiles.php'; // Incluye el archivo solo una vez (termina el script si falla)
echo "El doble de 7 es: " . doblar(7) . "<br>";

// Intentando incluir un archivo que no existe
echo "<h3>Intentando incluir un archivo inexistente:</h3>";
@include 'archivo_inexistente.php'; // El @ suprime los warnings
echo "Esta línea se ejecutará aunque el archivo no exista.<br>";

// Intentando requerir un archivo que no existe
echo "<h3>Intentando requerir un archivo inexistente:</h3>";
// Comenta la siguiente línea para evitar un error fatal
// require 'archivo_inexistente.php'; // Esto causará un error fatal si el archivo no existe
echo "Esta línea no se ejecutará si se requiere un archivo inexistente.<br>";

?>
