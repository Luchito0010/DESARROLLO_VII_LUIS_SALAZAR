<?php
// Función para obtener una lista de libros (simulando una base de datos)
function obtenerLibros() {
    return [
        [
            'titulo' => 'Cien años de soledad',
            'autor' => 'Gabriel García Márquez',
            'anio' => 1967,
            'genero' => 'Realismo mágico'
        ],
        [
            'titulo' => 'Don Quijote de la Mancha',
            'autor' => 'Miguel de Cervantes',
            'anio' => 1605,
            'genero' => 'Novela'
        ],
        [
            'titulo' => '1984',
            'autor' => 'George Orwell',
            'anio' => 1949,
            'genero' => 'Distopía'
        ],
        [
            'titulo' => 'Orgullo y prejuicio',
            'autor' => 'Jane Austen',
            'anio' => 1813,
            'genero' => 'Romance'
        ],
        [
            'titulo' => 'Matar a un ruiseñor',
            'autor' => 'Harper Lee',
            'anio' => 1960,
            'genero' => 'Ficción'
        ]
    ];
}

// Función para mostrar los detalles de un libro
function mostrarDetallesLibro($libro) {
    return '<div class="libro">
        <h3>' . htmlspecialchars($libro['titulo']) . '</h3>
        <p><strong>Autor:</strong> ' . htmlspecialchars($libro['autor']) . '</p>
        <p><strong>Año:</strong> ' . htmlspecialchars($libro['anio']) . '</p>
        <p><strong>Género:</strong> ' . htmlspecialchars($libro['genero']) . '</p>
    </div>';
}
?>
