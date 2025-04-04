<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['address'], $_POST['payment_method'])) {
    $user_id = $_SESSION["user_id"];
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];

    // Получаем список товаров в корзине
    $sql = "SELECT product_id, quantity FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Проверяем, что корзина не пуста
    if ($result->num_rows > 0) {
        // Создаем заказ
        $sql_order = "INSERT INTO orders (user_id, address, payment_method) VALUES (?, ?, ?)";
        $stmt_order = $conn->prepare($sql_order);
        $stmt_order->bind_param("iss", $user_id, $address, $payment_method);
        $stmt_order->execute();

        $order_id = $stmt_order->insert_id; // Получаем ID нового заказа

        // Переносим товары в заказ
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];

            $sql_order_item = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt_order_item = $conn->prepare($sql_order_item);
            $stmt_order_item->bind_param("iii", $order_id, $product_id, $quantity);
            $stmt_order_item->execute();
        }

        // Очищаем корзину
        $sql_clear_cart = "DELETE FROM cart WHERE user_id = ?";
        $stmt_clear_cart = $conn->prepare($sql_clear_cart);
        $stmt_clear_cart->bind_param("i", $user_id);
        $stmt_clear_cart->execute();

        // Перенаправляем на страницу подтверждения
        header("Location: order_confirmation.php?order_id=" . $order_id);
        exit();
    } else {
        echo "Ваша корзина пуста.";
        exit();
    }
} else {
    echo "Ошибка: Неверные данные.";
}
?>
