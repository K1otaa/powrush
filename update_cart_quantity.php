<?php
session_start();
header('Content-Type: application/json');

$product_id = $_POST['product_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$product_id || !$action) {
    echo json_encode(["status" => "error", "message" => "Не переданы параметры"]);
    exit;
}

if (!isset($_SESSION['cart'][$product_id])) {
    echo json_encode(["status" => "error", "message" => "Товар не найден в корзине"]);
    exit;
}

if ($action === 'increase') {
    $_SESSION['cart'][$product_id]['quantity']++;
} elseif ($action === 'decrease') {
    if ($_SESSION['cart'][$product_id]['quantity'] > 1) {
        $_SESSION['cart'][$product_id]['quantity']--;
    } else {
        unset($_SESSION['cart'][$product_id]);
    }
}

$totalCount = array_sum(array_column($_SESSION['cart'], 'quantity'));
$cartHtml = ''; // Обновляем корзину HTML

foreach ($_SESSION['cart'] as $product_id => $product) {
    $cartHtml .= "<div class='cart-item' data-id='$product_id'>
                    <p>" . $product['name'] . "</p>
                    <p>Цена: " . $product['price'] . " ₽</p>
                    <button class='quantity-decrease' data-id='$product_id'>-</button>
                    <span class='quantity'>" . $product['quantity'] . "</span>
                    <button class='quantity-increase' data-id='$product_id'>+</button>
                    <p>Итого: " . ($product['quantity'] * $product['price']) . " ₽</p>
                    <button class='remove-item' data-id='$product_id'>Удалить</button>
                </div>";
}

echo json_encode(["status" => "success", "cartCount" => $totalCount, "cartHtml" => $cartHtml]);
exit;
?>
