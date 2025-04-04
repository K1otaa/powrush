<?php
session_start();
include 'config.php';

if (!isset($_GET['order_id'])) {
    echo "Ошибка: Нет данных о заказе.";
    exit();
}

$order_id = $_GET['order_id'];

// Получаем информацию о заказе
$sql = "SELECT * FROM orders WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Получаем информацию о товарах в заказе
$sql_items = "SELECT oi.quantity, p.name, p.price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items = $stmt_items->get_result();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Подтверждение заказа</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2>Ваш заказ №<?php echo $order['id']; ?> оформлен!</h2>

<h3>Детали заказа:</h3>
<p>Адрес доставки: <?php echo $order['address']; ?></p>
<p>Способ оплаты: <?php echo $order['payment_method']; ?></p>

<h3>Товары в заказе:</h3>
<table>
    <tr>
        <th>Товар</th>
        <th>Количество</th>
        <th>Цена</th>
        <th>Итого</th>
    </tr>
    <?php
    $total = 0;
    while ($item = $items->fetch_assoc()) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        echo "<tr>";
        echo "<td>" . $item['name'] . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td>" . $item['price'] . " ₽</td>";
        echo "<td>" . $subtotal . " ₽</td>";
        echo "</tr>";
    }
    ?>
</table>

<h3>Общая сумма: <?php echo $total; ?> ₽</h3>

</body>
</html>

<?php
$stmt->close();
$stmt_items->close();
$conn->close();
?>
