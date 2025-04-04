<?php
session_start();
session_destroy(); // Уничтожаем сессию
header("Location: admin_login.php"); // Перенаправляем на страницу входа
exit();
?>

