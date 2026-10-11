<?php
session_start();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION['cart_error'] = 'Invalid request. Please try again.';
        header('Location: cart.php');
        exit;
    }

    $producto_id = intval($_POST['producto_id'] ?? 0);

    if ($producto_id <= 0 || !isset($_SESSION['cart'][$producto_id])) {
        $_SESSION['cart_error'] = 'No se ha podido eliminar el producto porque no se ha encontrado en tu carrito.';
    } else {
        unset($_SESSION['cart'][$producto_id]);

        $_SESSION['cart_success'] = 'El producto se ha eliminado correctamente de tu carrito.';
    }

} catch (Throwable $e) {
    // Save technical details in the server log
    error_log(
        'Error al eliminar el producto del carrito: ' . $e->getMessage()
    );

    // Show a safe message to the user
    $_SESSION['cart_error'] = 'Se ha producido un error al eliminar el producto. Inténtalo de nuevo.';
}

header('Location: cart.php');
exit;