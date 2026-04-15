<link rel="stylesheet" href="CSS/index.css">
<?php
// Gọi kết nối Database
require_once __DIR__ . '/Connect/connect.php';

// Hàm hỗ trợ để tạo đúng đường dẫn lưu trữ folder ảnh
function getImagePath($quality, $imageName) {
    if (!$imageName) return '';
    $dirName = 'Khac';
    if ($quality === 'Mới nguyên') $dirName = 'Moi_Nguyen';
    elseif ($quality === 'Mới 95%') $dirName = 'Moi_95';
    elseif ($quality === 'Cũ tốt') $dirName = 'Cu_Tot';
    
    return "Resources/Image/{$dirName}/{$imageName}";
}

// Khởi tạo mảng nhóm sản phẩm
$groupedProducts = [
    1 => [ // Người lớn
        0 => [], // Mùa Hè
        1 => []  // Mùa Đông
    ],
    0 => [ // Trẻ em
        0 => [], // Mùa Hè
        1 => []  // Mùa Đông
    ]
];

// Định nghĩa tên loại trang phục theo category_id
$categoryNames = [
    1 => 'Quần',
    2 => 'Áo',
    3 => 'Váy',
    4 => 'Bộ',
    5 => 'Dép',
    6 => 'Giày',
    7 => 'Sách vở',
    8 => 'Nhu yếu phẩm'
];

// Lấy toàn bộ sản phẩm hợp lệ, sắp xếp ưu tiên theo category (Áo -> Váy -> Quần) để hiển thị đẹp hơn
$sql = "SELECT * FROM sanpham WHERE (CAST(TrangThai AS UNSIGNED) = 0 OR TrangThai IS NULL) ORDER BY category_id ASC, MaSP DESC";
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $type = (int)$row['type_id'];
        $season = (int)$row['season_id'];
        $cat = (int)$row['category_id'];
        
        // Lọc cấu trúc: Chỉ áp dụng với category thuộc nhóm thời trang hoặc giày dép (<= 6)
        if ($cat <= 6) {
            // Đảm bảo type và season tồn tại trong cấu trúc (đề phòng data sai)
            if (isset($groupedProducts[$type]) && isset($groupedProducts[$type][$season])) {
                if (!isset($groupedProducts[$type][$season][$cat])) {
                    $groupedProducts[$type][$season][$cat] = [];
                }
                $groupedProducts[$type][$season][$cat][] = $row;
            }
        }
    }
}

// Hàm hỗ trợ render giao diện cho một Mùa (season)
function renderSeasonSection($seasonData, $typeId, $seasonId, $categoryNames) {
    if (empty($seasonData)) return;
    
    // Kiểm tra xem Mùa này có bất kỳ sản phẩm nào bên trong hay không
    $hasProducts = false;
    foreach ($seasonData as $items) {
        if (!empty($items)) {
            $hasProducts = true;
            break;
        }
    }
    if (!$hasProducts) return;

    $seasonName = ($seasonId == 1) ? "Mùa Đông" : "Mùa Hè";
    $icon = ($seasonId == 1) ? "fa-snowflake" : "fa-sun";
    
    echo "<div id='type_{$typeId}_season_{$seasonId}' class='season-section' style='margin-bottom: 50px; padding: 25px; background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; padding-top: 50px; margin-top: -30px;'>"; // Thêm offset padding cho smooth scroll khỏi bị khuất menu
    echo "<h4 style='color: #28a745; font-size: 1.6rem; margin-bottom: 25px; border-bottom: 2px solid #28a745; display: inline-block; padding-bottom: 8px;'><i class='fa-solid {$icon}'></i> {$seasonName}</h4>";
    
    // Đảm bảo thứ tự hiển thị luôn là: Áo (2) -> Váy (3) -> Quần (1) -> Bộ (4) -> Khác
    $order = [2, 3, 1, 4, 5, 6];
    
    foreach ($order as $catId) {
        if (!isset($seasonData[$catId]) || empty($seasonData[$catId])) continue;
        
        $items = $seasonData[$catId];
        $catName = isset($categoryNames[$catId]) ? $categoryNames[$catId] : "Khác";
        
        echo "<div class='category-group' style='margin-bottom: 35px;'>";
        echo "<h5 style='font-size: 1.3rem; color: #555; margin-bottom: 15px; padding-left: 12px; border-left: 4px solid #f39c12; font-weight: normal; text-transform: uppercase; letter-spacing: 1px;'>{$catName}</h5>";
        
        echo "<div class='product-grid' style='display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;'>";
        
        foreach ($items as $row) {
            $name = htmlspecialchars($row['name']);
            $quality = htmlspecialchars($row['quality']);
            $desc = htmlspecialchars($row['description']);
            $imgSrc = htmlspecialchars(getImagePath($quality, $row['image']));
            
            $isWinter = ($row['season_id'] == 1);
            $seasonClass = $isWinter ? "type-winter" : "type-summer";
            $seasonText = $isWinter ? "Mùa đông" : "Mùa hè";
            $statusClass = ($quality === 'Cũ tốt') ? "status-old" : "status-new";
            
            ?>
            <!-- Item Card -->
            <div class="product-card" style="display: flex; flex-direction: column; background: #fff; padding: 15px; border-radius: 8px; text-align: center; transition: all 0.3s ease; border: 1px solid #e5e7eb;">
                
                <div class="product-img" style="height: 220px; padding: 0; margin-bottom: 15px; border-radius: 6px; overflow: hidden; background: #f8fafc; position: relative;">
                    <?php if ($row['image']): ?>
                        <img src="<?= $imgSrc ?>" alt="<?= $name ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                    <?php else: ?>
                        <div style="height: 100%; display: flex; align-items:center; justify-content:center; color:#cbd5e1;"><i class="fa-solid fa-image fa-3x"></i></div>
                    <?php endif; ?>
                </div>
                
                <h3 style="margin-bottom: 10px; color: #334155; font-size: 1.1rem;"><?= $name ?></h3>
                
                <div class="product-meta" style="margin-bottom: 12px;">
                    <span class="badge <?= $seasonClass ?>"><?= $seasonText ?></span>
                    <span class="badge <?= $statusClass ?>"><?= $quality ?></span>
                </div>
                
                <!-- Box cho đoạn mô tả -->
                <p class="desc" style="flex-grow: 1; color: #64748b; font-size: 0.9rem; margin-bottom: 15px; line-height: 1.5; text-align: left; padding: 0 5px;"><?= $desc ?></p>
                
                <a href="index.php?type=ActionReceive&id=<?= $row['MaSP'] ?>" class="btn-outline" style="margin-top:auto;">Nhận Sản Phẩm</a>
            </div>
            <?php
        }
        
        echo "</div>"; // End product-grid
        echo "</div>"; // End category-group
    }
    
    echo "</div>"; // End season-section
}
?>

<style>
    /* Bổ sung CSS trực tiếp cho các hiệu ứng Card gọn nhẹ */
    .product-card:hover {
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
        transform: translateY(-4px);
        border-color: #cbd5e1 !important;
    }
    .product-card:hover .product-img img {
        transform: scale(1.05); /* Zoom nhẹ ảnh khi hover */
    }
</style>

<main class="category-page">
    <div class="container">
        <div class="page-header">
            <h2>Quần Áo Từ Thiện</h2>
            <p>Tất cả các vật phẩm quần áo được quyên góp, phân loại rõ ràng để bạn dễ dàng tìm kiếm.</p>
        </div>

        <!-- =======================
             PHẦN QUẦN ÁO NGƯỜI LỚN
        ========================== -->
        <section class="clothing-section" style="margin-bottom: 60px;">
            <h3 class="section-subtitle" style="font-size: 2rem; color: #1e293b; margin-bottom: 30px; text-align: center; border-bottom: none;"><i class="fa-solid fa-user-tie" style="color: #28a745; margin-right: 10px;"></i> Dành cho Người lớn</h3>
            
            <?php
            $hasAdult = false;
            // Hiển thị Mùa Hè rổi tới Mùa Đông
            if (isset($groupedProducts[1][0])) {
                renderSeasonSection($groupedProducts[1][0], 1, 0, $categoryNames);
                $hasAdult = true;
            }
            if (isset($groupedProducts[1][1])) {
                renderSeasonSection($groupedProducts[1][1], 1, 1, $categoryNames);
                $hasAdult = true;
            }
            
            if (!$hasAdult) {
                echo '<p style="text-align: center; color: #888; font-style: italic; padding: 20px 0;">Hiện tại chưa có sản phẩm dành cho Người lớn.</p>';
            }
            ?>
        </section>

        <!-- =======================
             PHẦN QUẦN ÁO TRẺ EM
        ========================== -->
        <section class="clothing-section">
            <h3 class="section-subtitle" style="font-size: 2rem; color: #1e293b; margin-bottom: 30px; text-align: center; border-bottom: none;"><i class="fa-solid fa-child-reaching" style="color: #28a745; margin-right: 10px;"></i> Dành cho Trẻ em</h3>
            
            <?php
            $hasChild = false;
            if (isset($groupedProducts[0][0])) {
                renderSeasonSection($groupedProducts[0][0], 0, 0, $categoryNames);
                $hasChild = true;
            }
            if (isset($groupedProducts[0][1])) {
                renderSeasonSection($groupedProducts[0][1], 0, 1, $categoryNames);
                $hasChild = true;
            }
            
            if (!$hasChild) {
                echo '<p style="text-align: center; color: #888; font-style: italic; padding: 20px 0;">Hiện tại chưa có sản phẩm dành cho Trẻ em.</p>';
            }
            ?>
        </section>

    </div>
</main>