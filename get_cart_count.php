<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$totalCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
echo json_encode(["cartCount" => $totalCount]);
exit;
?>
