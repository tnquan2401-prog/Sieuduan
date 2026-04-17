<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/Connect/connect.php';

// Bảo vệ: Chỉ Admin mới được vào
if (!isset($_SESSION['user_id']) || $_SESSION['permission'] !== 'admin') {
    echo "<script>alert('Truy cập bị từ chối!'); window.location.href = 'index.php';</script>";
    exit();
}

// Lấy danh sách đăng ký cho đồ, join với bảng users để biết ai đăng ký
$sql = "SELECT d.*, u.UserName as AccountName 
        FROM dangkychodo d 
        JOIN users u ON d.ID_User = u.ID 
        ORDER BY d.ID DESC";
$result = $conn->query($sql);
$list = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $list[] = $row;
    }
}
?>

<link rel="stylesheet" href="CSS/index.css">

<main class="category-page">
    <div class="container" style="max-width: 1000px;">
        <div class="page-header" style="text-align: left; border-bottom: 2px solid #28a745; padding-bottom: 15px; margin-bottom: 30px;">
            <h2 style="margin: 0; color: #1e293b;"><i class="fa-solid fa-clipboard-list"></i> Danh Sách Đăng Ký Cho Đồ</h2>
            <p style="margin: 5px 0 0 0; color: #64748b;">Tổng hợp thông tin những tấm lòng hảo tâm đã đăng ký quyên góp tại trường.</p>
        </div>

        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 20px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                        <th style="padding: 15px; color: #475569; width: 80px;">ID</th>
                        <th style="padding: 15px; color: #475569;">Tài khoản</th>
                        <th style="padding: 15px; color: #475569;">Họ và Tên</th>
                        <th style="padding: 15px; color: #475569;">Số điện thoại</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($list) > 0): ?>
                        <?php foreach($list as $item): ?>
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 15px; color: #1e293b; font-weight: 600;">#<?php echo $item['ID']; ?></td>
                                <td style="padding: 15px; color: #64748b;"><?php echo htmlspecialchars($item['AccountName']); ?></td>
                                <td style="padding: 15px; color: #0f172a; font-weight: 500;"><?php echo htmlspecialchars($item['HoTen']); ?></td>
                                <td style="padding: 15px; color: #28a745; font-weight: bold;"><?php echo htmlspecialchars($item['SoDienThoai']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 50px; color: #94a3b8; font-style: italic;">
                                <i class="fa-regular fa-face-meh fa-3x" style="display: block; margin-bottom: 15px;"></i>
                                Chưa có đăng ký nào trong danh sách.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
