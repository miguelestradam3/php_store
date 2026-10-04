<?php
    // Check to ask for dev or prod info
    $hostname = getenv('HTTP_HOST');

    //variables

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tienda";

    // Create connection
    if (!is_null($servername)){
        if (!is_null($username)){
            if (!is_null($password)){
                if (!is_null($dbname)){
                    $conn = new mysqli($servername, $username, $password, $dbname);
                } else {
                    die("Error de conexión: no se ha especificado la base de datos");
                }
            } else {
                die("Error de conexión: no se ha especificado la contraseña");
            }
        } else {
            die("Error de conexión: no se ha especificado el nombre de usuario");
        }
    } else {
        die("Error de conexión: no se ha especificado el servidor ni el host");
    }
    if ($conn->connect_error != null){
        die("Error de conexión: {$conn->connect_error}");
    }
