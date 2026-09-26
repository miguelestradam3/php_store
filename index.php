<?php
    // 1. Bring in the connection file
    require_once 'connection.php';

    // Sql to check if table exists
    $sql_table = "SELECT * FROM `productos`";

?>
<html lang="es">
    <head>
        
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <title>PHP Avanzado - Fidelitas</title>

        <!-- Favicons -->
        <link href="docs/img/phpicon.png" rel="icon">
        <link href="docs/img/phpicon.png" rel="apple-touch-icon">

        <!-- CSS assets -->
        <link href="assets/css/main.css" rel="stylesheet" type="text/css">


        <!-- Bootstrap -->
        <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

        <!-- Main CSS File -->
        <link href="assets/css/main.css" rel="stylesheet">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com" rel="preconnect">
        <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    </head>
    <body class="index-page">
        <header id="header" class="header d-flex align-items-center sticky-top">
            <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
                <a href="index.php" class="logo d-flex align-items-center">
                    <h1 class="sitename">PHP Avanzado</h1>
                </a>
            </div>
        </header>
        <main class="main">
            <section class="section">
                <div class="container section-title" data-aos="fade-up">
                    <p>Artículos disponibles en el mercado</p>
                    <h2>Artículos disponibles en el mercado</h2>
                </div>
                <div class="container">
                    <?php

                        $res = $conn->query($sql_table);
                        if ($res) { 
                            if ($res->num_rows > 0) { 
                                echo "<div class='row'>";
                                while ($row = $res->fetch_array())  
                                { 
                                    echo '
                                    <div class="col-lg-3 col-md-3 p-1">
                                        <div class="card h-100">
                                            <img src="'.htmlspecialchars($row['imagen']).'" class="card-img-top" alt="Imagen del producto">
                                            <div class="card-body">
                                                <h5 class="card-title">'.htmlspecialchars($row['nombre']).'</h5>
                                                <p class="card-text"><strong>Detalle:</strong> '.htmlspecialchars($row['detalle']).'</p>
                                                <p class="card-text"><strong>Precio:</strong> $'.number_format($row['precio'], 2).'</p>
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
                </div>
            </section>
        </main>
        <footer id="footer" class="footer light-background">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-4 col-lg-4 mb-3 mb-md-0">
                    <div class="widget">
                        <h3 class="widget-heading">Especialización en Diseño y Desarrollo Web</h3>
                        <p class="mb-4">
                            Aqui aprendere a desarrollar sitios web completos, integrando diseño visual y programación, dos habilidades clave en el entorno digital actual. 
                        </p>
                    </div>
                    </div>
                    <div class="col-md-4 col-lg-4 pl-lg-5">
                        <div class="widget">
                            <h3 class="widget-heading">En constante crecimiento</h3>
                            <ul class="list-unstyled footer-blog-entry">
                            <li>
                                <span class="d-block date">PHP Avanzado</span>
                                <a href="#">
                                    Entre otras cosas, en este curso aprendere a aplicar los métodos que proporciona el protocolo HTTP y tipos de datos en PHP para el envío e implementación de datos en formularios del servidor<br>Asi como el uso de <strong>bases de datos</strong> para el almacenamiento de informacion
                                </a>
                            </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 pl-lg-5">
                        <div class="widget">
                            <h3 class="widget-heading">Siguenos en Github</h3>
                            <ul class="list-unstyled social-icons light mb-3">
                                <li>
                                    <a href="#"><span class="bi bi-github"></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <link href="assets/vendor/bootstrap/js/bootstrap.bundle.min.js" rel="stylesheet">
        <!-- Custom JavaScript --> 
         <script src="assets/javascript/main.js"></script>
    </body