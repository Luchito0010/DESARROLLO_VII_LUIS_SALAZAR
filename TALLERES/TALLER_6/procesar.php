<?php
require_once 'validaciones.php';
require_once 'sanitizacion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errores = [];
    $datos = [];

    $camposObligatorios = ['nombre', 'email', 'fecha_nacimiento'];
    foreach ($camposObligatorios as $campo) {
        if (empty($_POST[$campo])) {
            $errores[] = "El campo $campo es obligatorio.";
        } else {
            $valor = $_POST[$campo];
            $funcionSanitizar = "sanitizar" . ucfirst($campo);
            $datos[$campo] = call_user_func($funcionSanitizar, $valor);
        }
    }

    // Validación opcional para sitioWeb
    if (!empty($_POST['sitioWeb'])) {
        $datos['sitioWeb'] = sanitizarSitioWeb($_POST['sitioWeb']);
        if (!validarSitioWeb($datos['sitioWeb'])) {
            $errores[] = "El campo sitioWeb no es válido.";
        }
    }

    // Cálculo de la edad si la fecha de nacimiento es válida
    if (isset($datos['fecha_nacimiento'])) {
        try {
            $fechaNacimiento = new DateTime($datos['fecha_nacimiento']);
            $edad = $fechaNacimiento->diff(new DateTime())->y;
            $datos['edad'] = $edad;

            if ($edad < 18 || $edad > 120) {
                $errores[] = "La edad debe estar entre 18 y 120 años.";
            }
        } catch (Exception $e) {
            $errores[] = "Fecha de nacimiento no válida.";
        }
    }

    // Validación y carga de la foto de perfil con nombre único
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_NO_FILE) {
        if (!validarFotoPerfil($_FILES['foto_perfil'])) {
            $errores[] = "La foto de perfil no es válida.";
        } else {
            $nombreUnico = uniqid() . "_" . basename($_FILES['foto_perfil']['name']);
            $rutaDestino = 'uploads/' . $nombreUnico;

            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $rutaDestino)) {
                $datos['foto_perfil'] = $rutaDestino;
            } else {
                $errores[] = "Error al subir la foto de perfil.";
            }
        }
    }

    if (empty($errores)) {
        echo "<h2>Datos Recibidos:</h2>";
        foreach ($datos as $campo => $valor) {
            if ($campo === 'foto_perfil') {
                echo "$campo: <img src='$valor' width='100'><br>";
            } else {
                echo "$campo: $valor<br>";
            }
        }

        $archivo = 'registros.json';
        $registros = file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : [];
        $registros[] = $datos;
        file_put_contents($archivo, json_encode($registros));
    } else {
        echo "<h2>Errores:</h2>";
        foreach ($errores as $error) {
            echo "$error<br>";
        }
        echo "<br><a href='formulario.html'>Volver al formulario</a>";
    }
} else {
    echo "Acceso no permitido.";
}
?>
