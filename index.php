<?php
    // 1. Bring in the connection file
    require_once 'connection.php';

    // Sql to check if table exists
    $sql_sub_check_table = "SELECT * FROM `productos`";

?>
<html lang="es">
    <head>
        
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <title>PHP CRUD</title>

        <!-- Favicons -->
        <link href="docs/img/phpicon.png" rel="icon">
        <link href="docs/img/phpicon.png" rel="apple-touch-icon">

        <!-- CSS assets -->
        <link href="assets/css/main.css" rel="stylesheet" type="text/css">


        <!-- Bootstrap -->
        <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com" rel="preconnect">
        <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    </head>
    <body class="index-page">>
    <?php

        $res = $conn->query($sql_sub_check_table);
        if ($res) { 
            if ($res->num_rows > 0) { 
                echo "<div class='row'>";
                while ($row = $res->fetch_array())  
                { 
                    echo '
                    <div class="col-lg-4 p-1">
                        <div class="card h-100">
                            <img src="'.htmlspecialchars($row['imagen']).'" class="card-img-top" alt="Imagen del producto">
                            <div class="card-body">
                                <h5 class="card-title">'.htmlspecialchars($row['nombre']).'</h5>
                                <p class="card-text"><strong>Detalle:</strong> '.htmlspecialchars($row['detalle']).'</p>
                                <p class="card-text"><strong>Precio:</strong> $'.number_format($row['precio'], 2).'</p>
                                <p class="card-text"><small class="text-muted">Código: '.$row['codigo'].'</small></p>
                                
                                <form action="" method="post" class="d-inline">
                                    <input type="hidden" name="EditCodigo" value="'.$row['codigo'].'">
                                    <button name="EditProducto" class="btn btn-primary btn-sm" type="submit"> Editar </button>
                                </form>
                                
                                <form action="" method="post" class="d-inline">
                                    <input type="hidden" name="DeleteCodigo" value="'.$row['codigo'].'">
                                    <button name="DeleteProductoButton" class="btn btn-danger btn-sm" type="submit"> Eliminar </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    ';
                } 
                echo '</div>'; 
            } 
            else { 
                echo "No matching records are found."; 
            } 
        } 
        else { 
            die("ERROR: Could not able to execute query. " . $conn->error); 
        }
    ?>
    </body