<?php
$host = 'localhost'; // Хост (в XAMPP это localhost)
$dbname = 'powrush_db'; // Название базы данных
$username = 'root'; // Стандартный пользователь в XAMPP
$password = ''; // Пароль в XAMPP по умолчанию пустой

// Подключение через MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$conn->set_charset("utf8"); // Устанавливаем кодировку UTF-8
?>
