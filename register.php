<?php
include 'config.php';

$message = ""; // Сообщение для пользователя

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $password_hash = password_hash($password, PASSWORD_DEFAULT); // Хешируем пароль

    // Проверяем, нет ли уже такого email
    $check_sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if (!$stmt) {
        die('Ошибка подготовки запроса: ' . $conn->error);
    }
    

    if ($stmt->num_rows > 0) {
        $message = "Email уже зарегистрирован!";
    } else {
        // Добавляем пользователя в базу
        $sql = "INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $password_hash);
        
        if ($stmt->execute()) {
            $message = "Регистрация успешна! <a href='login.php'>Войти</a>";
        } else {
            $message = "Ошибка при регистрации.";
        }
    }

    if (!$stmt) {
    die('Ошибка подготовки запроса: ' . $conn->error);
}


    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2>Регистрация</h2>

<form action="register.php" method="post">
    <input type="text" name="name" placeholder="Имя" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Пароль" required><br>
    <button type="submit">Зарегистрироваться</button>
</form>

<p><?php echo $message; ?></p>

</body>
</html>
