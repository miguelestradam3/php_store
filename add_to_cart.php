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

// Verify that the product exists
$stmt = $conn->prepare(
    "SELECT codigo, nombre, precio, imagen
     FROM productos
     WHERE codigo = ?"
);

$stmt->bind_param("i", $producto_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: index.php');
    exit;
}

// Create cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product or increase quantity
if (isset($_SESSION['cart'][$producto_id])) {
    $_SESSION['cart'][$producto_id]++;
} else {
    $_SESSION['cart'][$producto_id] = 1;
}

header('Location: index.php');
exit;