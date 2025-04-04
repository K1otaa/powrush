<?php
session_start();
include 'config.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PowRush - Главная</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<h1>Каталог товаров</h1>

<div class="products-container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<img src='" . $row["image_url"] . "' alt='" . $row["name"] . "'>";
            echo "<h3>" . $row["name"] . "</h3>";
            echo "<p>" . $row["description"] . "</p>";
            echo "<p class='price'>" . $row["price"] . " ₽</p>";
            echo "<button class='add-to-cart' data-id='" . $row['id'] . "'>Купить</button>";
            echo "</div>";
        }
    } else {
        echo "Товары не найдены.";
    }
    ?>
</div>

<script>
$(document).ready(function() {
    $('.add-to-cart').on('click', function() {
        var productId = $(this).data('id'); // Получаем ID товара

        $.ajax({
            url: 'add_to_cart.php',
            type: 'GET',
            data: { product_id: productId },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    alert('Товар добавлен в корзину!');
                } else {
                    alert('Ошибка при добавлении товара в корзину.');
                }
            },
            error: function() {
                alert('Ошибка при добавлении товара в корзину.');
            }
        });
    });
});
</script>

</body>
</html>

<?php
