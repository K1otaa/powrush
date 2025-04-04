<?php
session_start();
include 'config.php';

// Данные для входа (логин и пароль)
$email = $_POST['email'];
$password = $_POST['password']; // введённый пользователем пароль

// Получаем хеш пароля для данного email
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    // Проверяем, совпадает ли введённый пароль с хешем
    if (password_verify($password, $user['password_hash'])) {
        // Пароль верный, создаём сессию
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: admin_dashboard.php"); // Перенаправление на админ-панель
        exit();
    } else {
        echo "Неверный пароль!";
    }
} else {
    echo "Пользователь не найден!";
}
?>
