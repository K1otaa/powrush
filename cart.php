<?php
session_start();
include 'config.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Ваша корзина пуста.</p>";
    exit();
}

echo "<h2>Ваша корзина</h2>";
$total = 0;

foreach ($_SESSION['cart'] as $product_id => $product) {
    $subtotal = $product['quantity'] * $product['price'];
    $total += $subtotal;
    echo "<div class='cart-item'>";
    echo "<p>" . $product['name'] . "</p>";
    echo "<p>Цена: " . $product['price'] . " ₽</p>";
    
    // Добавляем кнопки + и -
    echo "<button class='quantity-decrease' data-id='$product_id'>-</button>";
    echo "<span class='quantity'>" . $product['quantity'] . "</span>";
    echo "<button class='quantity-increase' data-id='$product_id'>+</button>";
    
    echo "<p>Итого: " . $subtotal . " ₽</p>";
    echo "<a href='remove_from_cart.php?product_id=" . $product_id . "'>Удалить</a>";
    echo "</div>";
}

echo "<h3>Общая сумма: " . $total . " ₽</h3>";
if (!empty($_SESSION['cart'])) {
    echo "<a href='order.php' class='checkout-button'>Перейти к оформлению</a>";
}
?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".quantity-increase").forEach(button => {
        button.addEventListener("click", function() {
            let productId = this.getAttribute("data-id");
            updateProductQuantity(productId, 'increase');
        });
    });

    document.querySelectorAll(".quantity-decrease").forEach(button => {
        button.addEventListener("click", function() {
            let productId = this.getAttribute("data-id");
            updateProductQuantity(productId, 'decrease');
        });
    });
});

function updateProductQuantity(productId, action) {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "update_cart.php?product_id=" + productId + "&action=" + action, true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            try {
                let response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    updateCartCount(response.cartCount);
                    location.reload(); // Обновляем страницу
                }
            } catch (e) {
                console.error("Ошибка разбора JSON:", xhr.responseText);
            }
        }
    };
    xhr.send();
}

// Функция обновления счетчика корзины
function updateCartCount(cartCount = 0) {
    let cartCountElement = document.getElementById("cart-count");
    if (cartCountElement) {
        cartCountElement.innerText = cartCount;
    }
}
</script>

