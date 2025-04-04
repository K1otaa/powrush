<?php
echo password_hash('admin123', PASSWORD_DEFAULT);
?>


<?php
session_start();
include 'config.php';

// Если админ уже авторизован, перенаправляем в панель
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Проверяем, есть ли такой админ в базе
    $sql = "SELECT id, password_hash, name FROM users WHERE email = ? AND role = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($admin_id, $hashed_password, $admin_name);
        $stmt->fetch();

        // Проверяем пароль
        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['admin_name'] = $admin_name;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Неверный пароль.";
        }
    } else {
        $error = "Администратор не найден.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход в админ-панель</title>
</head>
<body>
    <h2>Вход в админ-панель</h2>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Пароль:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Войти</button>
    </form>
    <?php if ($error) echo "<p style='color: red;'>$error</p>"; ?>
</body>
</html>
