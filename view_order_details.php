<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    echo "Заказ не найден.";
    exit();
}

$order_id = intval($_GET['order_id']);

// Получаем детали заказа
$sql = "SELECT oi.product_id, p.name, oi.quantity 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Детали заказа</title>
</head>
<body>
    <h2>Детали заказа #<?php echo $order_id; ?></h2>
    <a href="view_orders.php">Назад</a>
    <table border="1">
        <tr>
            <th>Товар</th>
            <th>Количество</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo $row['quantity']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
