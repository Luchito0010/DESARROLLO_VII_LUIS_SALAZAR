<?php
require_once "config_mysqli.php";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Sanitizamos las entradas
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $nuevo_email = mysqli_real_escape_string($conn, $_POST['nuevo_email']); // Nuevo email

    // Consulta SQL para actualizar
    $sql = "UPDATE usuarios SET email = ? WHERE nombre = ? AND email = ?";
    
    if($stmt = mysqli_prepare($conn, $sql)){
        // Vinculamos los parámetros
        mysqli_stmt_bind_param($stmt, "sss", $nuevo_email, $nombre, $email);
        
        // Ejecutamos la consulta
        if(mysqli_stmt_execute($stmt)){
            echo "Usuario actualizado con éxito.";
        } else {
            echo "ERROR: No se pudo ejecutar la consulta. " . mysqli_error($conn);
        }
    } else {
        echo "ERROR: No se pudo preparar la consulta. " . mysqli_error($conn);
    }

    // Cerramos el statement
    mysqli_stmt_close($stmt);
}

// Cerramos la conexión
mysqli_close($conn);

