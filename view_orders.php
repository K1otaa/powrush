<?php
session_start();
include 'config.php';

// Проверяем авторизацию
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Получаем список заказов
$sql = "SELECT o.id, o.user_id, o.address, o.payment_method, o.created_at, u.name 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список заказов</title>
</head>
<body>
    <h2>Список заказов</h2>
    <a href="admin_dashboard.php">Назад в админку</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Пользователь</th>
            <th>Адрес</th>
            <th>Оплата</th>
            <th>Дата</th>
            <th>Детали</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td><a href="order_details.php?order_id=<?php echo $row['id']; ?>">Открыть</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
