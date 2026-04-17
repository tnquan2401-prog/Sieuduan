<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/Connect/connect.php';

// Bảo vệ: Yêu cầu đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo "<div class='container' style='padding: 50px; text-align: center;'>";
    echo "<h3>Vui lòng đăng nhập để thực hiện đăng ký cho đồ.</h3>";
    echo "<a href='index.php?type=Login' class='btn' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px;'>Đến trang Đăng nhập</a>";
    echo "</div>";
    return;
}

$successMsg = '';
$errorMsg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoten = htmlspecialchars($_POST['hoten'] ?? '');
    $sdt = htmlspecialchars($_POST['sdt'] ?? '');
    $user_id = $_SESSION['user_id'];

    if (empty($hoten) || empty($sdt)) {
        $errorMsg = "Vui lòng điền đầy đủ thông tin.";
    } else {
        $sql = "INSERT INTO dangkychodo (ID_User, HoTen, SoDienThoai) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iss", $user_id, $hoten, $sdt);
            if ($stmt->execute()) {
                $successMsg = "Đăng ký cho đồ thành công! Cảm ơn tấm lòng của bạn.";
            } else {
                $errorMsg = "Lỗi khi lưu dữ liệu: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errorMsg = "Lỗi truy vấn SQL: " . $conn->error;
        }
    }
}
?>

<link rel="stylesheet" href="CSS/index.css">
<style>
    .info-box {
        background: #f0fdf4;
        border-left: 4px solid #28a745;
        padding: 20px;
        margin-bottom: 30px;
        border-radius: 0 8px 8px 0;
    }
    .info-box h4 {
        color: #166534;
        margin-top: 0;
        margin-bottom: 10px;
    }
    .info-box p {
        margin: 5px 0;
        color: #374151;
        line-height: 1.6;
    }
    .form-donate {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }
</style>

<main class="category-page" style="padding: 40px 0;">
    <div class="container" style="max-width: 800px;">
        <div class="page-header" style="text-align: center; margin-bottom: 40px;">
            <h2 style="color: #28a745;"><i class="fa-solid fa-hand-holding-heart"></i> Đăng Ký Trao Tặng Vật Phẩm</h2>
            <p style="color: #64748b;">Mọi sự đóng góp của bạn đều là niềm hy vọng cho các em nhỏ.</p>
        </div>

        <!-- Thông báo thành công/lỗi -->
        <?php if ($successMsg): ?>
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #bbf7d0;">
                <i class="fa-solid fa-circle-check"></i> <?php echo $successMsg; ?>
            </div>
        <?php endif; ?>

        <?php if ($errorMsg): ?>
            <div style="background: #fef2f2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #fecaca;">
                <i class="fa-solid fa-triangle-exclamation"></i> <?php echo $errorMsg; ?>
            </div>
        <?php endif; ?>

        <div class="info-box">
            <h4><i class="fa-solid fa-circle-info"></i> Thông tin tiếp nhận</h4>
            <p><strong><i class="fa-regular fa-clock"></i> Thời gian:</strong> Từ 16h đến 17h hàng ngày (trừ Thứ 7, Chủ nhật và các ngày nghỉ Lễ, Tết theo quy định)</p>
            <p><strong><i class="fa-solid fa-location-dot"></i> Địa điểm nhận:</strong> Trường Mầm non Mai Động. 108 Phố Mai Động, Phường Tương Mai, TP Hà Nội.</p>
        </div>

        <div class="form-donate">
            <form action="" method="POST">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Họ và tên người cho đồ</label>
                    <input type="text" name="hoten" required placeholder="Nguyễn Văn A" style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none;">
                </div>

                <div class="form-group" style="margin-bottom: 30px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Số điện thoại liên hệ</label>
                    <input type="text" name="sdt" required placeholder="09xxxxxx.." style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none;">
                </div>

                <button type="submit" class="btn" style="background: #28a745; color: white; width: 100%; padding: 14px; border: none; border-radius: 8px; font-size: 1rem; font-weight: bold; cursor: pointer; transition: 0.3s;">
                    <i class="fa-solid fa-paper-plane"></i> Gửi đăng ký
                </button>
            </form>
        </div>
    </div>
</main>
