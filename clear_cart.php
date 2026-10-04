<?php

session_start();

// Delete all products from the cart
unset($_SESSION['cart']);

// Return to the products page
header('Location: index.php');
exit;