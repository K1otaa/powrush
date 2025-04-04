<?php
session_start();
include 'config.php';

$product_id = $_GET['product_id'];

// Удаляем товар из корзины
if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
}

// Перенаправляем на страницу корзины
header("Location: cart.php");
exit();
?>
