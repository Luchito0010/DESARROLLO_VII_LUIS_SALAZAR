<?php
require_once "config_pdo.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    $sql = "DELETE FROM usuarios WHERE nombre = :nombre AND email = :email";
    
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            echo "Usuario eliminado con éxito.";
        } else {
            echo "ERROR: No se pudo ejecutar la consulta. " . $stmt->errorInfo()[2];
        }
    }

    unset($stmt);
}

unset($pdo);
?>
