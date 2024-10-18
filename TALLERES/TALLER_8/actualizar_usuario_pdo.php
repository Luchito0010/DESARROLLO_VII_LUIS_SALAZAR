<?php
require_once "config_pdo.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Obtenemos y sanitizamos las entradas
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $nuevo_email = $_POST['nuevo_email'];

    // Consulta SQL para actualizar el email de un usuario
    $sql = "UPDATE usuarios SET email = :nuevo_email WHERE nombre = :nombre AND email = :email";
    
    if ($stmt = $pdo->prepare($sql)) {
        // Vinculamos los parámetros
        $stmt->bindParam(':nuevo_email', $nuevo_email, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        
        // Ejecutamos la consulta
        if ($stmt->execute()) {
            echo "Usuario actualizado con éxito.";
        } else {
            echo "ERROR: No se pudo ejecutar la consulta. " . $stmt->errorInfo()[2];
        }
    }

    // Cerramos el statement
    unset($stmt);
}

// Cerramos la conexión PDO
unset($pdo);
?>
