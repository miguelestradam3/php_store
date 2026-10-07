<?php

session_start();

require_once 'connection.php';

$cart = $_SESSION['cart'] ?? [];

$total = 0;

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Carrito de compras</title>

    <!-- Favicons -->
    <link href="assets/img/phpicon.png" rel="icon">
    <link href="assets/img/phpicon.png" rel="apple-touch-icon">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

    <link
        href="assets/vendor/bootstrap/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="assets/vendor/bootstrap-icons/bootstrap-icons.css"
        rel="stylesheet"
    >

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

</head>

<body class="index-page">
<header id="header" class="dark-background header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        <a href="index.php" class="logo d-flex align-items-center text-decoration-none">
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

        <a href="index.php" class="btn btn-light">
            <i class="bi bi-arrow-left"></i>
            Seguir comprando
        </a>
    </div>
</header>

<main class="main">
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php if (empty($cart)): ?>

                        <div class="alert alert-info text-center">

                            <i class="bi bi-cart-x"></i>

                            El carrito está vacío.

                        </div>

                    <?php else: ?>

                        <div class="table-responsive">

                            <div class="container">
                                <div class="row">
                                    <p>Cuando se actualiza o cambia la cantidad de un item, para actualizar el <strong>subtotal</strong> y el <strong>total</strong> hay que dar click en el boton:                                 
                                    <button class="btn btn-primary ms-2"><i class="bi bi-arrow-repeat"></i></button> 
                                    junto al display de cantidad</p>
                                </div>
                            </div>

                            <table class="table table-bordered align-middle">

                                <thead class="table-dark">

                                    <tr>

                                        <th>Producto</th>

                                        <th>Precio</th>

                                        <th>Cantidad</th>

                                        <th>Subtotal</th>

                                        <th>Acciones</th>

                                    </tr>

                                </thead>

                                <tbody>

                                <?php

                                foreach ($cart as $producto_id => $quantity):

                                    $stmt = $conn->prepare(
                                        "SELECT codigo, nombre, precio, imagen
                                        FROM productos
                                        WHERE codigo = ?"
                                    );

                                    $stmt->bind_param("i", $producto_id);

                                    $stmt->execute();

                                    $result = $stmt->get_result();

                                    $producto = $result->fetch_assoc();

                                    if (!$producto) {
                                        continue;
                                    }

                                    $subtotal = $producto['precio'] * $quantity;

                                    $total += $subtotal;

                                ?>

                                    <tr>

                                        <td>

                                            <div class="d-flex align-items-center">

                                                <img
                                                    src="<?= htmlspecialchars($producto['imagen']) ?>"
                                                    width="70"
                                                    height="70"
                                                    class="rounded me-3"
                                                    style="object-fit: cover;"
                                                    alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                                >

                                                <strong>
                                                    <?= htmlspecialchars($producto['nombre']) ?>
                                                </strong>

                                            </div>

                                        </td>

                                        <td>

                                            $<?= number_format($producto['precio'], 2) ?>

                                        </td>

                                        <td>

                                            <form
                                                action="update_cart.php"
                                                method="POST"
                                                class="d-flex"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="producto_id"
                                                    value="<?= $producto['codigo'] ?>"
                                                >

                                                <input
                                                    type="number"
                                                    name="quantity"
                                                    value="<?= $quantity ?>"
                                                    min="1"
                                                    class="form-control"
                                                    style="width: 80px;"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-primary ms-2"
                                                >
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>

                                            </form>

                                        </td>

                                        <td>

                                            <strong>
                                                $<?= number_format($subtotal, 2) ?>
                                            </strong>

                                        </td>

                                        <td>

                                            <form
                                                action="remove_from_cart.php"
                                                method="POST"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="producto_id"
                                                    value="<?= $producto['codigo'] ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>


                        <div class="row justify-content-end">

                            <div class="col-md-5">

                                <div class="card">

                                    <div class="card-body">

                                        <h4 class="card-title">
                                            Resumen de compra
                                        </h4>

                                        <hr>

                                        <div class="d-flex justify-content-between">

                                            <span>Total:</span>

                                            <strong>
                                                $<?= number_format($total, 2) ?>
                                            </strong>

                                        </div>

                                        <a
                                            href="checkout.php"
                                            class="btn btn-success w-100 mt-3"
                                        >
                                            <i class="bi bi-check-circle"></i>
                                            Finalizar compra
                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endif; ?>

                </div>
            </div>
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