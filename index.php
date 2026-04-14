<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php 
// 1. Nhúng phần Header vào
include __DIR__ . '/PHP/Header.php'; 

// 2. Phần thân trang web (Nội dung thay đổi ở đây)
echo '<div id="main-content">';

    // Kiểm tra tham số trên URL
    if (isset($_GET['type'])) {
        $page = $_GET['type'];
        $file = __DIR__ . '/PHP/' . $page . ".php";
       

        // Kiểm tra xem file có tồn tại không để tránh lỗi
        if (file_exists($file)) {
            include $file;
        } else {
            echo "<h3>Trang không tồn tại!</h3>";
        }
    } 
    else {
        // Nếu không có tham số gì, hiển thị nội dung mặc định của trang chủ
        include __DIR__ . '/PHP/HomePage.php'; 
    }

echo '</div>';

// 3. Nhúng phần Footer vào
include __DIR__ . '/PHP/Footer.php'; 
?>