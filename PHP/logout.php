<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Xóa tất cả các session
session_unset();
session_destroy();

// Redirect về lại trang chủ bằng Javascript để tránh lỗi Headers already sent
echo "<script>window.location.href = 'index.php';</script>";
exit();
?>
