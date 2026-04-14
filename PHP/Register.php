<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/Connect/connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

$errorMsg = '';
$successMsg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Logic kiểm soát tính hợp lệ (Validation)
    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        $errorMsg = "Tên đăng nhập không được chứa ký tự đặc biệt hoặc dấu cách.";
    } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
        $errorMsg = "Mật khẩu không được chứa ký tự đặc biệt hoặc dấu cách.";
    } elseif (strlen($password) < 6) {
        $errorMsg = "Mật khẩu phải có độ dài từ 6 ký tự trở lên.";
    } elseif ($password !== $confirm_password) {
        $errorMsg = "Mật khẩu xác nhận không khớp.";
    } else {
        // Kiểm tra xem User or Email exist không
        $checkSql = "SELECT * FROM users WHERE UserName = ? OR Email = ?";
        $stmt = $conn->prepare($checkSql);
        if ($stmt) {
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $errorMsg = "Tên đăng nhập hoặc Email đã tồn tại. Vui lòng chọn cái khác.";
            } else {
                // Random OTP (6 số)
                $otp = rand(100000, 999999);
                
                // Lưu dữ liệu chờ xử lý vào session với 5 phút hạn sử dụng
                $_SESSION['pending_user'] = [
                    'username' => $username,
                    'email' => $email,
                    'password' => md5($password), 
                    'otp' => $otp,
                    'expires_at' => time() + (5 * 60) // Tồn tại 5 phút
                ];

                // Thiết lập PHPMailer
                $mail = new $PHPMailer(true);
                try {
                    //Server settings
                    $mail->isSMTP(); 
                    $mail->Host       = 'smtp.gmail.com'; 
                    $mail->SMTPAuth   = true; 
                    $mail->Username   = 'karaokeparadise3008@gmail.com'; 
                    $mail->Password   = 'razf qglf okvs grcv'; 
                    $mail->SMTPSecure = $PHPMailer::ENCRYPTION_SMTPS; 
                    $mail->Port       = 465;
                    $mail->CharSet    = 'UTF-8';

                    //Recipients
                    $mail->setFrom('karaokeparadise3008@gmail.com', 'Mầm Non Mai Động');
                    $mail->addAddress($email, $username); // Thêm email người nhận

                    //Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Mầm Non Mai Động - Xác nhận tài khoản';
                    $mail->Body    = 'Mã xác nhận của bạn là <b>' . $otp . '</b>, Mã OTP này có hiệu lực trong vòng 5 phút.';
                    
                    $mail->send();

                    // Chuyển hướng sang giao diện xác nhận
                    echo "<script>window.location.href = 'index.php?type=VerifyOTP';</script>";
                    exit();
                } catch (\Exception $e) {
                    $errorMsg = "Gửi email thất bại. Lỗi từ hệ thống Mail: " . $e->getMessage();
                }
            }
        } else {
            $errorMsg = "Lỗi truy xuất hệ thống: " . $conn->error;
        }
    }
}
?>
<link rel="stylesheet" href="CSS/index.css">

<main class="auth-page">
    <div class="auth-container">
        <h2 style="margin-bottom: 25px;"><i class="fa-solid fa-user-plus"></i> Đăng ký tài khoản</h2>
        
        <?php if ($errorMsg): ?>
            <div style="background-color: #fef2f2; color: #991b1b; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #fecaca; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-triangle-exclamation"></i> 
                <span><?php echo $errorMsg; ?></span>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" required placeholder="" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required placeholder="">
            </div>

            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Nhập lại mật khẩu để xác nhận">
            </div>
            
            <button type="submit" class="btn auth-btn"><i class="fa-solid fa-paper-plane"></i> Gửi OTP Xác nhận</button>
        </form>
        
        <p class="auth-switch">Đã có tài khoản? <a href="index.php?type=Login">Đăng nhập</a></p>
    </div>
</main>