<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Удаление заказа
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM orders WHERE id = '$delete_id'");
    header("Location: manage_orders.php");
    exit();
}



$result = $conn->query("SELECT * FROM orders");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление заказами</title>
</head>
<body>
<h2>Список заказов</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Пользователь</th>
        <th>Товары</th>
        <th>Сумма</th>
        <th>Статус</th>
        <th>Действия</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['user_id'] ?></td>
            <td><?= $row['items'] ?></td>
            <td><?= $row['total_price'] ?> ₽</td>
            <td><?= $row['status'] ?></td>
            <td><a href="manage_orders.php?delete_id=<?= $row['id'] ?>">Удалить</a></td>
        </tr>
    <?php } ?>
</table>
<a href="admin_dashboard.php">Назад</a>
</body>
</html>