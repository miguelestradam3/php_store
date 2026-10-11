<?php
    // 1. Configuramos MySQLi para que lance excepciones ante cualquier fallo
    mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);

    // Variables de conexión
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tienda";

    try {
        // 2. Intentamos realizar la conexión dentro del bloque try
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Configuramos el juego de caracteres de forma segura
        mysqli_set_charset($conn, "utf8mb4");
        
        // Verificamos explícitamente y lanzamos la excepción si hay un error de conexión
        if ($conn->connect_error != null) {
            throw new Exception("Error de conexión: " . $conn->connect_error);
        }

    } catch (Exception $e) {
        // 3. Registramos el error real en el archivo de log del servidor de forma silenciosa
        error_log("Fallo crítico de conexión a MySQL: " . $e->getMessage());

        // 4. Mostramos un mensaje genérico y seguro para el usuario final
        http_response_code(500);
        $conn = false;
        $_SESSION['cart_error'] = "Fallo crítico de conexión";
    }
?>
