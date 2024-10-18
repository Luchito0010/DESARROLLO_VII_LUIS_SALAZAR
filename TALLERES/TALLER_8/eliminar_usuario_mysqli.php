<?php
require_once "config_mysqli.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $sql = "DELETE FROM usuarios WHERE nombre = ? AND email = ?";
    
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "ss", $nombre, $email);
        
        if(mysqli_stmt_execute($stmt)){
            echo "Usuario eliminado con éxito.";
        } else{
            echo "ERROR: No se pudo ejecutar la consulta. " . mysqli_error($conn);
        }
    } else {
        echo "ERROR: No se pudo preparar la consulta. " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
