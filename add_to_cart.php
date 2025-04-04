<?php
session_start();

if (isset($_POST['product_id'], $_POST['product_name'], $_POST['product_price'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];

    // Если корзина еще не создана, создаем ее
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Если товар уже есть в корзине, увеличиваем количество
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        // Добавляем товар в корзину
        $_SESSION['cart'][$productId] = [
            'name' => $productName,
            'price' => $productPrice,
            'quantity' => 1
        ];
    }

    // Обновляем количество товаров в корзине
    $cartCount = 0;
    foreach ($_SESSION['cart'] as $product) {
        $cartCount += $product['quantity'];
    }

    // Отправляем статус успешного добавления
    echo json_encode([
        'status' => 'success',
        'cartCount' => $cartCount
    ]);
} else {
    echo json_encode(['status' => 'error']);
}
?>
