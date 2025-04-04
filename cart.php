<?php
session_start(); // Этот вызов должен быть первым в файле
include 'config.php';

// Проверка, если пользователь не авторизован, то перенаправляем на страницу логина
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$sql = "SELECT p.name, p.price, c.quantity, p.id 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2>Корзина</h2>

<table>
    <tr>
        <th>Товар</th>
        <th>Количество</th>
        <th>Цена</th>
        <th>Итого</th>
        <th>Удалить</th>
    </tr>
    <?php
    $total = 0;
    
    // Если в корзине есть товары, выводим их
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $subtotal = $row["price"] * $row["quantity"];
            $total += $subtotal;
            echo "<tr id='product_{$row['id']}'>";
            echo "<td>{$row['name']}</td>";
            echo "<td>";
            echo "<button type='button' onclick='updateQuantity({$row['id']}, {$row['price']}, -1)'>-</button>";
            echo "<input type='number' id='quantity_{$row['id']}' value='{$row['quantity']}' min='1' readonly>";
            echo "<button type='button' onclick='updateQuantity({$row['id']}, {$row['price']}, 1)'>+</button>";
            echo "</td>";
            echo "<td id='price_{$row['id']}'>{$row['price']} ₽</td>";
            echo "<td id='subtotal_{$row['id']}'>$subtotal ₽</td>";
            echo "<td><a href='remove_from_cart.php?product_id={$row['id']}'>Удалить</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Ваша корзина пуста.</td></tr>";
    }
    ?>
</table>

<h3>Общая сумма: <span id="total_amount"><?php echo $total; ?> ₽</span></h3>

<?php
// Проверяем, если в корзине есть товары, показываем кнопку для перехода на страницу оформления заказа
if ($total > 0) {
    echo "<a href='order.php'><button>Перейти к оформлению</button></a>";
}
?>

<script>
function updateQuantity(productId, price, change) {
    var quantityInput = document.getElementById('quantity_' + productId);
    var currentQuantity = parseInt(quantityInput.value);
    var newQuantity = currentQuantity + change;

    if (newQuantity < 1) return;

    quantityInput.value = newQuantity;

    var subtotal = price * newQuantity;
    document.getElementById('subtotal_' + productId).innerText = subtotal + " ₽";
    updateTotal();

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_cart.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText);
        }
    };
    xhr.send('product_id=' + productId + '&quantity=' + newQuantity);
}

function updateTotal() {
    var total = 0;
    var rows = document.querySelectorAll("tr[id^='product_']");
    rows.forEach(function(row) {
        var subtotal = parseFloat(row.querySelector("td[id^='subtotal_']").innerText.replace(' ₽', ''));
        total += subtotal;
    });
    document.getElementById("total_amount").innerText = total + " ₽";
}
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
