<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Получение данных товара
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if (!$product) {
        echo "Товар не найден.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];

    // Обновление данных товара
    $sql = "UPDATE products SET name = ?, price = ?, description = ?, image_url = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $price, $description, $image_url, $product_id);
    $stmt->execute();

    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать товар</title>
</head>
<body>

<h2>Редактировать товар</h2>

<form method="POST">
    <label for="name">Название товара:</label>
    <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required><br>

    <label for="price">Цена:</label>
    <input type="text" id="price" name="price" value="<?php echo $product['price']; ?>" required><br>

    <label for="description">Описание:</label>
    <textarea id="description" name="description" required><?php echo $product['description']; ?></textarea><br>

    <label for="image_url">URL изображения:</label>
    <input type="text" id="image_url" name="image_url" value="<?php echo $product['image_url']; ?>" required><br>

    <button type="submit">Сохранить изменения</button>
</form>

</body>
</html>

<?php
$conn->close();
?>
