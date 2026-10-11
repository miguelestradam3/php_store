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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Finalizar compra - PHP Avanzado</title>

    <!-- Favicons -->
    <link href="assets/img/phpicon.png" rel="icon">
    <link href="assets/img/phpicon.png" rel="apple-touch-icon">


    <!-- Bootstrap -->
    <link
        href="assets/vendor/bootstrap/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        href="assets/vendor/bootstrap-icons/bootstrap-icons.css"
        rel="stylesheet"
    >

    <!-- Main CSS -->
    <link
        href="assets/css/main.css"
        rel="stylesheet"
    >

</head>

<body class="index-page">


<header
    id="header"
    class="dark-background header d-flex align-items-center sticky-top"
>

    <div
        class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between"
    >

        <a href="index.php" class="logo d-flex align-items-center text-decoration-none">
            <h1 class="sitename">PHP Avanzado</h1>
        </a>

        <a
            href="cart.php"
            class="btn btn-primary"
        >
            <i class="bi bi-cart"></i>
            Volver al carrito
        </a>

        <a href="index.php" class="btn btn-light">
            <i class="bi bi-arrow-left"></i>
            Seguir comprando
        </a>

    </div>

</header>


<main class="main py-5">

    <section class="section">

        <div class="container">

            <?php if (empty($cart)): ?>

                <div class="text-center">

                    <div class="alert alert-warning">

                        <i class="bi bi-cart-x fs-3"></i>

                        <h4 class="mt-3">
                            Tu carrito está vacío
                        </h4>

                        <p>
                            No hay productos para finalizar la compra.
                        </p>

                    </div>

                    <a
                        href="index.php"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-shop"></i>
                        Volver a la tienda
                    </a>

                </div>

            <?php else: ?>

                <div class="text-center mb-5">

                    <i
                        class="bi bi-check-circle text-success"
                        style="font-size: 4rem;"
                    ></i>

                    <h1 class="mt-3">
                        ¡Solo un paso más!
                    </h1>

                    <p class="lead">
                        Hemos recibido tu solicitud de compra.
                    </p>

                </div>


                <div class="row justify-content-center">

                    <div class="col-lg-8">

                        <div class="card shadow-sm">

                            <div class="card-body p-4">

                                <h3 class="card-title mb-4">

                                    <i class="bi bi-receipt"></i>

                                    Resumen de compra

                                </h3>


                                <?php foreach ($cart as $producto_id => $quantity): ?>

                                    <?php

                                    try {
                                        $stmt = $conn->prepare(
                                            "SELECT codigo, nombre, precio, imagen
                                             FROM productos
                                             WHERE codigo = ?"
                                        );

                                        $stmt->bind_param(
                                            "i",
                                            $producto_id
                                        );

                                        $stmt->execute();

                                        $result = $stmt->get_result();

                                        $producto = $result->fetch_assoc();

                                        $stmt->close();

                                        if (!$producto) {
                                            continue;
                                        }

                                        $subtotal =
                                            $producto['precio'] * $quantity;

                                        $total += $subtotal;

                                    } catch (Exception $e) {
                                        if (isset($stmt) && $stmt instanceof mysqli_stmt) {
                                            $stmt->close();
                                        }
                                        error_log("Error crítico al procesar el producto ID {$producto_id} en el resumen de compra: " . $e->getMessage());
                                        continue;
                                    }

                                    ?>

                                    <div
                                        class="d-flex align-items-center justify-content-between border-bottom py-3"
                                    >

                                        <div
                                            class="d-flex align-items-center"
                                        >

                                            <img
                                                src="<?= htmlspecialchars($producto['imagen']) ?>"
                                                width="70"
                                                height="70"
                                                class="rounded me-3"
                                                style="object-fit: cover;"
                                                alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                            >

                                            <div>

                                                <h5 class="mb-1">

                                                    <?= htmlspecialchars(
                                                        $producto['nombre']
                                                    ) ?>

                                                </h5>

                                                <small class="text-muted">

                                                    Cantidad:
                                                    <?= $quantity ?>

                                                </small>

                                            </div>

                                        </div>


                                        <strong>

                                            $<?= number_format(
                                                $subtotal,
                                                2
                                            ) ?>

                                        </strong>

                                    </div>

                                <?php endforeach; ?>


                                <div
                                    class="d-flex justify-content-between mt-4"
                                >

                                    <h4>
                                        Total:
                                    </h4>

                                    <h4 class="text-success">

                                        $<?= number_format(
                                            $total,
                                            2
                                        ) ?>

                                    </h4>

                                </div>


                                <div
                                    class="alert alert-info mt-4"
                                >

                                    <i class="bi bi-info-circle"></i>

                                    <strong>
                                        Próximamente:
                                    </strong>

                                    El sistema de pagos estará disponible
                                    próximamente.

                                </div>

                                <form action="process_order.php" method="POST">

                                    <div class="mb-3">

                                        <label for="email" class="form-label">
                                            Correo electrónico
                                        </label>

                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            class="form-control"
                                            placeholder="example@email.com"
                                            required
                                        >

                                        <div class="form-text">
                                            Utilizaremos este correo para contactarte sobre tu pedido.
                                        </div>

                                    </div>

                                    <button
                                        type="submit"
                                        class="btn btn-success w-100"
                                    >
                                        <i class="bi bi-check-circle"></i>
                                        Confirmar pedido
                                    </button>

                                </form>


                                <div class="text-center mt-4">

                                    <a
                                        href="index.php"
                                        class="btn btn-primary"
                                    >

                                        <i class="bi bi-shop"></i>

                                        Volver a la tienda

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </section>

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

<script
    src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"
></script>

<script
    src="assets/javascript/main.js"
></script>

</body>

</html>