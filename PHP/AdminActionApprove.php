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

$id = $_GET['id'] ?? null;

if ($id) {
    $sql = "UPDATE sanphamnhan SET TrangThai = 1 WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

echo "<script>window.location.href = 'index.php?type=AdminDuyetDon&tab=pending';</script>";
exit();
?>
