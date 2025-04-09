<?php
session_start();
include 'config.php';

$total = 0;

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p class='empty-cart'>Ваша корзина пуста</p>";
} else {
    echo "<h2>Ваша корзина</h2>";
    
    foreach ($_SESSION['cart'] as $product_id => $product) {
        $subtotal = $product['quantity'] * $product['price'];
        $total += $subtotal;

        echo "<div class='cart-item' data-id='$product_id'>";
        echo "<p>" . $product['name'] . "</p>";
        echo "<p>Цена: " . $product['price'] . " ₽</p>";
        echo "<button class='quantity-decrease' data-id='$product_id'>-</button>";
        echo "<span class='quantity'>" . $product['quantity'] . "</span>";
        echo "<button class='quantity-increase' data-id='$product_id'>+</button>";
        echo "<p>Итого: " . $subtotal . " ₽</p>";

        echo "<button class='remove-item' data-id='$product_id'>
            <span class='MuiIconButton-label'>
                <img src='img/trash.svg' alt='' class='remove-icon' width='20' height='20'>
            </span>
        </button>";
        echo "</div>";
    }

    echo "<h3>Общая сумма: " . $total . " ₽</h3>";
    echo "<a href='order.php' class='checkout-button'>Перейти к оформлению</a>";
}
?>
