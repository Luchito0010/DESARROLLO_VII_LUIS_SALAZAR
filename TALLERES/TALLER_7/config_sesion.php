<?php
// Verificar si ya se ha iniciado una sesión
if (session_status() === PHP_SESSION_NONE) {
    // Configurar sesiones con medidas de seguridad adicionales
    ini_set('session.cookie_httponly', 1);   // Hacer que las cookies de sesión solo sean accesibles a través de HTTP (previene ataques XSS)
    ini_set('session.cookie_secure', 1);     // Asegurar que las cookies de sesión solo se envíen a través de HTTPS (cambiar a 0 si no usas HTTPS)
    ini_set('session.use_strict_mode', 1);   // Evitar que se utilicen ID de sesión no válidos
    ini_set('session.use_only_cookies', 1);  // Asegurarse de que las sesiones solo se inicien a través de cookies

    // Iniciar la sesión
    session_start([
        'cookie_lifetime' => 86400, // 1 día
        'cookie_secure' => true,    // Solo para HTTPS
        'cookie_httponly' => true,  // Para evitar acceso a la cookie desde JavaScript
        'use_strict_mode' => true,
        'sid_length' => 128,
    ]);

    // Regenerar el ID de sesión en transacciones importantes para prevenir ataques de fijación de sesión
    session_regenerate_id(true);
}

// Evitar ataques de fijación de sesión
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}
?>
