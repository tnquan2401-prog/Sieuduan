<?php
require_once __DIR__ . '/Connect/connect.php';

header('Content-Type: application/json');

$keyword = trim($_GET['keyword'] ?? '');

if (strlen($keyword) < 2) {
    echo json_encode([]);
    exit;
}

$searchTerm = '%' . $conn->real_escape_string($keyword) . '%';
$sql = "SELECT id, description as name FROM category WHERE description LIKE ? ORDER BY description ASC LIMIT 10";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('s', $searchTerm);
    $stmt->execute();
    $res = $stmt->get_result();
    $results = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $results[] = [
                'id' => $row['id'],
                'name' => htmlspecialchars($row['name'])
            ];
        }
    }
    $stmt->close();
}

echo json_encode($results);
?>