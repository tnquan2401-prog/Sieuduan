<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_to_remove = $_GET['id'] ?? null;

if ($id_to_remove && isset($_SESSION['cart'])) {
    // Remove element from array explicitly by searching for value
    $index = array_search($id_to_remove, $_SESSION['cart']);
    if ($index !== false) {
        unset($_SESSION['cart'][$index]);
        // Reset index sequence
        $_SESSION['cart'] = array_values($_SESSION['cart']);

        // Gỡ khóa trạng thái hiển thị lại lên web
        require_once __DIR__ . '/Connect/connect.php';
        $sql = "UPDATE sanpham SET TrangThai = 0 WHERE MaSP = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id_to_remove);
            $stmt->execute();
        }
    }
}

// Chuyển hướng lại giỏ hàng
echo "<script>window.location.href = 'index.php?type=DanhSachNhan';</script>";
exit();
?>
