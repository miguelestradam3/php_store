<?php

session_start();

require_once 'connection.php';

// Make sure the request comes from the checkout form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['cart_error'] = 'Error de acceso: Se intento acceder normalmente a una pagina que se encuentra dentro de un flujo (POST).';
    error_log($_SESSION['cart_error']);
    header('Location: index.php');
    exit;
}

// Get email
$email = trim($_POST['email'] ?? '');

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['cart_error'] = 'Error de input: El correo que se ha introducido no es un correo como tal.';
    error_log($_SESSION['cart_error']);
    header('Location: checkout.php');
    exit;
}

// Get cart
$cart = $_SESSION['cart'] ?? [];

// Make sure cart isn't empty
if (empty($cart)) {
    $_SESSION['cart_error'] = 'El carrito se encuentra vacio, no es posible procesar la compra.';
    error_log($_SESSION['cart_error']);
    header('Location: index.php');
    exit;
}

try {
    
    // Verificamos que la conexión sea válida antes de iniciar la transacción
    if (!isset($conn) || !($conn instanceof mysqli)) {
        throw new Exception("La conexión a la base de datos no está inicializada.");
    }

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

    if (!isset($conn) || !($conn instanceof mysqli)) {
        $_SESSION['cart_error'] = 'Fallo crítico de conexión a MySQL: Error en procesar la orden';
        error_log($_SESSION['cart_error']);
    } else {
        $conn->rollback();
    }

    $_SESSION['cart_error'] = 'No se pudo procesar el pedido.';
    error_log("No se pudo procesar el pedido: " . $e->getMessage());

    header('Location: cart.php');
}