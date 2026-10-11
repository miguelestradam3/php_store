<?php
try {
    session_start();

    // Delete all products from the cart
    unset($_SESSION['cart']);

    // Capturamos la URL de la página anterior, o usamos 'index.php' como respaldo si no está disponible
    $redirect_url = $_SERVER['HTTP_REFERER'] ?? 'index.php';

    $_SESSION['cart_success'] = 'El carrito se ha vaciado correctamente.';

    // Redireccionamos de regreso a la página en que se llamó
    header('Location: ' . $redirect_url);
    exit;

} catch (Exception $e) {
    // Registramos el error técnico en los logs del servidor de forma silenciosa
    error_log("Error crítico al vaciar el carrito: " . $e->getMessage());

    $_SESSION['cart_error'] = 'El carrito no se ha podido vaciar';

    // Redirección de respaldo segura para evitar que la página falle en blanco
    header('Location: index.php');
    exit;
}
?>