<?php
session_start();
include 'config.php';

if (!isset($_GET['order_id'])) {
    echo "Ошибка: Не указан ID заказа.";
    exit();
}

$order_id = $_GET['order_id'];

// Получаем данные о заказе
$sql = "SELECT * FROM orders WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Получаем товары из заказа
$sql_items = "SELECT p.name, oi.quantity, p.price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$result_items = $stmt_items->get_result();

// Массив для преобразования значения способа оплаты
$payment_methods = [
    'credit_card' => 'Дебетовая карта',
    'paypal' => 'PayPal',
    'cash_on_delivery' => 'Наличными'
];

// Извлекаем способ оплаты из сессии
$payment_method = $_SESSION['payment_method'] ?? 'Не выбран';

// Преобразуем в читаемую форму
$display_payment_method = isset($payment_methods[$payment_method]) ? $payment_methods[$payment_method] : 'Не выбран';

// Выводим информацию о заказе
echo "<h2>Подтверждение заказа</h2>";
echo "<p>Номер заказа: " . $order['id'] . "</p>";
echo "<p>Адрес доставки: " . $order['address'] . "</p>";

// Выводим выбранный способ оплаты
echo "<p>Способ оплаты: " . $display_payment_method . "</p>";

// Выводим товары в заказе
echo "<h3>Ваши товары:</h3>";
echo "<table>";
echo "<tr><th>Товар</th><th>Количество</th><th>Цена</th><th>Итого</th></tr>";
$total = 0;
while ($row = $result_items->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
    echo "<tr>";
    echo "<td>" . $row['name'] . "</td>";
    echo "<td>" . $row['quantity'] . "</td>";
    echo "<td>" . $row['price'] . " ₽</td>";
    echo "<td>" . $subtotal . " ₽</td>";
    echo "</tr>";
}
echo "</table>";
echo "<h3>Общая сумма: " . $total . " ₽</h3>";

$stmt->close();
$stmt_items->close();
$conn->close();
?>
