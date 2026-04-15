<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$cartCount = count($_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang web từ thiện</title>
    <link rel="stylesheet" href="CSS/index.css">
    <link rel="stylesheet" href="Resources/Icon/FontAwesome/fontawesome-free-7.2.0-web/css/all.min.css">
    <style>
        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        .search-suggestions div {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }
        .search-suggestions div:hover {
            background: #f8f9fa;
        }
        .search-box {
            position: relative;
        }
    </style>
</head>
<body>
    <header>
    <div class="top-bar">
            <div class="logo">
                <a href="index.php"><h2>Mầm Non Mai Động</h2></a>
            </div>
            
            <div class="search-box">
                    <form action="PHP/Search.php?type=Search" method="GET">
                        <input type="text" name="keyword" class="keywork" placeholder="Tìm kiếm sản phẩm..." required>
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
                <div id="search-suggestions" class="search-suggestions"></div>
            </div>

            <div class="right-actions">
                <div class="auth-links" style="display: flex; align-items: center; gap: 20px;">
                    <?php if(isset($_SESSION['username'])): ?>
                        <a href="index.php?type=DanhSachNhan" style="font-size: 14.5px; margin-right: 15px; position: relative; color: #28a745;">
                            <i class="fa-solid fa-cart-arrow-down"></i> Nhận sản phẩm 
                            <span style="background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; font-weight: bold; position: absolute; top: -10px; right: -25px;"><?= $cartCount ?></span>
                        </a>
                        <div style="font-weight: 500; color: #28a745; margin-left: 15px;">
                            <i class="fa-solid fa-circle-user fa-lg"></i> Xin chào, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                        </div>
                        <?php if(isset($_SESSION['permission']) && $_SESSION['permission'] === 'admin'): ?>
                            <div class="admin-dropdown" style="position: relative; display: inline-block;">
                                <a href="#" style="font-size: 14.5px; margin-right: 15px; cursor: default;"><i class="fa-solid fa-user-tie"></i> Quản trị <i class="fa-solid fa-angle-down" style="font-size: 12px;"></i></a>
                                <div class="admin-dropdown-content" style="display: none; position: absolute; top: 100%; right: 0; background: #fff; min-width: 170px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 8px; z-index: 1000; padding: 10px 0; border: 1px solid #f1f5f9;">
                                    <a href="index.php?type=AdminDuyetDon" style="display: block; padding: 8px 15px; color: #333; font-size: 14px; text-decoration: none; margin: 0;"><i class="fa-solid fa-list-check" style="width: 20px; color: #28a745;"></i> Duyệt Đơn</a>
                                    <a href="index.php?type=admin_add_product" style="display: block; padding: 8px 15px; color: #333; font-size: 14px; text-decoration: none; margin: 0;"><i class="fa-solid fa-plus-circle" style="width: 20px; color: #28a745;"></i> Thêm sản phẩm</a>
                                </div>
                            </div>
                            <style>
                                .admin-dropdown:hover .admin-dropdown-content {
                                    display: block !important;
                                }
                                .admin-dropdown-content a:hover {
                                    background-color: #f8fafc;
                                    color: #28a745 !important;
                                }
                            </style>
                        <?php endif; ?>
                        <a href="index.php?type=logout" style="color: #ef4444; font-size: 14.5px;"><i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất</a>
                    <?php else: ?>
                        <span>
                            <a href="index.php?type=Login"><i class="fa-solid fa-arrow-right-to-bracket"></i> Đăng nhập</a> <span style="margin: 0 8px; color: #cbd5e1;">|</span>
                            <a href="index.php?type=Register"><i class="fa-solid fa-user-plus"></i> Đăng ký</a>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <nav class="main-nav">
            <ul>
                <li><a href="index.php" class="active">Trang chủ</a></li>
                <li><a href="index.php?type=VeChungToi">Về chúng tôi</a></li>
                <li class="dropdown">
                    <a href="">Sản phẩm từ thiện <i class="fa-solid fa-angle-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="index.php?type=QuanAo">Quần áo</a></li>
                        <li><a href="index.php?type=sachvo">Sách vở</a></li>
                        <li><a href="index.php?type=nhuyeupham">Nhu yếu phẩm</a></li>
                    </ul>
                </li>
                <li><a href="index.php?type=NoiQuy">Nội quy</a></li>
                <li><a href="index.php?type=LienHe">Liên hệ</a></li>
            </ul>
        </nav>
    </header>
    <script src="JS/scripts.js"></script>
</body>
</html>