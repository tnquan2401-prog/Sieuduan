<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/Connect/connect.php';

// Kiểm tra quyền Login mới được Nhận
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Vui lòng đăng nhập để có thể nhận sản phẩm!'); window.location.href = 'index.php?type=Login';</script>";
    exit();
}

$id_sp = $_GET['id'] ?? null;
if (!$id_sp) {
    echo "<script>alert('Sản phẩm không hợp lệ!'); window.location.href = 'index.php?type=QuanAo';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Kiểm tra người dùng đã điền thông tin người nhận vào bảng thongtinnguoinhan hay chưa
$sql_check = "SELECT * FROM thongtinnguoinhan WHERE users_id = ?";
$stmt = $conn->prepare($sql_check);

if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Chưa có thông tin, bắt buộc chuyển đến form để điền
        // Lưu giữ ID SP đang muốn Nhận vào Session để sau khi điền nó tự add luôn.
        $_SESSION['pending_add_sp'] = $id_sp;
        echo "<script>window.location.href = 'index.php?type=FormUserInfo';</script>";
        exit();
    } else {
        // Đã có thông tin rồi -> Add vào Giỏ 
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Tối đa nhận 5 sản phẩm
        if (count($_SESSION['cart']) >= 5) {
            echo "<script>alert('Xin lỗi! Mỗi tài khoản chỉ được nhận tối đa 5 món đồ trong 1 lần Yêu cầu.'); window.location.href = 'index.php?type=DanhSachNhan';</script>";
            exit();
        }

        // Kiểm tra xem sản phẩm đã bị ai khác nhanh tay lấy chưa
        $check_avail = "SELECT TrangThai FROM sanpham WHERE MaSP = ?";
        $av_stmt = $conn->prepare($check_avail);
        $av_stmt->bind_param("i", $id_sp);
        $av_stmt->execute();
        $av_res = $av_stmt->get_result();
        if ($av_res->num_rows > 0) {
            $av_row = $av_res->fetch_assoc();
            if ($av_row['TrangThai'] == 1) {
                echo "<script>alert('Giỏ hàng lỗi! Sản phẩm này vừa mới được người khác nhận trước!'); window.location.href = 'index.php?type=QuanAo';</script>";
                exit();
            }
        }

        // Push sản phẩm vào Giỏ và Khóa trên Web để người khác ko nhận
        $_SESSION['cart'][] = $id_sp;
        
        $upSql = "UPDATE sanpham SET TrangThai = 1 WHERE MaSP = ?";
        $upStmt = $conn->prepare($upSql);
        $upStmt->bind_param("i", $id_sp);
        $upStmt->execute();

        echo "<script>window.location.href = 'index.php?type=DanhSachNhan';</script>";
        exit();
    }
} else {
    echo "<script>alert('Lỗi truy vấn Database!'); window.location.href = 'index.php?type=QuanAo';</script>";
    exit();
}
?>
