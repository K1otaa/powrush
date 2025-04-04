<?php
session_start();
include 'config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Обновляем счетчик товаров в корзине
$cart_count = 0;
foreach ($_SESSION['cart'] as $product) {
    $cart_count += $product['quantity'];
}
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

<!-- Иконка корзины -->
<div class="cart-container">
    <a href="cart.php">
        <img src="img/basket.svg" alt="Корзина" class="cart-icon">
        <span class="cart-count" id="cart-count"><?= $cart_count ?></span>
    </a>
</div>

<main>
    <h1>Каталог товаров</h1>
    <div class="products-container">
        <?php
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product'>";
                echo "<img src='" . $row["image_url"] . "' alt='" . $row["name"] . "'>";
                echo "<h3>" . $row["name"] . "</h3>";
                echo "<p>" . $row["description"] . "</p>";
                echo "<p class='price'>" . $row["price"] . " ₽</p>";
                echo "<button class='add-to-cart' data-id='" . $row['id'] . "' data-name='" . $row['name'] . "' data-price='" . $row['price'] . "'>Купить</button>";
                echo "</div>";
            }
        } else {
            echo "Товары не найдены.";
        }
        ?>
    </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".add-to-cart").forEach(button => {
        button.addEventListener("click", function() {
            let productId = this.getAttribute("data-id");
            let productName = this.getAttribute("data-name");
            let productPrice = this.getAttribute("data-price");
            
            // Добавляем товар в корзину через AJAX
            addToCart(productId, productName, productPrice);
        });
    });
});

// Функция для добавления товара в корзину
function addToCart(productId, productName, productPrice) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "add_to_cart.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status == 200) {
            try {
                let response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // Обновляем счетчик корзины
                    document.getElementById("cart-count").innerText = response.cartCount;
                }
            } catch (e) {
                console.error("Ошибка разбора JSON:", xhr.responseText);
            }
        }
    };
    xhr.send("product_id=" + productId + "&product_name=" + encodeURIComponent(productName) + "&product_price=" + productPrice);
}
</script>

</body>
</html>

<?php
$conn->close();
?>
