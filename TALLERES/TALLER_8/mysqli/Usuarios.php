<?php
require_once "config_mysqli.php"; 

// Agregar usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contraseña = mysqli_real_escape_string($conn, $_POST['contraseña']);

    $sql = "INSERT INTO Users (nombre, email, contraseña) VALUES (?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $contraseña);

        if (mysqli_stmt_execute($stmt)) {
            echo "Usuario creado con éxito.";
        } else {
            echo "ERROR: No se pudo ejecutar la consulta. " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "ERROR: No se pudo preparar la consulta. " . mysqli_error($conn);
    }
}

// Listar usuarios
$usuarios = [];
$sql = "SELECT id, nombre, email, contraseña FROM Users";
$result = mysqli_query($conn, $sql);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $usuarios[] = $row;
        }
        mysqli_free_result($result);
    } else {
        echo "No se encontraron registros.";
    }
} else {
    echo "ERROR: No se pudo ejecutar $sql. " . mysqli_error($conn);
}

// Actualizar usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
    $id = intval($_POST['id']);
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contraseña = mysqli_real_escape_string($conn, $_POST['contraseña']);

    $sql = "UPDATE Users SET nombre = ?, email = ?, contraseña = ? WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssi", $nombre, $email, $contraseña, $id);

        if (mysqli_stmt_execute($stmt)) {
            echo "Usuario actualizado con éxito.";
        } else {
            echo "ERROR: No se pudo ejecutar la consulta. " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "ERROR: No se pudo preparar la consulta. " . mysqli_error($conn);
    }
}

// Eliminar usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $id = intval($_POST['id']);

    $sql = "DELETE FROM Users WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            echo "Usuario eliminado con éxito.";
        } else {
            echo "ERROR: No se pudo ejecutar la consulta. " . mysqli_error($conn);
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
