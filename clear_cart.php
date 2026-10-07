<?php
session_start();

// Delete all products from the cart[cite: 3]
unset($_SESSION['cart']);

// Capturamos la URL de la página anterior, o usamos 'index.php' como respaldo si no está disponible
$redirect_url = $_SERVER['HTTP_REFERER'] ?? 'index.php';

// Redireccionamos de regreso a la página en que se llamó
header('Location: ' . $redirect_url);
exit;
?>