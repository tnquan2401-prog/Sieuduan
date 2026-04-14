<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/Connect/connect.php';

// Bảo vệ luồng
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'index.php?type=Login';</script>";
    exit();
}

$errorMsg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = htmlspecialchars($_POST['fullname'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $address = htmlspecialchars($_POST['address'] ?? '');
    $user_id = $_SESSION['user_id'];

    if (empty($fullname) || empty($phone) || empty($address)) {
        $errorMsg = "Vui lòng điền đầy đủ các trường.";
    } else {
        // Lưu data vào Database
        $sql = "INSERT INTO thongtinnguoinhan (users_id, Fullname, Phone, Address) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("isss", $user_id, $fullname, $phone, $address);
            if ($stmt->execute()) {
                // Tự động đẩy sản phẩm lúc nãy vào Giỏ
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }
                
                if (isset($_SESSION['pending_add_sp']) && count($_SESSION['cart']) < 5) {
                    $id_sp = $_SESSION['pending_add_sp'];
                    if (!in_array($id_sp, $_SESSION['cart'])) {
                        
                        // Kiểm tra trạng thái còn chưa ai lấy
                        $check_avail = "SELECT TrangThai FROM sanpham WHERE MaSP = ?";
                        $av_stmt = $conn->prepare($check_avail);
                        $av_stmt->bind_param("i", $id_sp);
                        $av_stmt->execute();
                        $av_res = $av_stmt->get_result();
                        $is_taken = false;
                        if ($av_res->num_rows > 0) {
                            $av_row = $av_res->fetch_assoc();
                            if ($av_row['TrangThai'] == 1) $is_taken = true;
                        }

                        if (!$is_taken) {
                            $_SESSION['cart'][] = $id_sp;
                            
                            $upSql = "UPDATE sanpham SET TrangThai = 1 WHERE MaSP = ?";
                            $upStmt = $conn->prepare($upSql);
                            $upStmt->bind_param("i", $id_sp);
                            $upStmt->execute();
                        }
                    }
                    unset($_SESSION['pending_add_sp']); // Done
                }
                
                echo "<script>alert('Lưu thông tin thành công! Sản phẩm đã được thêm vào danh sách nhận.'); window.location.href = 'index.php?type=DanhSachNhan';</script>";
                exit();
            } else {
                $errorMsg = "Lỗi Database: " . $stmt->error;
            }
        } else {
            $errorMsg = "Lỗi truy vấn SQL: " . $conn->error;
        }
    }
}
?>
<link rel="stylesheet" href="CSS/index.css">

<main class="auth-page">
    <div class="auth-container">
        <h2 style="margin-bottom: 25px;"><i class="fa-solid fa-address-card" style="color: #28a745;"></i> Điền thông tin nhận đồ</h2>
        
        <p style="color: #666; margin-bottom: 20px; font-size: 0.95em;">Đây là lần đầu bạn nhận đồ. Để đảm bảo đồ đến tận tay, vui lòng cung cấp thông tin liên hệ tĩnh (chỉ cần điền 1 lần duy nhất).</p>

        <?php if ($errorMsg): ?>
            <div style="background-color: #fef2f2; color: #991b1b; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #fecaca;">
                <i class="fa-solid fa-triangle-exclamation"></i> <?php echo $errorMsg; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="fullname">Họ và tên người nhận</label>
                <input type="text" id="fullname" name="fullname" required placeholder="Nguyễn Văn A">
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="text" id="phone" name="phone" required placeholder="09xxxxxx..">
            </div>
            
            <div class="form-group">
                <label for="address">Địa chỉ nhận tận nơi</label>
                <input type="text" id="address" name="address" required placeholder="Số nhà, Tên đường, Quận, Tên thành phố">
            </div>
            
            <button type="submit" class="btn auth-btn"><i class="fa-solid fa-floppy-disk"></i> Lưu thông tin</button>
        </form>
    </div>
</main>
