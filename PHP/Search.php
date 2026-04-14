<link rel="stylesheet" href="CSS/index.css">
<?php
require_once __DIR__ . '/Connect/connect.php';

$keyword = trim($_GET['keyword'] ?? '');
$results = [];
$errorMsg = '';

if ($keyword !== '') {
    $searchTerm = '%' . $conn->real_escape_string($keyword) . '%';
    $sql = "SELECT * FROM sanpham WHERE (name LIKE ? OR description LIKE ?) AND (CAST(TrangThai AS UNSIGNED) = 0 OR TrangThai IS NULL) ORDER BY MaSP DESC";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ss', $searchTerm, $searchTerm);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res) {
            $results = $res->fetch_all(MYSQLI_ASSOC);
        }
        $stmt->close();
    } else {
        $errorMsg = 'Lỗi truy vấn tìm kiếm: ' . $conn->error;
    }
}
?>

<main class="category-page">
    <div class="container">
        <div class="page-header">
            <h2>Tìm kiếm: <?= htmlspecialchars($keyword) ?></h2>
            <p>Nhập từ khóa để tìm quần áo, sách vở hoặc nhu yếu phẩm.</p>
        </div>

        <?php if ($errorMsg): ?>
            <div style="color: #b91c1c; padding: 12px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; margin-bottom: 20px;">
                <?= htmlspecialchars($errorMsg) ?>
            </div>
        <?php endif; ?>

        <?php if ($keyword === ''): ?>
            <div style="padding: 30px; text-align: center; color: #555; font-style: italic;">
                Vui lòng nhập từ khóa tìm kiếm.
            </div>
        <?php else: ?>
            <div class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 25px;">
                <?php if (!empty($results)): ?>
                    <?php foreach ($results as $row): ?>
                        <?php
                        $name = htmlspecialchars($row['name']);
                        $quality = htmlspecialchars($row['quality']);
                        $desc = htmlspecialchars($row['description']);
                        $dirName = 'Khac';
                        if ($row['quality'] === 'Mới nguyên') $dirName = 'Moi_Nguyen';
                        elseif ($row['quality'] === 'Mới 95%') $dirName = 'Moi_95';
                        elseif ($row['quality'] === 'Cũ tốt') $dirName = 'Cu_Tot';
                        $imgSrc = htmlspecialchars("Resources/Image/{$dirName}/{$row['image']}");
                        $isWinter = ($row['season_id'] == 1);
                        $seasonClass = $isWinter ? 'type-winter' : 'type-summer';
                        $seasonText = $isWinter ? 'Mùa đông' : 'Mùa hè';
                        $statusClass = ($quality === 'Cũ tốt') ? 'status-old' : 'status-new';
                        ?>
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
                            <p class="desc" style="flex-grow: 1;"><?= $desc ?></p>
                            <a href="index.php?type=ActionReceive&id=<?= $row['MaSP'] ?>" class="btn-outline" style="margin-top:auto;">Nhận</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; color: #888; font-style: italic; padding: 20px 0;">
                        Không tìm thấy sản phẩm nào cho từ khóa "<?= htmlspecialchars($keyword) ?>".
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</main>
