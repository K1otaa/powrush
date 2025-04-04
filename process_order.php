<?php
session_start();
include 'config.php';

// Проверка, есть ли товары в корзине
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Ваша корзина пуста.";
    exit();
}

// Проверяем, был ли выбран способ оплаты
if (isset($_POST['payment_method'])) {
    $_SESSION['payment_method'] = $_POST['payment_method'];  // Сохраняем способ оплаты в сессии
}

if (isset($_POST['address'])) {
    $address = $_POST['address'];
    $payment_method = $_SESSION['payment_method'];  // Извлекаем способ оплаты из сессии
    $user_id = $_SESSION['user_id']; // Полагаем, что пользователь авторизован

    // Создаем заказ
    $sql_order = "INSERT INTO orders (user_id, address, payment_method) VALUES (?, ?, ?)";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("iss", $user_id, $address, $payment_method);
    $stmt_order->execute();

    $order_id = $stmt_order->insert_id; // Получаем ID нового заказа

    // Переносим товары в заказ
    foreach ($_SESSION['cart'] as $product_id => $product) {
        $quantity = $product['quantity'];
        $sql_order_item = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt_order_item = $conn->prepare($sql_order_item);
        $stmt_order_item->bind_param("iii", $order_id, $product_id, $quantity);
        $stmt_order_item->execute();
    }

    // Очищаем корзину
    unset($_SESSION['cart']);

    // Перенаправляем на страницу подтверждения
    header("Location: order_confirmation.php?order_id=" . $order_id);
    exit();
} else {
    echo "Ошибка: Способ оплаты не выбран.";
    exit();
}
?>
