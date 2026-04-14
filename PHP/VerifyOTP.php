<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/Connect/connect.php';

$errorMsg = '';
$successMsg = '';

// Quét xem có session đăng ký đang treo không, không có thì mời quay lại Register
if (!isset($_SESSION['pending_user'])) {
    echo "<script>window.location.href = 'index.php?type=Register';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = trim($_POST['otp'] ?? '');
    
    // Kiểm tra hết hạn 5 phút
    if (time() > $_SESSION['pending_user']['expires_at']) {
        $errorMsg = "Mã OTP đã hết hạn";
    } elseif ($user_otp == $_SESSION['pending_user']['otp']) {
        // Mã đúng và còn hạn -> Ghi nhận vào DB
        $username = $_SESSION['pending_user']['username'];
        $email = $_SESSION['pending_user']['email'];
        $password = $_SESSION['pending_user']['password']; // Đã được mã hóa sẵn bên form Register bằng md5
        $permission = 'customer'; 
        
        $sql = "INSERT INTO users (UserName, Passwd, Email, Permission) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("ssss", $username, $password, $email, $permission);
            if ($stmt->execute()) {
                // Xóa session pending 
                unset($_SESSION['pending_user']);
                
                // Mở cửa và ném thông báo JS thành công
                echo "<script>alert('Đã đăng ký tài khoản thành công.'); window.location.href = 'index.php?type=Login';</script>";
                exit();
            } else {
                $errorMsg = "Lỗi tích hợp Database: " . $stmt->error;
            }
        } else {
            $errorMsg = "Lỗi xử lý Database: " . $conn->error;
        }
    } else {
        $errorMsg = "Mã xác nhận (OTP) không chính xác.";
    }
}
?>
<link rel="stylesheet" href="CSS/index.css">

<main class="auth-page">
    <div class="auth-container" style="max-width: 500px;">
        <h2 style="margin-bottom: 25px; color: #333;"><i class="fa-solid fa-shield-halved" style="color: #28a745;"></i> Xác nhận Email</h2>
        
        <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; border: 1px dashed #cbd5e1; margin-bottom: 25px;">
            <p style="text-align: center; color: #475569; margin: 0; line-height: 1.6;">
                Một mã xác nhận gồm 6 chữ số đã được gửi đến hộp thư<br>
                <strong style="color: #0f172a; font-size: 1.1em;"><?php echo htmlspecialchars($_SESSION['pending_user']['email']); ?></strong>.
                <br>Mã chỉ có hiệu lực trong vòng <strong>5 phút</strong>.
            </p>
        </div>

        <?php if ($errorMsg): ?>
            <div style="background-color: #fef2f2; color: #991b1b; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #fecaca; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-triangle-exclamation"></i> 
                <span><?php echo $errorMsg; ?></span>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group" style="text-align: center;">
                <label for="otp" style="text-align: left; display: block;">Nhập mã (6 số)</label>
                <input type="text" id="otp" name="otp" required maxlength="6" 
                       style="text-align: center; font-size: 24px; letter-spacing: 12px; font-weight: bold; padding: 15px; border-radius: 8px; border: 2px solid #cbd5e1;" 
                       placeholder="------" autofocus>
            </div>
            
            <button type="submit" class="btn auth-btn"><i class="fa-solid fa-circle-check"></i> Đăng ký</button>
        </form>
        
        <p class="auth-switch" style="font-size: 0.9em; color: #64748b; margin-top: 25px;">Kiểm tra kỹ cả Hộp Thư rác/Spam nếu bạn không tìm thấy mail.</p>
    </div>
</main>
