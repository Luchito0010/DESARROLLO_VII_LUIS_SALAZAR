<?php
require_once "config_pdo.php"; 

// Agregar usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];

    $sql = "INSERT INTO Users (nombre, email, contraseña) VALUES (:nombre, :email, :contraseña)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':contraseña', $contraseña);

    if ($stmt->execute()) {
        echo "Usuario creado con éxito.";
    } else {
        echo "ERROR: No se pudo ejecutar la consulta.";
    }
}

// Listar usuarios
$usuarios = [];
$sql = "SELECT id, nombre, email, contraseña FROM Users";
$stmt = $conn->prepare($sql);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "No se encontraron registros.";
}

// Actualizar usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];

    $sql = "UPDATE Users SET nombre = :nombre, email = :email, contraseña = :contraseña WHERE id = :id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':contraseña', $contraseña);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "Usuario actualizado con éxito.";
    } else {
        echo "ERROR: No se pudo ejecutar la consulta.";
    }
}

// Eliminar usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $id = intval($_POST['id']);

    $sql = "DELETE FROM Users WHERE id = :id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "Usuario eliminado con éxito.";
    } else {
        echo "ERROR: No se pudo ejecutar la consulta.";
    }
}

// Cerrar la conexión
$conn = null;
?>

<!-- Formularios -->
<h2>Agregar Usuario</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div><label>Nombre</label><input type="text" name="nombre" required></div>
    <div><label>Email</label><input type="email" name="email" required></div>
    <div><label>Contraseña</label><input type="password" name="contraseña" required></div>
    <input type="hidden" name="accion" value="agregar">
    <input type="submit" value="Crear Usuario">
</form>

<h2>Listar Usuarios</h2>
<table border='1'>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Contraseña</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($usuarios as $usuario): ?>
    <tr>
        <td><?php echo htmlspecialchars($usuario['id']); ?></td>
        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
        <td><?php echo htmlspecialchars($usuario['contraseña']); ?></td>
        <td>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                <input type="hidden" name="accion" value="eliminar">
                <input type="submit" value="Eliminar">
            </form>
            <button onclick="document.getElementById('updateForm-<?php echo $usuario['id']; ?>').style.display='block'">Actualizar</button>
            <div id="updateForm-<?php echo $usuario['id']; ?>" style="display:none;">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div><label>Nombre</label><input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required></div>
                    <div><label>Email</label><input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required></div>
                    <div><label>Contraseña</label><input type="password" name="contraseña" required></div>
                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                    <input type="hidden" name="accion" value="actualizar">
                    <input type="submit" value="Actualizar Usuario">
                </form>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
