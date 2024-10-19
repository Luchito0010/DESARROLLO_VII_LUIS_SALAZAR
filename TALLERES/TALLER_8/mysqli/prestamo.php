<?php
require_once 'config_mysqli.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] === 'registrar_prestamo') {
    $nombre_usuario = mysqli_real_escape_string($conn, $_POST['nombre_usuario']);
    $titulo_libro = mysqli_real_escape_string($conn, $_POST['titulo_libro']);
    $fecha_prestamo = date('Y-m-d');

    // Verificar si el usuario y el libro existen
    $sql_usuario = "SELECT id FROM Users WHERE nombre = ?";
    $sql_libro = "SELECT id FROM libros WHERE titulo = ?";

    $stmt_usuario = mysqli_prepare($conn, $sql_usuario);
    mysqli_stmt_bind_param($stmt_usuario, "s", $nombre_usuario);
    mysqli_stmt_execute($stmt_usuario);
    mysqli_stmt_bind_result($stmt_usuario, $user_id);
    mysqli_stmt_fetch($stmt_usuario);
    mysqli_stmt_close($stmt_usuario);

    $stmt_libro = mysqli_prepare($conn, $sql_libro);
    mysqli_stmt_bind_param($stmt_libro, "s", $titulo_libro);
    mysqli_stmt_execute($stmt_libro);
    mysqli_stmt_bind_result($stmt_libro, $libro_id);
    mysqli_stmt_fetch($stmt_libro);
    mysqli_stmt_close($stmt_libro);

    if ($user_id && $libro_id) {
        $sql = "INSERT INTO prestamos (nombre_usuario, titulo_libro, fecha_prestamo) VALUES (?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $nombre_usuario, $titulo_libro, $fecha_prestamo);

            if (mysqli_stmt_execute($stmt)) {
                echo "Préstamo registrado con éxito.";
            } else {
                echo "ERROR: No se pudo ejecutar la consulta. " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "ERROR: No se pudo preparar la consulta. " . mysqli_error($conn);
        }
    } else {
        echo "ERROR: Usuario o libro no encontrado.";
    }
}

// Listar préstamos activos
$prestamos = [];
$sql = "SELECT p.nombre_usuario, p.titulo_libro, p.fecha_prestamo 
        FROM prestamos p 
        WHERE p.fecha_devolucion IS NULL"; // Solo préstamos activos

$result = mysqli_query($conn, $sql);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $prestamos[] = $row;
        }
        mysqli_free_result($result);
    } else {
        echo "No hay préstamos activos.";
    }
} else {
    echo "ERROR: No se pudo ejecutar $sql. " . mysqli_error($conn);
}

// Registrar devolución
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] === 'registrar_devolucion') {
    $nombre_usuario = mysqli_real_escape_string($conn, $_POST['nombre_usuario']);
    $titulo_libro = mysqli_real_escape_string($conn, $_POST['titulo_libro']);
    $fecha_devolucion = date('Y-m-d');

    $sql = "UPDATE prestamos SET fecha_devolucion = ? WHERE nombre_usuario = ? AND titulo_libro = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $fecha_devolucion, $nombre_usuario, $titulo_libro);

        if (mysqli_stmt_execute($stmt)) {
            echo "Devolución registrada con éxito.";
        } else {
            echo "ERROR: No se pudo ejecutar la consulta. " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "ERROR: No se pudo preparar la consulta. " . mysqli_error($conn);
    }
}

// Mostrar historial de préstamos por usuario
$historial = [];
if (isset($_POST['accion']) && $_POST['accion'] === 'historial_prestamos') {
    $nombre_usuario = mysqli_real_escape_string($conn, $_POST['nombre_usuario']);
    $sql = "SELECT titulo_libro, fecha_prestamo, fecha_devolucion 
            FROM prestamos 
            WHERE nombre_usuario = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $nombre_usuario);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $historial[] = $row;
            }
            mysqli_free_result($result);
        } else {
            echo "No se encontraron préstamos para este usuario.";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "ERROR: No se pudo preparar la consulta. " . mysqli_error($conn);
    }
}

// Cerrar la conexión
mysqli_close($conn);
?>

<!-- Formularios -->
<h2>Registrar Préstamo</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div>
        <label>Nombre Usuario</label>
        <input type="text" name="nombre_usuario" required>
    </div>
    <div>
        <label>Título Libro</label>
        <input type="text" name="titulo_libro" required>
    </div>
    <input type="hidden" name="accion" value="registrar_prestamo">
    <input type="submit" value="Registrar Préstamo">
</form>

<h2>Préstamos Activos</h2>
<table border='1'>
    <tr>
        <th>Usuario</th>
        <th>Libro</th>
        <th>Fecha de Préstamo</th>
    </tr>
    <?php foreach ($prestamos as $prestamo): ?>
    <tr>
        <td><?php echo htmlspecialchars($prestamo['nombre_usuario']); ?></td>
        <td><?php echo htmlspecialchars($prestamo['titulo_libro']); ?></td>
        <td><?php echo htmlspecialchars($prestamo['fecha_prestamo']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Registrar Devolución</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div>
        <label>Nombre Usuario</label>
        <input type="text" name="nombre_usuario" required>
    </div>
    <div>
        <label>Título Libro</label>
        <input type="text" name="titulo_libro" required>
    </div>
    <input type="hidden" name="accion" value="registrar_devolucion">
    <input type="submit" value="Registrar Devolución">
</form>

<h2>Historial de Préstamos por Usuario</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div>
        <label>Nombre Usuario</label>
        <input type="text" name="nombre_usuario" required>
    </div>
    <input type="hidden" name="accion" value="historial_prestamos">
    <input type="submit" value="Mostrar Historial">
</form>

<?php if (!empty($historial)): ?>
<table border='1'>
    <tr>
        <th>Libro</th>
        <th>Fecha de Préstamo</th>
        <th>Fecha de Devolución</th>
    </tr>
    <?php foreach ($historial as $item): ?>
    <tr>
        <td><?php echo htmlspecialchars($item['titulo_libro']); ?></td>
        <td><?php echo htmlspecialchars($item['fecha_prestamo']); ?></td>
        <td><?php echo htmlspecialchars($item['fecha_devolucion']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
