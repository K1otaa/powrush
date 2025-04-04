<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    echo "Ошибка: Не авторизован.";
    exit();
}

$user_id = $_SESSION["user_id"];
$product_id = $_POST["product_id"];
$quantity = $_POST["quantity"];

// Обновляем количество товара в корзине
$sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $quantity, $user_id, $product_id);
$stmt->execute();

$stmt->close();
$conn->close();

echo "Количество товара обновлено"; // Ответ для AJAX
?>
