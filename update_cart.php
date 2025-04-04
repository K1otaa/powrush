<?php
session_start();
header('Content-Type: application/json');

if (!isset($_GET['product_id']) || !isset($_GET['action'])) {
    echo json_encode(["status" => "error", "message" => "Не переданы параметры"]);
    exit;
}

$product_id = $_GET['product_id'];
$action = $_GET['action'];

if (!isset($_SESSION['cart']) || !isset($_SESSION['cart'][$product_id])) {
    echo json_encode(["status" => "error", "message" => "Товар не найден в корзине"]);
    exit;
}

// Увеличение/уменьшение количества товара
if ($action === 'increase') {
    $_SESSION['cart'][$product_id]['quantity']++;
} elseif ($action === 'decrease') {
    if ($_SESSION['cart'][$product_id]['quantity'] > 1) {
        $_SESSION['cart'][$product_id]['quantity']--;
    } else {
        unset($_SESSION['cart'][$product_id]); // Удаляем товар, если его количество стало 0
    }
}

// Подсчет общего количества товаров
$totalCount = array_sum(array_column($_SESSION['cart'], 'quantity'));

echo json_encode(["status" => "success", "cartCount" => $totalCount]);
exit;
?>
