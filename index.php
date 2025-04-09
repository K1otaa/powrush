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
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&family=Russo+One&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="cart-overlay" id="cart-overlay"></div>
<div class="cart-sidebar" id="cart-sidebar"></div>

<header>
    <div class="container">
        <div class="logo">
            <a href="index.php">PowRush</a>
        </div>
        <nav class="nav-menu">
            <ul class="nav-links">
                <li><a href="#">Главная</a></li>
                <li><a href="index.php">Каталог</a></li>
                <li><a href="about.php">О нас</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php" class="logout">Выйти</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="login">Войти</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <div class="cart-container">
            <a href="#" id="open-cart">
                <img src="img/basket.svg" alt="Корзина" class="cart-icon">
                <span class="cart-count" id="cart-count"><?= $cart_count ?></span>
            </a>
        </div>
        <div class="favorites-container">
            <a href="favorites.php">
                <img src="img/favor.svg" alt="Избранное" class="favorite-icon">
            </a>
        </div>
        <div class="user-container">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profile.php">
                    <img src="img/profile.svg" alt="Профиль" class="user-icon">
                </a>
            <?php else: ?>
                <a href="login.php">
                    <img src="img/user-icon.svg" alt="Войти" class="user-icon">
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

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
    // Добавление товара в корзину
    document.querySelectorAll(".add-to-cart").forEach(button => {
        button.addEventListener("click", function() {
            let productId = this.getAttribute("data-id");
            let productName = this.getAttribute("data-name");
            let productPrice = this.getAttribute("data-price");
            addToCart(productId, productName, productPrice);
        });
    });

    // Открытие корзины
    document.getElementById("open-cart").addEventListener("click", function(e) {
        e.preventDefault();
        fetch("cart.php")
            .then(res => res.text())
            .then(html => {
                document.getElementById("cart-sidebar").innerHTML = html;
                document.getElementById("cart-sidebar").classList.add("open");
                document.getElementById("cart-overlay").classList.add("active");
            });
    });

    // Закрытие корзины по клику вне
    document.getElementById("cart-overlay").addEventListener("click", function() {
        document.getElementById("cart-sidebar").classList.remove("open");
        this.classList.remove("active");
    });

    // Обработчик событий внутри документа
    document.addEventListener('click', function(e) {
        // Удаление товара из корзины
        if (e.target && e.target.closest('.remove-item')) {
            let productId = e.target.closest('.remove-item').getAttribute('data-id');
            removeFromCart(productId);
        }

        // Увеличение количества
        if (e.target && e.target.classList.contains('quantity-increase')) {
            let productId = e.target.getAttribute('data-id');
            updateQuantity(productId, 'increase');
        }

        // Уменьшение количества
        if (e.target && e.target.classList.contains('quantity-decrease')) {
            let productId = e.target.getAttribute('data-id');
            updateQuantity(productId, 'decrease');
        }
    });
});

// Функция добавления товара
function addToCart(productId, productName, productPrice) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "add_to_cart.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status == 200) {
            try {
                let response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    document.getElementById("cart-count").innerText = response.cartCount;
                }
            } catch (e) {
                console.error("Ошибка разбора JSON:", xhr.responseText);
            }
        }
    };
    xhr.send("product_id=" + productId + "&product_name=" + encodeURIComponent(productName) + "&product_price=" + productPrice);
}

// Функция удаления товара
function removeFromCart(productId) {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "remove_from_cart.php?product_id=" + productId, true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            try {
                let response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    document.getElementById("cart-count").innerText = response.cartCount;
                    document.getElementById("cart-sidebar").innerHTML = response.cartHtml;
                }
            } catch (e) {
                console.error("Ошибка разбора JSON:", xhr.responseText);
            }
        }
    };
    xhr.send();
}

// Функция изменения количества товара
function updateQuantity(productId, action) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "update_cart_quantity.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                let response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // Обновляем количество в корзине
                    document.getElementById("cart-count").innerText = response.cartCount;
                    
                    // Перезагружаем содержимое корзины
                    fetch("cart.php")
                        .then(res => res.text())
                        .then(html => {
                            document.getElementById("cart-sidebar").innerHTML = html;
                        });
                }
            } catch (e) {
                console.error("Ошибка разбора JSON:", xhr.responseText);
            }
        }
    };
    xhr.send("product_id=" + productId + "&action=" + action);
}
</script>






</body>
</html>

<?php $conn->close(); ?>
