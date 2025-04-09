<?php
session_start();

$cart_count = 0;
foreach ($_SESSION['cart'] as $product) {
    $cart_count += $product['quantity'];
}


echo json_encode(['cartCount' => $cart_count]);
exit();
?>
