<?php

session_start();

require_once 'connection.php';

$orden_id = intval($_SESSION['order'] ?? 0);

unset($_SESSION['order']);

if ($orden_id <= 0) {
    header('Location: index.php');
    exit;
}

// Get order
$stmt = $conn->prepare(
    "SELECT id, email, fecha, total
     FROM ordenes
     WHERE id = ?"
);

$stmt->bind_param("i", $orden_id);

$stmt->execute();

$result = $stmt->get_result();

$orden = $result->fetch_assoc();

if (!$orden) {
    header('Location: index.php');
    exit;
}

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Pedido confirmado</title>

    <!-- Favicons -->
    <link href="assets/img/phpicon.png" rel="icon">
    <link href="assets/img/phpicon.png" rel="apple-touch-icon">


    <link
        href="assets/vendor/bootstrap/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="assets/vendor/bootstrap-icons/bootstrap-icons.css"
        rel="stylesheet"
    >

    <link
        href="assets/css/main.css"
        rel="stylesheet"
    >

</head>

<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card shadow text-center">

                <div class="card-body p-5">

                    <i
                        class="bi bi-check-circle-fill text-success"
                        style="font-size: 5rem;"
                    ></i>

                    <h1 class="mt-4">
                        ¡Pedido confirmado!
                    </h1>

                    <p class="lead">
                        Gracias por tu compra.
                    </p>

                    <hr>

                    <p>
                        <strong>Número de pedido:</strong>
                        #<?= $orden['id'] ?>
                    </p>

                    <p>
                        <strong>Correo:</strong>
                        <?= htmlspecialchars($orden['email']) ?>
                    </p>

                    <p>
                        <strong>Fecha:</strong>
                        <?= htmlspecialchars($orden['fecha']) ?>
                    </p>

                    <h3 class="text-success mt-4">

                        Total:
                        $<?= number_format($orden['total'], 2) ?>

                    </h3>

                    <div class="alert alert-info mt-4">

                        <i class="bi bi-envelope"></i>

                        Te contactaremos a través del correo
                        proporcionado para darte información
                        sobre tu pedido.

                    </div>

                    <a
                        href="index.php"
                        class="btn btn-primary mt-3"
                    >

                        <i class="bi bi-shop"></i>

                        Volver a la tienda

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>