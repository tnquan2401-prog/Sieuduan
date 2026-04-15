<?php
require_once __DIR__ . '/Connect/connect.php';

header('Content-Type: application/json');

$keyword = trim($_GET['keyword'] ?? '');

if (strlen($keyword) < 2) {
    echo json_encode([]);
    exit;
}

$searchTerm = '%' . $conn->real_escape_string($keyword) . '%';
$sql = "SELECT MaSP, name FROM sanpham WHERE (name LIKE ? OR description LIKE ?) AND (CAST(TrangThai AS UNSIGNED) = 0 OR TrangThai IS NULL) ORDER BY MaSP DESC LIMIT 10";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
    $stmt->execute();
    $res = $stmt->get_result();
    $results = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $results[] = [
                'id' => $row['MaSP'],
                'name' => htmlspecialchars($row['name'])
            ];
        }
    }
    $stmt->close();
}

echo json_encode($results);
?>