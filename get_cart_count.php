<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    echo 0; // Если пользователь не авторизован, возвращаем 0 товаров
    exit();
}

$user_id = $_SESSION["user_id"];

// Получаем количество товаров в корзине
$sql = "SELECT SUM(quantity) AS total_quantity FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Возвращаем количество товаров в корзине
echo $row['total_quantity'] ?: 0;

$stmt->close();
$conn->close();
?>
