<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Добавление товара
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $conn->query("INSERT INTO products (name, price) VALUES ('$name', '$price')");
    header("Location: manage_products.php");
    exit();
}

// Удаление товара
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM products WHERE id = '$delete_id'");
    header("Location: manage_products.php");
    exit();
}

$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление товарами</title>
</head>
<body>
<h2>Список товаров</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Название" required>
    <input type="number" name="price" placeholder="Цена" required>
    <button type="submit">Добавить</button>
</form>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Цена</th>
        <th>Действия</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['price'] ?> ₽</td>
            <td><a href="manage_products.php?delete_id=<?= $row['id'] ?>">Удалить</a></td>
        </tr>
    <?php } ?>
</table>
<a href="admin_dashboard.php">Назад</a>
</body>
</html>