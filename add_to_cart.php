<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    echo "Пожалуйста, войдите в систему.";
    exit();
}

if (!isset($_GET['product_id']) || empty($_GET['product_id'])) {
    echo "Не указан ID товара.";
    exit();
}

$user_id = $_SESSION["user_id"];
$product_id = $_GET["product_id"];
$quantity = 1; // Начальное количество товара

// Проверка на наличие корзины в сессии
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Создаем корзину, если её нет
}

// Проверяем, есть ли уже этот товар в корзине (сессии)
if (isset($_SESSION['cart'][$product_id])) {
    // Если товар уже есть, увеличиваем количество на 1
    $_SESSION['cart'][$product_id]['quantity']++;
} else {
    // Если товара нет, добавляем его с количеством 1
    $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
}

// Получаем название и цену товара из базы данных
$sql = "SELECT name, price FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($name, $price);
$stmt->fetch();

// Сохраняем информацию о товаре в сессию
if (!isset($_SESSION['cart'][$product_id]['name'])) {
    $_SESSION['cart'][$product_id]['name'] = $name;
    $_SESSION['cart'][$product_id]['price'] = $price;
}

$stmt->close();

// Обновление корзины в базе данных (если нужно)
$sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Если товар уже есть в корзине базы данных, обновляем количество
    $update_sql = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $user_id, $product_id);
    $update_stmt->execute();
    $update_stmt->close();
} else {
    // Если товара нет в корзине базы данных, добавляем его
    $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $insert_stmt->execute();
    $insert_stmt->close();
}

// Логирование в ответ
echo json_encode([
    'status' => 'success',
    'message' => 'Товар добавлен в корзину.',
    'cart' => $_SESSION['cart'] // Выводим корзину в ответ, чтобы клиент мог увидеть, что добавлено
]);

$conn->close();
exit();
?>
