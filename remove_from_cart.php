<?php
try {
    session_start();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: cart.php');
        exit;
    }

    $producto_id = intval($_POST['producto_id'] ?? 0);

    if ($producto_id > 0 && isset($_SESSION['cart'][$producto_id])) {
        unset($_SESSION['cart'][$producto_id]);
    }

    header('Location: cart.php');
    exit;

} catch (Exception $e) {
    // Registramos el error técnico en los logs del servidor de forma silenciosa
    error_log("Error crítico al eliminar producto del carrito: " . $e->getMessage());

    // Redirección de respaldo segura
    header('Location: cart.php');
    exit;
}