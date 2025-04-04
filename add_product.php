<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];

    // Вставка нового товара в таблицу
    $sql = "INSERT INTO products (name, price, description, image_url) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $price, $description, $image_url);
    $stmt->execute();

    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить товар</title>
</head>
<body>

<h2>Добавить новый товар</h2>

<form method="POST">
    <label for="name">Название товара:</label>
    <input type="text" id="name" name="name" required><br>

    <label for="price">Цена:</label>
    <input type="text" id="price" name="price" required><br>

    <label for="description">Описание:</label>
    <textarea id="description" name="description" required></textarea><br>

    <label for="image_url">URL изображения:</label>
    <input type="text" id="image_url" name="image_url" required><br>

    <button type="submit">Добавить товар</button>
</form>

</body>
</html>

<?php
$conn->close();
?>
