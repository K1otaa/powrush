<?php
include 'config.php';

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . " - Имя: " . $row["name"] . "<br>";
    }
} else {
    echo "Нет данных в таблице users";
}

$conn->close();
?>
