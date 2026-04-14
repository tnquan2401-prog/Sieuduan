<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/Connect/connect.php';

// Redirect to login if unauth
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'index.php?type=Login';</script>";
    exit();
}

$cartItemIDs = $_SESSION['cart'] ?? [];
$cartProducts = [];

// Fetch item details
if (count($cartItemIDs) > 0) {
    // Generate ?,?,? sequence
    $placeholders = implode(',', array_fill(0, count($cartItemIDs), '?'));
    
    // Bind dynamically
    $sql = "SELECT * FROM sanpham WHERE MaSP IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        // types string like 'iii'
        $types = str_repeat('i', count($cartItemIDs));
        
        $stmt->bind_param($types, ...$cartItemIDs);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while($row = $result->fetch_assoc()) {
            $cartProducts[] = $row;
        }
    }
}

// Hàm hỗ trợ để tạo đúng đường dẫn lưu trữ folder ảnh
function getImagePath($quality, $imageName) {
    if (!$imageName) return '';
    $dirName = 'Khac';
    if ($quality === 'Mới nguyên') $dirName = 'Moi_Nguyen';
    elseif ($quality === 'Mới 95%') $dirName = 'Moi_95';
    elseif ($quality === 'Cũ tốt') $dirName = 'Cu_Tot';
    
    return "Resources/Image/{$dirName}/{$imageName}";
}

// Xử lý nút Gửi Yêu cầu cho Admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_request'])) {
    if (count($cartItemIDs) > 0) {
        
        $insert_sql = "INSERT INTO sanphamnhan (product_id, user_id, TrangThai) VALUES (?, ?, 0)";
        $insert_stmt = $conn->prepare($insert_sql);
        
        if ($insert_stmt) {
            foreach ($cartItemIDs as $p_id) {
                // Từng item được nhét vào bảng với user_id của người dùng hiện tại
                $insert_stmt->bind_param("ii", $p_id, $_SESSION['user_id']);
                $insert_stmt->execute();
            }
            
            // Hủy session cart để đóng luồng
            $_SESSION['cart'] = [];
            echo "<script>alert('Tuyệt vời! Yêu cầu nhận hiện vật của bạn đã được chuyển đến Ban Quản Trị. Vui lòng chờ chúng tôi liên hệ để giao đồ nhé!'); window.location.href = 'index.php?type=QuanAo';</script>";
            exit();
        } else {
            echo "<script>alert('Đã xảy ra lỗi khi tạo đơn hàng!');</script>";
        }
    }
}
?>

<main class="category-page">
    <div class="container" style="max-width: 900px;">
        <div class="page-header" style="text-align: left; border-bottom: 2px solid #28a745; padding-bottom: 10px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0;"><i class="fa-solid fa-cart-shopping"></i> Danh Sách Nhận Của Bạn</h2>
            <span style="background: #28a745; color: white; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 14px;">
                Đã chọn: <?= count($cartProducts) ?> / 5 món
            </span>
        </div>

        <?php if (count($cartProducts) > 0): ?>
            
            <div class="cart-items" style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 30px;">
                <?php foreach ($cartProducts as $product): 
                    $name = htmlspecialchars($product['name']);
                    $imgSrc = htmlspecialchars(getImagePath($product['quality'], $product['image']));
                    $quality = htmlspecialchars($product['quality']);
                    $seasonText = ($product['season_id'] == 1) ? "Mùa đông" : "Mùa hè";
                    $typeText = ($product['type_id'] == 1) ? "Người lớn" : "Trẻ em";
                ?>
                    <div style="display: flex; align-items: center; border: 1px solid #ddd; padding: 15px; border-radius: 8px; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <div style="width: 80px; height: 80px; flex-shrink: 0; background: #f9f9f9; border-radius: 5px; overflow: hidden; margin-right: 20px;">
                            <img src="<?= $imgSrc ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div style="flex-grow: 1;">
                            <h3 style="margin: 0 0 8px 0; color: #333; font-size: 1.1em;"><?= $name ?></h3>
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <span class="badge status-new" style="display: inline-block; padding: 3px 10px; border-radius: 12px; background: #f1f5f9; color: #475569; font-size: 0.82em; border: 1px solid #e2e8f0;"><?= $quality ?></span>
                                <span class="badge status-new" style="display: inline-block; padding: 3px 10px; border-radius: 12px; background: #fff1f2; color: #be123c; font-size: 0.82em; border: 1px solid #ffe4e6;"><?= $seasonText ?></span>
                                <span class="badge status-new" style="display: inline-block; padding: 3px 10px; border-radius: 12px; background: #eff6ff; color: #1d4ed8; font-size: 0.82em; border: 1px solid #dbeafe;"><?= $typeText ?></span>
                            </div>
                        </div>
                        <div style="margin-left: 20px;">
                            <a href="index.php?type=ActionRemoveCart&id=<?= $product['MaSP'] ?>" class="btn-outline" style="border-color: #ef4444; color: #ef4444; padding: 6px 12px;">
                                <i class="fa-solid fa-trash-can"></i> Xóa
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Nút gửi form -->
            <div style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px dashed #cbd5e1; text-align: center;">
                <p style="margin-bottom: 20px; color: #475569;">Xác nhận danh sách đã chọn (Tối đa 5 vật phẩm). Thông tin sẽ được đưa đến bộ phận kiểm duyệt.</p>
                <form action="" method="POST">
                    <button type="submit" name="submit_request" class="btn" style="font-size: 1.1em; padding: 12px 30px;"><i class="fa-solid fa-paper-plane"></i> Gửi Yêu Cầu Nhận Đồ Trực Tuyến</button>
                </form>
            </div>

        <?php else: ?>
            <div style="text-align: center; padding: 50px 20px; background: #fff; border-radius: 8px; border: 1px dashed #ccc;">
                <i class="fa-solid fa-box-open fa-4x" style="color: #cbd5e1; margin-bottom: 20px;"></i>
                <h3 style="color: #64748b;">Giỏ nhận đồ của bạn đang trống</h3>
                <p style="color: #94a3b8; margin-bottom: 20px;">Hãy lướt qua xem có tấm áo/cuốn sách nào bạn đang thấy hợp với mình nhé.</p>
                <a href="index.php?type=QuanAo" class="btn"><i class="fa-solid fa-shirt"></i> Quay lại xem quần áo</a>
            </div>
        <?php endif; ?>

    </div>
</main>
