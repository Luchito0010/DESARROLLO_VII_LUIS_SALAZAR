<?php
// Incluir los archivos necesarios
include 'includes/funciones.php';
include 'includes/header.php';
include 'includes/footer.php';

// Obtener la lista de libros
$libros = obtenerLibros();

// Mostrar todos los libros con sus detalles
echo '<main>';
foreach ($libros as $libro) {
    echo mostrarDetallesLibro($libro);
}
echo '</main>';
?>
