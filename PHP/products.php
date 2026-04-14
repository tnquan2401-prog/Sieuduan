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

// Truy vấn tất cả sản phẩm
$sql = "SELECT * FROM sanpham WHERE (CAST(TrangThai AS UNSIGNED) = 0 OR TrangThai IS NULL) ORDER BY MaSP DESC";
$res = $conn->query($sql);
if ($res === false) {
    die('Lỗi truy vấn sản phẩm: ' . $conn->error);
}
?>

<main>
    <header class="banner">
        <img src="Resources\Image\Ảnh tủ đồ yêu thương.png" alt="">
    </header>
    <section class="products-section">
        <div class="container">
            <div class="page-header">
                <h2>Tất cả vật phẩm quyên góp</h2>
                <p>Tất cả các vật phẩm được quyên góp, sẵn sàng để bạn nhận.</p>
            </div>
            <div class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 25px;">
                <?php
                if ($res && $res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        $name = htmlspecialchars($row['name']);
                        $quality = htmlspecialchars($row['quality']);
                        $desc = htmlspecialchars($row['description']);
                        $imgSrc = htmlspecialchars(getImagePath($row['quality'], $row['image']));
                        
                        // Xét dữ liệu Season và Status badge
                        $isWinter = ($row['season_id'] == 1);
                        $seasonClass = $isWinter ? "type-winter" : "type-summer";
                        $seasonText = $isWinter ? "Mùa đông" : "Mùa hè";
                        
                        // Style chất lượng
                        $statusClass = ($quality === 'Cũ tốt') ? "status-old" : "status-new";
                ?>
                        <!-- Item Card -->
                        <div class="product-card" style="display: flex; flex-direction: column; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); text-align: center; transition: 0.3s; border: 1px solid #eee;">
                            
                            <div class="product-img" style="height: 200px; padding: 0; margin-bottom: 15px; border-radius: 6px; overflow: hidden; background: #f8f9fa;">
                                <?php if ($row['image']): ?>
                                    <img src="<?= $imgSrc ?>" alt="<?= $name ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <div style="height: 100%; display: flex; align-items:center; justify-content:center; color:#888;"><i class="fa-solid fa-image fa-3x" style="color:#ccc;"></i></div>
                                <?php endif; ?>
                            </div>
                            
                            <h3 style="margin-bottom: 12px; color: #333;"><?= $name ?></h3>
                            
                            <div class="product-meta">
                                <span class="badge <?= $seasonClass ?>"><?= $seasonText ?></span>
                                <span class="badge <?= $statusClass ?>"><?= $quality ?></span>
                            </div>
                            
                            <!-- Box cho đoạn mô tả có giới hạn để các thẻ đều nhau -->
                            <p class="desc" style="flex-grow: 1;"><?= $desc ?></p>
                            
                            <a href="index.php?type=ActionReceive&id=<?= $row['MaSP'] ?>" class="btn-outline" style="margin-top:auto;">Nhận</a>
                        </div>
                <?php
                    }
                } else {
                    echo '<p style="grid-column: 1 / -1; text-align: center; color: #888; font-style: italic; padding: 20px 0;">Hiện tại chưa có sản phẩm nào.</p>';
                }
                ?>
            </div>
        </div>
    </section>
</main>