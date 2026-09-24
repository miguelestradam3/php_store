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
                    die("Connection failed: database not specified");
                }
            } else {
                die("Connection failed: password not specified");
            }
        } else {
            die("Connection failed: username not specified");
        }
    } else {
        die("Connection failed: Server/host not specified");
    }
    if ($conn->connect_error != null){
        die("Connection failed: {$conn->connect_error}");
    }
