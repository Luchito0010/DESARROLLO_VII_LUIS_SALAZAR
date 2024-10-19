<?php
require_once "config_pdo.php"; // Asegúrate de que $conn esté definido aquí.

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] === 'registrar_prestamo') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $titulo_libro = $_POST['titulo_libro'];
    $fecha_prestamo = date('Y-m-d');

    // Verificar si el usuario y el libro existen
    $sql_usuario = "SELECT id FROM Users WHERE nombre = :nombre";
    $sql_libro = "SELECT id FROM libros WHERE titulo = :titulo";

    // Verificar usuario
    $stmt_usuario = $conn->prepare($sql_usuario);
    $stmt_usuario->bindParam(':nombre', $nombre_usuario);
    $stmt_usuario->execute();
    $user_id = $stmt_usuario->fetchColumn();
    $stmt_usuario = null; // Liberar la declaración

    // Verificar libro
    $stmt_libro = $conn->prepare($sql_libro);
    $stmt_libro->bindParam(':titulo', $titulo_libro);
    $stmt_libro->execute();
    $libro_id = $stmt_libro->fetchColumn();
    $stmt_libro = null; // Liberar la declaración

    if ($user_id && $libro_id) {
        $sql = "INSERT INTO prestamos (nombre_usuario, titulo_libro, fecha_prestamo) VALUES (:nombre_usuario, :titulo_libro, :fecha_prestamo)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre_usuario', $nombre_usuario);
        $stmt->bindParam(':titulo_libro', $titulo_libro);
        $stmt->bindParam(':fecha_prestamo', $fecha_prestamo);

        if ($stmt->execute()) {
            echo "Préstamo registrado con éxito.";
        } else {
            echo "ERROR: No se pudo ejecutar la consulta.";
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

$stmt = $conn->prepare($sql);
$stmt->execute();
$prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($prestamos)) {
    echo "No hay préstamos activos.";
}

// Registrar devolución
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] === 'registrar_devolucion') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $titulo_libro = $_POST['titulo_libro'];
    $fecha_devolucion = date('Y-m-d');

    $sql = "UPDATE prestamos SET fecha_devolucion = :fecha_devolucion WHERE nombre_usuario = :nombre_usuario AND titulo_libro = :titulo_libro";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fecha_devolucion', $fecha_devolucion);
    $stmt->bindParam(':nombre_usuario', $nombre_usuario);
    $stmt->bindParam(':titulo_libro', $titulo_libro);

    if ($stmt->execute()) {
        echo "Devolución registrada con éxito.";
    } else {
        echo "ERROR: No se pudo ejecutar la consulta.";
    }
}

// Mostrar historial de préstamos por usuario
$historial = [];
if (isset($_POST['accion']) && $_POST['accion'] === 'historial_prestamos') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $sql = "SELECT titulo_libro, fecha_prestamo, fecha_devolucion 
            FROM prestamos 
            WHERE nombre_usuario = :nombre_usuario";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre_usuario', $nombre_usuario);
    $stmt->execute();
    $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($historial)) {
        echo "No se encontraron préstamos para este usuario.";
    }
}

// Cerrar la conexión (opcional, pero recomendable)
$conn = null;
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
