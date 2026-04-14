<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/Connect/connect.php';

// Chỉ admin mới có quyền truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['permission'] !== 'admin') {
    echo "<script>alert('Truy cập bị từ chối!'); window.location.href = 'index.php';</script>";
    exit();
}

$id = $_GET['id'] ?? null;     // sanphamnhan ID
$sp = $_GET['sp'] ?? null;     // Product MaSP

if ($id && $sp) {
    // 1. Xóa bỏ đơn yêu cầu
    $sql1 = "DELETE FROM sanphamnhan WHERE ID = ?";
    $stmt1 = $conn->prepare($sql1);
    if ($stmt1) {
        $stmt1->bind_param("i", $id);
        $stmt1->execute();
    }
    
    // 2. Khôi phục trạng thái Hiển thị Web cho Sản phẩm (Unlock)
    $sql2 = "UPDATE sanpham SET TrangThai = 0 WHERE MaSP = ?";
    $stmt2 = $conn->prepare($sql2);
    if ($stmt2) {
        $stmt2->bind_param("i", $sp);
        $stmt2->execute();
    }
}

// Redirect and alert success
echo "<script>alert('Đã hủy đơn hàng và khôi phục sản phẩm để hiển thị lại trên trang web!'); window.location.href = 'index.php?type=AdminDuyetDon&tab=pending';</script>";
exit();
?>
