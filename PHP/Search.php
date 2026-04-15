<link rel="stylesheet" href="CSS/index.css">
<?php
require_once __DIR__ . '/Connect/connect.php';

$keyword = trim($_GET['keyword'] ?? '');
$categoryFilter = $_GET['category'] ?? '';
$qualityFilter = $_GET['quality'] ?? '';
$seasonFilter = $_GET['season'] ?? '';
$results = [];
$errorMsg = '';

$whereClauses = [];
$params = [];
$types = '';

if ($keyword !== '') {
    $whereClauses[] = "category.description LIKE ?";
    $searchTerm = '%' . $conn->real_escape_string($keyword) . '%';
    $params[] = $searchTerm;
    $types .= 's';
}

if ($categoryFilter !== '') {
    $whereClauses[] = "sanpham.category_id = ?";
    $params[] = $categoryFilter;
    $types .= 'i';
}

if ($qualityFilter !== '') {
    $whereClauses[] = "sanpham.quality = ?";
    $params[] = $qualityFilter;
    $types .= 's';
}

if ($seasonFilter !== '') {
    $whereClauses[] = "sanpham.season_id = ?";
    $params[] = $seasonFilter;
    $types .= 'i';
}

$whereClauses[] = "(CAST(TrangThai AS UNSIGNED) = 0 OR TrangThai IS NULL)";

$whereSql = implode(' AND ', $whereClauses);

$sql = "SELECT sanpham.*, category.description as category_name FROM sanpham LEFT JOIN category ON sanpham.category_id = category.id WHERE $whereSql ORDER BY MaSP DESC";
$stmt = $conn->prepare($sql);
if ($stmt) {
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res) {
        $results = $res->fetch_all(MYSQLI_ASSOC);
    }
    $stmt->close();
} else {
    $errorMsg = 'Lỗi truy vấn tìm kiếm: ' . $conn->error;
}

// Get filter options
$categories = $conn->query("SELECT id, description FROM category");
$qualities = $conn->query("SELECT DISTINCT quality FROM sanpham WHERE quality IS NOT NULL");
$seasons = [['id' => 0, 'name' => 'Mùa hè'], ['id' => 1, 'name' => 'Mùa đông']];
?>

<main class="category-page">
    <div class="container">
        <div class="page-header">
            <h2>Tìm kiếm: <?= htmlspecialchars($keyword) ?></h2>
            <p>Nhập từ khóa để tìm quần áo, sách vở hoặc nhu yếu phẩm.</p>
        </div>

        <!-- Filter Form -->
        <div class="filter-form">
            <form method="GET" action="index.php">
                <input type="hidden" name="type" value="Search">
                <input type="hidden" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
                <div>
                    <label for="category">Danh mục:</label>
                    <select name="category" id="category">
                        <option value="">Tất cả</option>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>" <?= $categoryFilter == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['description']) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <label for="quality">Chất lượng:</label>
                    <select name="quality" id="quality">
                        <option value="">Tất cả</option>
                        <?php while ($qual = $qualities->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($qual['quality']) ?>" <?= $qualityFilter == $qual['quality'] ? 'selected' : '' ?>><?= htmlspecialchars($qual['quality']) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <label for="season">Mùa:</label>
                    <select name="season" id="season">
                        <option value="">Tất cả</option>
                        <?php foreach ($seasons as $season): ?>
                            <option value="<?= $season['id'] ?>" <?= $seasonFilter == $season['id'] ? 'selected' : '' ?>><?= $season['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Lọc</button>
                </div>
            </form>
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
            <div style="margin-bottom: 20px; color: #555;">
                Tìm thấy <strong><?= count($results) ?></strong> sản phẩm phù hợp.
            </div>
            <div class="product-grid">
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
                        <div class="product-card">
                            <div class="product-img">
                                <?php if ($row['image']): ?>
                                    <img src="<?= $imgSrc ?>" alt="<?= $name ?>">
                                <?php else: ?>
                                    <div style="height: 100%; display: flex; align-items:center; justify-content:center; color:#888;"><i class="fa-solid fa-image fa-3x" style="color:#ccc;"></i></div>
                                <?php endif; ?>
                            </div>
                            <h3><?= $name ?></h3>
                            <div class="product-meta">
                                <span class="badge category-badge"><?= htmlspecialchars($row['category_name'] ?? 'Khác') ?></span>
                                <span class="badge <?= $seasonClass ?>"><?= $seasonText ?></span>
                                <span class="badge <?= $statusClass ?>"><?= $quality ?></span>
                            </div>
                            <p class="desc"><?= $desc ?></p>
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
