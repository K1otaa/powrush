<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$sql = "SELECT p.name, p.price, c.quantity, p.id FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Проверка на пустую корзину
if ($result->num_rows == 0) {
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
        while ($row = $result->fetch_assoc()) {
            $subtotal = $row["price"] * $row["quantity"];
            $total += $subtotal;
            echo "<tr>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["quantity"] . "</td>";
            echo "<td>" . $row["price"] . " ₽</td>";
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
        <option value="credit_card">Кредитная карта</option>
        <option value="paypal">PayPal</option>
        <option value="cash_on_delivery">Наложенный платеж</option>
    </select>
    
    <h3>Общая сумма: <?php echo $total; ?> ₽</h3>

    <button type="submit">Оформить заказ</button>
</form>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
