<?php
session_start();
include 'config.php';

// Проверяем, авторизован ли администратор
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Подсчет данных для панели
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$total_orders = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];
$total_products = $conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <h1>Добро пожаловать, <?php echo $_SESSION['admin_name']; ?>!</h1>
    <nav>
        <ul>
            <li><a href="manage_users.php">Пользователи (<?php echo $total_users; ?>)</a></li>
            <li><a href="manage_orders.php">Заказы (<?php echo $total_orders; ?>)</a></li>
            <li><a href="manage_products.php">Товары (<?php echo $total_products; ?>)</a></li>
            <li><a href="logout.php">Выход</a></li>
        </ul>
    </nav>
</body>
</html>
