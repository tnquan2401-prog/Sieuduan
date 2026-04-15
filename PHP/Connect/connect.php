<?php
// $host = "sql308.infinityfree.com";
// $user = "if0_41658507";
// $pass = "aEDZKEUWziEMN";
// $db   = "if0_41658507_duannuoiem_db";

$host = "localhost";
$user = "root";
$pass = "";
$db   = "duannuoiem_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>