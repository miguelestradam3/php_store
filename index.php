<?php
    session_start();

    // 1. Bring in the connection file
    require_once 'connection.php';

    // Sql to check if table exists
    $sql_table = "SELECT * FROM `productos`";

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
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (isset($_SESSION['cart_error'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= htmlspecialchars($_SESSION['cart_error']) ?>

                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="alert"
                                            aria-label="Close">
                                    </button>
                                </div>
                                <?php unset($_SESSION['cart_error']); ?>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['cart_success'])): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?= htmlspecialchars($_SESSION['cart_success']) ?>

                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="alert"
                                            aria-label="Close">
                                    </button>
                                </div>
                                <?php unset($_SESSION['cart_success']); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
            <section class="section">
                <div class="container section-title" data-aos="fade-up">
                    <p>Artículos disponibles en el mercado</p>
                    <h2>Artículos disponibles en el mercado</h2>
                </div>
                <div class="container">
                    <?php
                    try {
                        // Verificamos que la conexión exista y sea un objeto mysqli válido
                        if (!isset($conn) || !($conn instanceof mysqli)) {
                            throw new Exception("La conexión a la base de datos no está inicializada.");
                        }

                        // Intentamos ejecutar la consulta de la tabla de productos
                        $res = $conn->query($sql_table);

                        if ($res && $res->num_rows > 0) { 
                            echo "<div class='row'>";
                            while ($row = $res->fetch_array()) { 
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
                                                    <i class="bi bi-cart-plus"></i> Agregar al carrito
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                ';
                            } 
                            echo '</div>'; 
                        } else { 
                            echo "<div class='alert alert-info'>No se han encontrado registros que coincidan.</div>"; 
                        }

                    } catch (Exception $e) {
                        // Registramos el detalle técnico en el log del servidor de forma silenciosa
                        error_log("Error crítico al consultar productos: " . $e->getMessage());

                        // Mostramos un mensaje seguro para el usuario final sin romper la página
                        echo "<div class='alert alert-danger'>Ocurrió un error al cargar los productos. Por favor, inténtalo más tarde.</div>";
                    }
                    ?>
                </div>
            </section>
            <!-- Contact Section -->
            <section id="contact" class="dark-background contact section">

            <!-- Section Title -->
            <div class="container section-title">
                <h2>Contacto</h2>
                <p>Tanto si tienes alguna pregunta, un proyecto en mente o simplemente quieres ponerte en contacto conmigo, no dudes en hacerlo. Haré todo lo posible por responderte lo antes posible.</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-4">

                <div class="col-lg-12">
                    <form action="contact.php" method="POST" class="email-form">
                        <div class="row gy-4">

                            <div class="col-md-6">
                            <input type="text" id="name" name="name" class="form-control" placeholder="Su nombre" required>
                            </div>

                            <div class="col-md-6">
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="Su numero de telefono" required>
                            </div>

                            <div class="col-md-12">
                            <input type="email" id="email" class="form-control" name="email" placeholder="Su Email (correo electronico)" required>
                            </div>

                            <div class="col-md-12">
                            <textarea class="form-control" id="subject" name="subject" rows="2" placeholder="Mensaje" required></textarea>
                            </div>

                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary w-100" type="submit">Enviar mensaje</button>
                            </div>

                        </div>
                    </form>
                </div>

                </div>

            </div>

            </section>
            <!-- /Contact Section -->
        </main>
        <footer id="footer" class="footer dark-background">
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