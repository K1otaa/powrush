<?php
session_start();
include 'config.php';

// Проверка, есть ли товары в корзине
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Ваша корзина пуста.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Оформление заказа</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2>Оформление заказа</h2>

<form method="POST" action="process_order.php">
    <h3>Ваши товары:</h3>
    <table>
        <tr>
            <th>Товар</th>
            <th>Количество</th>
            <th>Цена</th>
            <th>Итого</th>
        </tr>
        <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $product) {
            $subtotal = $product['price'] * $product['quantity'];
            $total += $subtotal;
            echo "<tr>";
            echo "<td>" . $product['name'] . "</td>";
            echo "<td>" . $product['quantity'] . "</td>";
            echo "<td>" . $product['price'] . " ₽</td>";
            echo "<td>" . $subtotal . " ₽</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h3>Адрес доставки:</h3>
    <label for="address">Адрес:</label>
    <textarea name="address" id="address" required></textarea>
    
    <h3>Оплата:</h3>
    <label for="payment_method">Способ оплаты:</label>
    <select name="payment_method" id="payment_method" required>
        <option value="credit_card">Дебетовая карта</option>
        <option value="paypal">PayPal</option>
        <option value="cash_on_delivery">Наличными</option>
    </select>
    
    <h3>Общая сумма: <?php echo $total; ?> ₽</h3>

    <button type="submit">Оформить заказ</button>
</form>

</body>
</html>
