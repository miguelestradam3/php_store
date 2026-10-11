<?php

session_start();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION['cart_error'] = 'Invalid request. Please try again.';
        header('Location: cart.php');
        exit;
    }

    $producto_id = filter_var($_POST['producto_id'] ?? null, FILTER_VALIDATE_INT);

    $quantity = filter_var($_POST['quantity'] ?? null, FILTER_VALIDATE_INT);

    if ($producto_id === false || $producto_id === null || $producto_id <= 0) {
        $_SESSION['cart_error'] = 'No se ha podido actualizar el producto. Inténtalo de nuevo.';

    } elseif ($quantity === false || $quantity === null || $quantity < 0) {
        $_SESSION['cart_error'] = 'Introduce una cantidad válida, que puede ser cero o superior.';

    } elseif (!isset($_SESSION['cart'][$producto_id])) {
        $_SESSION['cart_error'] = 'No se ha encontrado el producto en tu carrito.';

    } elseif ($quantity === 0) {
        unset($_SESSION['cart'][$producto_id]);

        $_SESSION['cart_success'] = 'El producto se ha eliminado correctamente de tu carrito.';

    } else {
        $_SESSION['cart'][$producto_id] = $quantity;

        $_SESSION['cart_success'] ='La cantidad del carrito se ha actualizado correctamente.';
    }

} catch (Throwable $e) {
    error_log(
        'Error al actualizar la cantidad del carrito: ' . $e->getMessage()
    );

    $_SESSION['cart_error'] = 'Se ha producido un error al actualizar tu carrito. Inténtalo de nuevo.';
}

header('Location: cart.php');
exit;