<?php
require_once "config_mysqli.php"; // Asegúrate de que $conn esté definido aquí.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Agregar libro
    if (isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
        $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
        $autor = mysqli_real_escape_string($conn, $_POST['autor']);
        $ISBN = mysqli_real_escape_string($conn, $_POST['ISBN']);
        $publicacion = mysqli_real_escape_string($conn, $_POST['publicacion']);
        $cantidad_disponible = mysqli_real_escape_string($conn, $_POST['cantidad_disponible']);

        $publicacion = intval($publicacion);
        $cantidad_disponible = intval($cantidad_disponible);

        $sql = "INSERT INTO libros (titulo, autor, isbn, publicacion, cantidad_disponible) VALUES (?, ?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssi", $titulo, $autor, $ISBN, $publicacion, $cantidad_disponible);

            if (mysqli_stmt_execute($stmt)) {
                echo "Libro agregado con éxito.";
            } else {
                echo "ERROR: No se pudo ejecutar la consulta. " . mysqli_error($conn);
            }
        } else {
            echo "ERROR: No se pudo preparar la consulta. " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }

    // Listar libros
    if (isset($_POST['accion']) && $_POST['accion'] === 'listar') {
        $sql = "SELECT * FROM libros";
        $result = mysqli_query($conn, $sql); 

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo "<h3>Lista de Libros</h3>";
                echo "<table border='1'>";
                echo "<tr><th>Título</th><th>Autor</th><th>ISBN</th><th>Año de Publicación</th><th>Cantidad Disponible</th></tr>";
                
                while ($row = mysqli_fetch_assoc($result)) {
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
        } else {
            echo "ERROR: No se pudo ejecutar la consulta. " . mysqli_error($conn);
        }
    }

    // Eliminar libro
    if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
        if (isset($_POST['titulo'])) {
            $titulo = mysqli_real_escape_string($conn, $_POST['titulo']); // Escapar el título para evitar inyección SQL

            $sql = "DELETE FROM libros WHERE titulo = ?";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $titulo);

                if (mysqli_stmt_execute($stmt)) {
                    if (mysqli_stmt_affected_rows($stmt) > 0) {
                        echo "Libro eliminado con éxito.";
                    } else {
                        echo "No se encontró ningún libro con ese título.";
                    }
                } else {
                    echo "ERROR: No se pudo ejecutar la consulta. " . mysqli_error($conn);
                }
            } else {
                echo "ERROR: No se pudo preparar la consulta. " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "ERROR: Debes proporcionar un título para eliminar el libro.";
        }
    }
}

//buscar
if (isset($_POST['accion']) && $_POST['accion'] === 'buscar') {
    $titulo = isset($_POST['titulo']) ? mysqli_real_escape_string($conn, $_POST['titulo']) : null;
    $autor = isset($_POST['autor']) ? mysqli_real_escape_string($conn, $_POST['autor']) : null;
    $isbn = isset($_POST['isbn']) ? mysqli_real_escape_string($conn, $_POST['isbn']) : null;

    $sql = "SELECT * FROM libros WHERE titulo = ? OR autor = ? OR isbn = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $titulo, $autor, $isbn);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo "<h3>Lista de Libros</h3>";
                echo "<table border='1'>";
                echo "<tr><th>Título</th><th>Autor</th><th>ISBN</th><th>Año de Publicación</th><th>Cantidad Disponible</th></tr>";

                while ($row = mysqli_fetch_assoc($result)) {
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
        } else {
            echo "ERROR: No se pudo ejecutar la consulta. " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "ERROR: No se pudo preparar la consulta. " . mysqli_error($conn);
    }
}

//actualizar

// Actualizar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
    // Obtener y escapar los valores del formulario
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $autor = mysqli_real_escape_string($conn, $_POST['autor']);
    $ISBN = mysqli_real_escape_string($conn, $_POST['ISBN']);
    $publicacion = mysqli_real_escape_string($conn, $_POST['publicacion']); // No usar intval aquí
    $cantidad_disponible = intval($_POST['cantidad_disponible']); // Solo convertir cantidad_disponible a entero

    // Actualizar el libro
    $sql = "UPDATE libros SET autor = ?, isbn = ?, publicacion = ?, cantidad_disponible = ? WHERE titulo = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Vincular los parámetros
        mysqli_stmt_bind_param($stmt, "sssis", $autor, $ISBN, $publicacion, $cantidad_disponible, $titulo);
        
        // Ejecutar la consulta
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $mensaje = "Libro actualizado con éxito.";
            } else {
                $mensaje = "No se encontró ningún libro con ese título para actualizar.";
            }
        } else {
            $mensaje = "ERROR: No se pudo ejecutar la consulta. " . mysqli_error($conn);
        }
    } else {
        $mensaje = "ERROR: No se pudo preparar la consulta. " . mysqli_error($conn);
    }

    // Cerrar el statement
    mysqli_stmt_close($stmt);
}

// Cerrar la conexión
mysqli_close($conn);
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
    <label>Título o ISB O Autor</label><input type="text" name="titulo" required>
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
    <input type="submit" value="actualizar libro">
</form>