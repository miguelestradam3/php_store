<?php

try {
    session_start();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: cart.php');
        exit;
    }

    $producto_id = intval($_POST['producto_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 0);

    if ($producto_id <= 0) {
        header('Location: cart.php');
        exit;
    }

    if ($quantity <= 0) {

        unset($_SESSION['cart'][$producto_id]);

    } else {

        $_SESSION['cart'][$producto_id] = $quantity;

    }

    header('Location: cart.php');
    exit;

} catch (Exception $e) {
    // Registramos el error técnico en los logs del servidor de forma silenciosa
    error_log("Error crítico al modificar la cantidad de un producto del carrito: " . $e->getMessage());

    // Redirección de respaldo segura
    header('Location: cart.php');
    exit;
}