<?php

session_start();

require_once 'connection.php';

// Make sure the request comes from the checkout form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Get email
$email = trim($_POST['email'] ?? '');

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: checkout.php?error=email');
    exit;
}

// Get cart
$cart = $_SESSION['cart'] ?? [];

// Make sure cart isn't empty
if (empty($cart)) {
    header('Location: index.php');
    exit;
}

try {

    // Start transaction
    $conn->begin_transaction();

    $total = 0;

    /*
     * First calculate the total and verify
     * that every product still exists.
     */

    $products = [];

    foreach ($cart as $producto_id => $quantity) {

        $producto_id = intval($producto_id);
        $quantity = intval($quantity);

        if ($producto_id <= 0 || $quantity <= 0) {
            continue;
        }

        $stmt = $conn->prepare(
            "SELECT codigo, nombre, precio
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

        $precio = (float) $producto['precio'];

        $subtotal = $precio * $quantity;

        $total += $subtotal;

        $products[] = [
            'id' => $producto_id,
            'quantity' => $quantity,
            'price' => $precio,
            'subtotal' => $subtotal
        ];
    }

    // Make sure we actually have products
    if (empty($products)) {
        throw new Exception('El carrito no contiene productos válidos.');
    }

    /*
     * Create the order
     */

    $stmt = $conn->prepare(
        "INSERT INTO ordenes (email, total)
         VALUES (?, ?)"
    );

    $stmt->bind_param(
        "sd",
        $email,
        $total
    );

    $stmt->execute();

    // Get the new order ID
    $orden_id = $conn->insert_id;


    /*
     * Insert each product into detalle_orden
     */

    $stmt = $conn->prepare(
        "INSERT INTO detalle_orden
        (orden_id, producto_id, cantidad, precio, subtotal)
        VALUES (?, ?, ?, ?, ?)"
    );

    foreach ($products as $product) {

        $stmt->bind_param(
            "iiidd",
            $orden_id,
            $product['id'],
            $product['quantity'],
            $product['price'],
            $product['subtotal']
        );

        $stmt->execute();
    }


    /*
     * Everything worked
     */

    $conn->commit();

    /*
     * Clear the shopping cart
     */

    unset($_SESSION['cart']);

    /*
     * Send customer to confirmation page
     */

    $_SESSION['order'] = $orden_id;
    header(
        'Location: order_confirmation.php'
    );

    exit;

} catch (Exception $e) {

    /*
     * Something went wrong.
     * Undo the database changes.
     */

    $conn->rollback();

    error_log("No se pudo procesar el pedido:: " . $e->getMessage());

    die(
        "No se pudo procesar el pedido: "
        . htmlspecialchars($e->getMessage())
    );
}