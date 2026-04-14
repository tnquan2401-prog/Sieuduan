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

$tab = $_GET['tab'] ?? 'pending';

// Query ghép 4 bảng: sanphamnhan (gốc), sanpham (tên/ảnh sp), users (tên user), thongtinnguoinhan (sđt, địa chỉ thật)
// Chú ý: Ở thongtinnguoinhan cột users_id liên kết với users.ID
$sql = "
    SELECT 
        sn.ID as RequestID, sn.TrangThai as RequestStatus,
        sp.MaSP, sp.name as ProductName, sp.image as ProductImage, sp.quality,
        u.UserName, u.Email,
        tt.Fullname, tt.Phone, tt.Address
    FROM sanphamnhan sn
    JOIN sanpham sp ON sn.product_id = sp.MaSP
    JOIN users u ON sn.user_id = u.ID
    JOIN thongtinnguoinhan tt ON sn.user_id = tt.users_id
    WHERE sn.TrangThai = ?
    ORDER BY sn.ID DESC
";

$statusFilter = ($tab === 'approved') ? 1 : 0;

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $statusFilter);
$stmt->execute();
$result = $stmt->get_result();
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

function getImagePath($quality, $imageName) {
    if (!$imageName) return '';
    $dirName = 'Khac';
    if ($quality === 'Mới nguyên') $dirName = 'Moi_Nguyen';
    elseif ($quality === 'Mới 95%') $dirName = 'Moi_95';
    elseif ($quality === 'Cũ tốt') $dirName = 'Cu_Tot';
    return "Resources/Image/{$dirName}/{$imageName}";
}
?>

<link rel="stylesheet" href="CSS/index.css">
<style>
/* Custom local styles for Admin Dashboard */
.tab-active {
    background-color: #28a745;
    color: #fff !important;
    border: 2px solid #28a745;
}
.tab-inactive {
    background-color: transparent;
    color: #28a745 !important;
    border: 2px solid #28a745;
}
.tab-inactive:hover {
    background-color: #28a745;
    color: #fff !important;
}

.btn-reject {
    background-color: transparent !important;
    border: 2px solid #ef4444 !important;
    color: #ef4444 !important;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
}
.btn-reject:hover {
    background-color: #ef4444 !important;
    color: #fff !important;
}

/* Base button fixing to align heights well */
.btn-approve {
    background-color: transparent !important;
    border: 2px solid #28a745 !important;
    color: #28a745 !important;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
}
.btn-approve:hover {
    background-color: #28a745 !important;
    color: #fff !important;
}
</style>

<main class="category-page">
    <div class="container" style="max-width: 1100px;">
        <div class="page-header" style="text-align: left; padding-bottom: 20px; border-bottom: 2px solid #28a745; display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <h2 style="margin: 0;"><i class="fa-solid fa-list-check"></i> Quản trị Yêu cầu Nhận đồ</h2>
                <p style="margin: 5px 0 0 0; color: #555;">Duyệt và xem thông tin đóng gói gửi đồ cho những khách hàng yêu cầu.</p>
            </div>
            
            <div class="admin-tabs" style="display: flex; gap: 10px;">
                <a href="index.php?type=AdminDuyetDon&tab=pending" class="btn <?= $tab === 'pending' ? 'tab-active' : 'tab-inactive' ?>" style="border-radius: 20px; padding: 8px 20px; font-weight: bold;">Đang chờ duyệt</a>
                <a href="index.php?type=AdminDuyetDon&tab=approved" class="btn <?= $tab === 'approved' ? 'tab-active' : 'tab-inactive' ?>" style="border-radius: 20px; padding: 8px 20px; font-weight: bold;">Đã duyệt <i class="fa-solid fa-check"></i></a>
            </div>
        </div>

        <div style="background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-top: 20px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                        <th style="padding: 15px; color: #475569;">Sản phẩm</th>
                        <th style="padding: 15px; color: #475569;">Người nhận</th>
                        <th style="padding: 15px; color: #475569;">Địa chỉ giao</th>
                        <th style="padding: 15px; color: #475569; width: 150px; text-align: center;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($orders) > 0): ?>
                        <?php foreach($orders as $o): 
                            $imgSrc = getImagePath($o['quality'], $o['ProductImage']);
                        ?>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <!-- Cột Sản phẩm -->
                                <td style="padding: 15px;">
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <div style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden; background: #eee;">
                                            <img src="<?= htmlspecialchars($imgSrc) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: #1e293b;"><?= htmlspecialchars($o['ProductName']) ?></div>
                                            <div style="font-size: 13px; color: #64748b; margin-top: 5px;">Mã SP: <?= htmlspecialchars($o['MaSP']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Cột Người dùng -->
                                <td style="padding: 15px;">
                                    <div style="font-weight: bold; color: #0f172a; margin-bottom: 5px;"><?= htmlspecialchars($o['Fullname']) ?></div>
                                    <div style="color: #64748b; font-size: 0.9em;"><i class="fa-solid fa-phone" style="font-size: 0.85em;"></i> <?= htmlspecialchars($o['Phone']) ?></div>
                                    <div style="color: #64748b; font-size: 0.9em; margin-top: 3px;"><i class="fa-regular fa-envelope" style="font-size: 0.85em;"></i> <?= htmlspecialchars($o['Email']) ?></div>
                                </td>

                                <!-- Cột Địa chỉ Giao -->
                                <td style="padding: 15px; color: #334155; line-height: 1.4; max-width: 250px;">
                                    <?= htmlspecialchars($o['Address']) ?>
                                </td>

                                <!-- Cột Hành động -->
                                <td style="padding: 15px; text-align: center;">
                                    <?php if ($tab === 'pending'): ?>
                                        <div style="display: flex; gap: 8px; justify-content: center; align-items: stretch; height: 100%;">
                                            <a href="index.php?type=AdminActionApprove&id=<?= $o['RequestID'] ?>" class="btn btn-approve" style="padding: 8px 15px; font-size: 0.9rem;" title="Duyệt đơn này"><i class="fa-solid fa-check"></i> Duyệt</a>
                                            <!-- Nút hủy khôi phục trạng thái -->
                                            <a href="index.php?type=AdminActionReject&id=<?= $o['RequestID'] ?>&sp=<?= $o['MaSP'] ?>" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn này?')" class="btn btn-reject" style="padding: 8px 15px; font-size: 0.9rem;" title="Từ chối, xóa đơn và mở khóa hiển thị SP"><i class="fa-solid fa-xmark"></i> Hủy</a>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge status-new" style="background-color: #d1fae5; color: #065f46; font-size: 0.9rem;"><i class="fa-solid fa-circle-check"></i> Đã Duyệt Yêu Cầu</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: #94a3b8; font-style: italic;">
                                <i class="fa-regular fa-folder-open fa-3x" style="display:block; margin-bottom: 15px;"></i>
                                Không có yêu cầu nào trong danh sách này.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
