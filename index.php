<?php
    session_start();

    // 1. Bring in the connection file
    require_once 'connection.php';

    // Sql to check if table exists
    $sql_table = "SELECT * FROM `productos`";

    mysqli_set_charset($conn, "utf8mb4");

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <!-- utf-8 charset -->
        <meta charset="utf-8">

        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <title>PHP Avanzado - Fidelitas</title>

        <!-- Favicons -->
        <link href="assets/img/phpicon.png" rel="icon">
        <link href="assets/img/phpicon.png" rel="apple-touch-icon">

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
        <header id="header" class="dark-background header d-flex align-items-center sticky-top">
            <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

                <a href="index.php" class="logo d-flex align-items-center">
                    <h1 class="sitename">PHP Avanzado</h1>
                </a>

                <a href="cart.php" class="btn btn-primary">
                    <i class="bi bi-cart"></i>
                    Carrito

                    <?php
                    $cart_count = 0;

                    if (isset($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $quantity) {
                            $cart_count += $quantity;
                        }
                    }

                    echo "($cart_count)";
                    ?>
                </a>
                <a
                    href="clear_cart.php"
                    class="btn btn-danger ms-2"
                    onclick="return confirm('¿Estás seguro de que quieres vaciar el carrito?');"
                >
                    <i class="bi bi-trash"></i>
                    Vaciar carrito
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
                                                <form action="add_to_cart.php" method="POST">
                                                    <input type="hidden" name="producto_id" value="'.$row['codigo'].'">

                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <i class="bi bi-cart-plus"></i>
                                                        Agregar al carrito
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    ';
                                } 
                                echo '</div>'; 
                            } 
                            else { 
                                echo "No se han encontrado registros que coincidan."; 
                            } 
                        } 
                        else { 
                            die("ERROR: No se ha podido ejecutar la consulta. " . $conn->error); 
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
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Custom JavaScript --> 
         <script src="assets/javascript/main.js"></script>
    </body>
</html>