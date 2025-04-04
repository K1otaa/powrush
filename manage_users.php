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
<form method="POST">
    <table>
        <tr>
            <th>Выбрать</th>
            <th>ID</th>
            <th>Имя</th>
            <th>Email</th>
        </tr>
        <?php
        $sql = "SELECT id, name, email FROM users";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><input type='checkbox' name='items[]' value='" . $row['id'] . "'></td>"; // Чекбокс для выбора пользователей
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <button type="submit">Удалить выбранных</button>
</form>

<a href="admin_dashboard.php">Назад</a>
</body>
</html>