<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/Connect/connect.php';

$errorMsg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_user = $_POST['email'] ?? '';
    // Input is named 'email' but takes both email & username
    $password = $_POST['password'] ?? '';

    // Truy vấn dữ liệu người dùng 
    // Trong ảnh các cột là: ID, UserName, Passwd, Email, Permission
    $sql = "SELECT * FROM users WHERE Email = ? OR UserName = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ss", $email_or_user, $email_or_user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Kiểm tra mật khẩu (Mật khẩu đã được mã hóa MD5 trong CSDL)
            if (md5($password) === $user['Passwd']) {
                // Lưu session 
                $_SESSION['user_id'] = $user['ID'];
                $_SESSION['username'] = $user['UserName'];
                $_SESSION['permission'] = $user['Permission'];

                // Phân quyền điều hướng
                if ($user['Permission'] === 'admin') {
                    // Chuyển hướng đến trang quản trị (Admin thêm sản phẩm)
                    echo "<script>window.location.href = 'index.php?type=admin_add_product';</script>";
                    exit();
                } else {
                    // Chuyển hướng đến trang người dùng
                    echo "<script>window.location.href = 'index.php';</script>";
                    exit();
                }
            } else {
                $errorMsg = "Mật khẩu không chính xác!";
            }
        } else {
            $errorMsg = "Tài khoản chưa tồn tại!";
        }
        $stmt->close();
    } else {
        $errorMsg = "Lỗi Database: " . $conn->error . ". (Bạn hãy kiểm tra xem tên bảng có đúng là 'users' không nhé!)";
    }
}
?>
<link rel="stylesheet" href="CSS/index.css">

<main class="auth-page">
    <div class="auth-container">
        <h2 style="margin-bottom: 25px;"><i class="fa-solid fa-right-to-bracket"></i> Đăng nhập</h2>
        
        <!-- Khung báo lỗi -->
        <?php if ($errorMsg): ?>
            <div style="background-color: #fef2f2; color: #991b1b; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #fecaca; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-triangle-exclamation"></i> 
                <span><?php echo $errorMsg; ?></span>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Email hoặc Tên đăng nhập</label>
                <!-- Giữ lại thông tin username/email vừa nhập nếu có lỡ nhập sai pass -->
                <input type="text" id="email" name="email" required placeholder="Nhập email hoặc tên đăng nhập" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required placeholder="Nhập mật khẩu">
            </div>
            
            <button type="submit" class="btn auth-btn"><i class="fa-solid fa-arrow-right-to-bracket"></i> Đăng nhập</button>
        </form>
        
        <p class="auth-switch">Chưa có tài khoản? <a href="index.php?type=Register">Đăng ký ngay</a></p>
    </div>
</main>