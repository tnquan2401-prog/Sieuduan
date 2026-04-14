<link rel="stylesheet" href="CSS/index.css">
<?php
require_once __DIR__ . '/Connect/connect.php';

// Nếu không truyền category id thì dừng lại
if (!isset($categoryId) || !isset($pageTitle)) {
    echo '<div style="padding:40px;text-align:center;color:#d32f2f;font-weight:bold;">Danh mục chưa được xác định!</div>';
    return;
}

function getImagePath($quality, $imageName) {
    if (!$imageName) return '';
    $dirName = 'Khac';
    if ($quality === 'Mới nguyên') $dirName = 'Moi_Nguyen';
    elseif ($quality === 'Mới 95%') $dirName = 'Moi_95';
    elseif ($quality === 'Cũ tốt') $dirName = 'Cu_Tot';
    return "Resources/Image/{$dirName}/{$imageName}";
}

$pageDescription = $pageDescription ?? '';

function fetchStatementRows($stmt) {
    if (method_exists($stmt, 'get_result')) {
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    $stmt->store_result();
    $meta = $stmt->result_metadata();
    $fields = [];
    $row = [];
    while ($field = $meta->fetch_field()) {
        $row[$field->name] = null;
        $fields[] = &$row[$field->name];
    }
    call_user_func_array([$stmt, 'bind_result'], $fields);

    $rows = [];
    while ($stmt->fetch()) {
        $copy = [];
        foreach ($row as $key => $value) {
            $copy[$key] = $value;
        }
        $rows[] = $copy;
    }
    return $rows;
}

// Query chung cho cả Người lớn và Trẻ em
$sqlNguoiLon = "SELECT * FROM sanpham WHERE category_id = ? AND CAST(type_id AS UNSIGNED) = 1 AND (CAST(TrangThai AS UNSIGNED) = 0 OR TrangThai IS NULL) ORDER BY MaSP DESC";
$sqlTreEm = "SELECT * FROM sanpham WHERE category_id = ? AND CAST(type_id AS UNSIGNED) = 0 AND (CAST(TrangThai AS UNSIGNED) = 0 OR TrangThai IS NULL) ORDER BY MaSP DESC";

$stmtNguoiLon = $conn->prepare($sqlNguoiLon);
$stmtTreEm = $conn->prepare($sqlTreEm);

if (!$stmtNguoiLon || !$stmtTreEm) {
    die('Lỗi truy vấn danh mục: ' . $conn->error);
}

$stmtNguoiLon->bind_param('i', $categoryId);
$stmtNguoiLon->execute();
$resNguoiLon = fetchStatementRows($stmtNguoiLon);

$stmtTreEm->bind_param('i', $categoryId);
$stmtTreEm->execute();
$resTreEm = fetchStatementRows($stmtTreEm);
?>

<main class="category-page">
    <div class="container">
        <div class="page-header">
            <h2><?= htmlspecialchars($pageTitle) ?></h2>
            <p><?= htmlspecialchars($pageDescription) ?></p>
        </div>

        <section class="clothing-section">
            <h3 class="section-subtitle">Dành cho Người lớn</h3>
            <div class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 25px;">
                <?php
                if (!empty($resNguoiLon)) {
                    foreach ($resNguoiLon as $row) {
                        $name = htmlspecialchars($row['name']);
                        $quality = htmlspecialchars($row['quality']);
                        $desc = htmlspecialchars($row['description']);
                        $imgSrc = htmlspecialchars(getImagePath($row['quality'], $row['image']));
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
                <?php
                    }
                } else {
                    echo '<p style="grid-column: 1 / -1; text-align: center; color: #888; font-style: italic; padding: 20px 0;">Hiện tại chưa có sản phẩm dành cho Người lớn.</p>';
                }
                ?>
            </div>
        </section>

        <section class="clothing-section">
            <h3 class="section-subtitle">Dành cho Trẻ em</h3>
            <div class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 25px;">
                <?php
                if (!empty($resTreEm)) {
                    foreach ($resTreEm as $row) {
                        $name = htmlspecialchars($row['name']);
                        $quality = htmlspecialchars($row['quality']);
                        $desc = htmlspecialchars($row['description']);
                        $imgSrc = htmlspecialchars(getImagePath($row['quality'], $row['image']));
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
                <?php
                    }
                } else {
                    echo '<p style="grid-column: 1 / -1; text-align: center; color: #888; font-style: italic; padding: 20px 0;">Hiện tại chưa có sản phẩm dành cho Trẻ em.</p>';
                }
                ?>
            </div>
        </section>
    </div>
</main>

<?php
$stmtNguoiLon->close();
$stmtTreEm->close();
?>