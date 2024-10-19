<?php
require_once "config_pdo.php";

try {
    // 1. Mostrar todos los usuarios junto con el número de publicaciones que han hecho
    $sql = "SELECT u.id, u.nombre, COUNT(p.id) as num_publicaciones 
            FROM usuarios u 
            LEFT JOIN publicaciones p ON u.id = p.usuario_id 
            GROUP BY u.id";

    $stmt = $pdo->query($sql);

    echo "<h3>Usuarios y número de publicaciones:</h3>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Usuario: " . $row['nombre'] . ", Publicaciones: " . $row['num_publicaciones'] . "<br>";
    }

    // 2. Listar todas las publicaciones con el nombre del autor
    $sql = "SELECT p.titulo, u.nombre as autor, p.fecha_publicacion 
            FROM publicaciones p 
            INNER JOIN usuarios u ON p.usuario_id = u.id 
            ORDER BY p.fecha_publicacion DESC";

    $stmt = $pdo->query($sql);

    echo "<h3>Publicaciones con nombre del autor:</h3>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Título: " . $row['titulo'] . ", Autor: " . $row['autor'] . ", Fecha: " . $row['fecha_publicacion'] . "<br>";
    }

    // 3. Encontrar el usuario con más publicaciones
    $sql = "SELECT u.nombre, COUNT(p.id) as num_publicaciones 
            FROM usuarios u 
            LEFT JOIN publicaciones p ON u.id = p.usuario_id 
            GROUP BY u.id 
            ORDER BY num_publicaciones DESC 
            LIMIT 1";

    $stmt = $pdo->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<h3>Usuario con más publicaciones:</h3>";
    echo "Nombre: " . $row['nombre'] . ", Número de publicaciones: " . $row['num_publicaciones'];

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}


$sql = "SELECT p.titulo, u.nombre AS autor, p.fecha_publicacion
        FROM publicaciones p
        INNER JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.fecha_publicacion DESC
        LIMIT 5";

$stmt = $pdo->query($sql);

echo "<h3>Últimas 5 publicaciones:</h3>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Título: " . $row['titulo'] . ", Autor: " . $row['autor'] . ", Fecha: " . $row['fecha_publicacion'] . "<br>";
}

$sql = "SELECT u.id, u.nombre
        FROM usuarios u
        LEFT JOIN publicaciones p ON u.id = p.usuario_id
        WHERE p.id IS NULL";

$stmt = $pdo->query($sql);

echo "<h3>Usuarios sin publicaciones:</h3>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Usuario: " . $row['nombre'] . "<br>";
}


$sql = "SELECT AVG(num_publicaciones) AS promedio_publicaciones
        FROM (
            SELECT COUNT(p.id) AS num_publicaciones
            FROM usuarios u
            LEFT JOIN publicaciones p ON u.id = p.usuario_id
            GROUP BY u.id
        ) AS subquery";

$stmt = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h3>Promedio de publicaciones por usuario:</h3>";
echo "Promedio: " . $row['promedio_publicaciones'];

$sql = "SELECT p.id, p.titulo, u.nombre AS autor, p.fecha_publicacion
        FROM publicaciones p
        INNER JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.fecha_publicacion = (
            SELECT MAX(fecha_publicacion)
            FROM publicaciones
            WHERE usuario_id = p.usuario_id
        )";

$stmt = $pdo->query($sql);

echo "<h3>Última publicación de cada usuario:</h3>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Título: " . $row['titulo'] . ", Autor: " . $row['autor'] . ", Fecha: " . $row['fecha_publicacion'] . "<br>";
}


$pdo = null;
?>