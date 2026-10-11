<?php

session_start();

require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$producto_id = intval($_POST['producto_id'] ?? 0);

if ($producto_id <= 0) {
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
        header('Location: index.php');
        exit;
    }

    $stmt->close();

} catch (Exception $e) {
    // Registramos el error real en los logs del servidor de forma silenciosa
    error_log("Error crítico al agregar producto al carrito: " . $e->getMessage());

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
} else {
    $_SESSION['cart'][$producto_id] = 1;
}

header('Location: index.php');
exit;