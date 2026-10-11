<?php

session_start();

require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['cart_error'] = 'Se ha intentado acceder a la funcion de agregar un carro sin usar los metodos disponibles.';
    error_log($_SESSION['cart_error']);
    header('Location: index.php');
    exit;
}

$producto_id = intval($_POST['producto_id'] ?? 0);

if ($producto_id <= 0) {
    $_SESSION['cart_error'] = 'Codigo del producto no es valido.';
    error_log($_SESSION['cart_error']);
    header('Location: index.php');
    exit;
}

try {
    // Verificar que el producto exista utilizando una consulta preparada
    $stmt = $conn->prepare(
        "SELECT codigo, nombre, precio, imagen
         FROM productos
         WHERE codigo = ?"
    );

    $stmt->bind_param("i", $producto_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt->close();
        $_SESSION['cart_error'] = 'No existe un producto con ese codigo.';
        error_log($_SESSION['cart_error']);
        header('Location: index.php');
        exit;
    }

    $stmt->close();

} catch (Exception $e) {

    $_SESSION['cart_error'] = "Error crítico al agregar producto al carrito: " . $e->getMessage();
    // Registramos el error real en los logs del servidor de forma silenciosa
    error_log($_SESSION['cart_error']);

    // Redirigimos de forma segura para evitar romper la experiencia del usuario
    header('Location: index.php');
    exit;
}

// Crear el carrito si no existe
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Agregar producto o incrementar su cantidad
if (isset($_SESSION['cart'][$producto_id])) {
    $_SESSION['cart'][$producto_id]++;
    $_SESSION['cart_success'] = 'El producto se ha añadido correctamente a tu carrito.';
} else {
    $_SESSION['cart'][$producto_id] = 1;
    $_SESSION['cart_success'] = 'El producto se ha añadido correctamente a tu carrito.';
}

header('Location: index.php');
exit;