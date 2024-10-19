<?php
require_once "config_pdo.php"; // Asegúrate de que $conn esté definido aquí.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Agregar libro
    if (isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
        $titulo = $_POST['titulo'];
        $autor = $_POST['autor'];
        $ISBN = $_POST['ISBN'];
        $publicacion = $_POST['publicacion'];
        $cantidad_disponible = $_POST['cantidad_disponible'];

        $publicacion = intval($publicacion);
        $cantidad_disponible = intval($cantidad_disponible);

        $sql = "INSERT INTO libros (titulo, autor, isbn, publicacion, cantidad_disponible) VALUES (?, ?, ?, ?, ?)";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([$titulo, $autor, $ISBN, $publicacion, $cantidad_disponible]);
            echo "Libro agregado con éxito.";
        } catch (PDOException $e) {
            echo "ERROR: No se pudo ejecutar la consulta. " . $e->getMessage();
        }
    }

    // Listar libros
    if (isset($_POST['accion']) && $_POST['accion'] === 'listar') {
        $sql = "SELECT * FROM libros";
        
        try {
            $stmt = $conn->query($sql);
            $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($libros) {
                echo "<h3>Lista de Libros</h3>";
                echo "<table border='1'>";
                echo "<tr><th>Título</th><th>Autor</th><th>ISBN</th><th>Año de Publicación</th><th>Cantidad Disponible</th></tr>";
                
                foreach ($libros as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['autor']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['isbn']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['publicacion']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['cantidad_disponible']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No se encontraron libros en la base de datos.";
            }
        } catch (PDOException $e) {
            echo "ERROR: No se pudo ejecutar la consulta. " . $e->getMessage();
        }
    }

    // Eliminar libro
    if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
        if (isset($_POST['titulo'])) {
            $titulo = $_POST['titulo']; // No es necesario escapar aquí, PDO lo maneja.

            $sql = "DELETE FROM libros WHERE titulo = ?";

            try {
                $stmt = $conn->prepare($sql);
                $stmt->execute([$titulo]);

                if ($stmt->rowCount() > 0) {
                    echo "Libro eliminado con éxito.";
                } else {
                    echo "No se encontró ningún libro con ese título.";
                }
            } catch (PDOException $e) {
                echo "ERROR: No se pudo ejecutar la consulta. " . $e->getMessage();
            }
        } else {
            echo "ERROR: Debes proporcionar un título para eliminar el libro.";
        }
    }
}

// Buscar
if (isset($_POST['accion']) && $_POST['accion'] === 'buscar') {
    $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : null;
    $autor = isset($_POST['autor']) ? $_POST['autor'] : null;
    $isbn = isset($_POST['isbn']) ? $_POST['isbn'] : null;

    $sql = "SELECT * FROM libros WHERE titulo = ? OR autor = ? OR isbn = ?";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$titulo, $autor, $isbn]);
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($libros) {
            echo "<h3>Lista de Libros</h3>";
            echo "<table border='1'>";
            echo "<tr><th>Título</th><th>Autor</th><th>ISBN</th><th>Año de Publicación</th><th>Cantidad Disponible</th></tr>";

            foreach ($libros as $row) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
                echo "<td>" . htmlspecialchars($row['autor']) . "</td>";
                echo "<td>" . htmlspecialchars($row['isbn']) . "</td>";
                echo "<td>" . htmlspecialchars($row['publicacion']) . "</td>";
                echo "<td>" . htmlspecialchars($row['cantidad_disponible']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No se encontraron libros en la base de datos.";
        }
    } catch (PDOException $e) {
        echo "ERROR: No se pudo ejecutar la consulta. " . $e->getMessage();
    }
}

// Actualizar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
    // Obtener los valores del formulario
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $ISBN = $_POST['ISBN'];
    $publicacion = $_POST['publicacion'];
    $cantidad_disponible = intval($_POST['cantidad_disponible']);

    // Actualizar el libro
    $sql = "UPDATE libros SET autor = ?, isbn = ?, publicacion = ?, cantidad_disponible = ? WHERE titulo = ?";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$autor, $ISBN, $publicacion, $cantidad_disponible, $titulo]);

        if ($stmt->rowCount() > 0) {
            echo "Libro actualizado con éxito.";
        } else {
            echo "No se encontró ningún libro con ese título para actualizar.";
        }
    } catch (PDOException $e) {
        echo "ERROR: No se pudo ejecutar la consulta. " . $e->getMessage();
    }
}

// Cerrar la conexión
$conn = null;
?>

<!-- Formularios -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <h3>Agregar Libro</h3>
    <div><label>Título</label><input type="text" name="titulo" required></div>
    <div><label>Autor</label><input type="text" name="autor" required></div>
    <div><label>ISBN</label><input type="text" name="ISBN" required></div>
    <div><label>Fecha de Publicación</label><input type="date" name="publicacion" required></div>
    <div><label>Cantidad Disponible</label><input type="number" name="cantidad_disponible" required></div>
    <input type="hidden" name="accion" value="agregar">
    <input type="submit" value="Crear libro">
</form>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <h3>Listar Libros</h3>
    <input type="hidden" name="accion" value="listar">
    <input type="submit" value="Listar Libros">
</form>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <h3>Buscar Libros</h3>
    <label>Título o ISBN o Autor</label><input type="text" name="titulo" required>
    <input type="hidden" name="accion" value="buscar">
    <input type="submit" value="Buscar Libros">
</form>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <h3>Eliminar libro</h3>
    <label>Título</label><input type="text" name="titulo" required>
    <input type="hidden" name="accion" value="eliminar">
    <input type="submit" value="Eliminar libro">
</form>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <h3>Actualizar Libro</h3>
    <div><label>Titulo</label><input type="text" name="titulo" required></div>
    <div><label>Autor</label><input type="text" name="autor" required></div>
    <div><label>ISBN</label><input type="text" name="ISBN" required></div>
    <div><label>Fecha de Publicación</label><input type="date" name="publicacion" required></div>
    <div><label>Cantidad Disponible</label><input type="number" name="cantidad_disponible" required></div>
    <input type="hidden" name="accion" value="actualizar">
    <input type="submit" value="Actualizar libro">
</form>
