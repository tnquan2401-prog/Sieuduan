<?php
// Initialize message variables
$successMsg = '';
$errorMsg = '';

// Include Database Connection
require_once __DIR__ . '/Connect/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Lấy dữ liệu từ form
    $ten = htmlspecialchars($_POST['ten'] ?? '');
    $chat_luong = $_POST['chat_luong'] ?? '';
    $kieu = $_POST['kieu'] ?? '';
    $loai = $_POST['loai'] ?? '';
    $mua = $_POST['mua'] ?? '';
    $mo_ta = htmlspecialchars($_POST['mo_ta'] ?? '');

    // 2. Chuyển đổi trạng thái "Chất lượng" thành tên thư mục lưu lưu ảnh cho dễ quản lý
    $dirName = '';
    switch ($chat_luong) {
        case 'Mới nguyên': 
            $dirName = 'Moi_Nguyen'; 
            break;
        case 'Mới 95%': 
            $dirName = 'Moi_95'; 
            break;
        case 'Cũ tốt': 
            $dirName = 'Cu_Tot'; 
            break;
        default: 
            $dirName = 'Khac';
    }

    // 3. Xử lý Upload file ảnh
    if (isset($_FILES['anh']) && $_FILES['anh']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['anh']['tmp_name'];
        $fileName = $_FILES['anh']['name'];
        $fileSize = $_FILES['anh']['size'];
        
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        // Hỗ trợ các định dạng ảnh phổ biến
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($fileExtension, $allowedExts)) {
            // Kiểm tra giới hạn 5MB = 5 * 1024 * 1024 bytes
            if ($fileSize <= 5 * 1024 * 1024) {
                
                // Cấu trúc: Workspace/Resources/Image/[ChatLuong]/
                $baseDir = __DIR__ . '/../Resources/Image';
                $targetDir = $baseDir . '/' . $dirName . '/';
                
                // Tạo thư mục nếu chưa tồn tại
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                // Chống trùng lặp định danh ảnh bằng MD5 + Time
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $destPath = $targetDir . $newFileName;

                // Move từ temp file vào folder chính
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    // Chuyển đổi ID phù hợp Datatype trong DB
                    $type_id = ($kieu === 'Người lớn') ? 1 : 0; // bit(1)
                    
                    $category_id = 1; // Quần mặc định
                    if ($loai === 'Áo') {
                        $category_id = 2;
                    } elseif ($loai === 'Váy') {
                        $category_id = 3;
                    } elseif ($loai === 'Bộ') {
                        $category_id = 4;
                    } elseif ($loai === 'Dép') {
                        $category_id = 5;
                    } elseif ($loai === 'Giày') {
                        $category_id = 6;
                    } elseif ($loai === 'Sách vở') {
                        $category_id = 7;
                    } elseif ($loai === 'Nhu yếu phẩm') {
                        $category_id = 8;
                    }

                    $season_id = ($mua === 'Đông') ? 1 : 0; // bit(1)

                    // Thực thi query thêm vào DB cho bảng sản phẩm (giả định tên là sanpham)
                    $available = 0; // Sản phẩm mới thêm mặc định hiển thị (TrangThai = 0)
                    $sql = "INSERT INTO sanpham (name, image, type_id, category_id, season_id, quality, description, TrangThai) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        // param: string, string, int, int, int, string, string, int = "ssiiissi"
                        $stmt->bind_param("ssiiissi", $ten, $newFileName, $type_id, $category_id, $season_id, $chat_luong, $mo_ta, $available);
                        
                        if ($stmt->execute()) {
                            $successMsg = "Đã thêm sản phẩm <b>'$ten'</b> thành công. Ảnh được lưu vào danh mục $chat_luong!";
                        } else {
                            $errorMsg = "Lỗi lưu Database: " . $stmt->error;
                        }
                        $stmt->close();
                    } else {
                        $errorMsg = "Lỗi truy vấn SQL: " . $conn->error;
                    }
                } else {
                    $errorMsg = 'Có lỗi xảy ra trong quá trình lưu tệp tin ảnh lên máy chủ. Vui lòng thử lại sau.';
                }
                
            } else {
                $errorMsg = 'Dung lượng ảnh vượt quá giới hạn 5MB!';
            }
        } else {
            $errorMsg = 'Định dạng file không được hỗ trợ. Vui lòng chọn ảnh: JPG, PNG, WEBP, GIF.';
        }
    } else {
        $errorMsg = 'Vui lòng chọn hoặc tải lên một hình ảnh hợp lệ!';
    }
}
?>

<!-- Gọi file CSS chứa style mới giao diện Admin -->
<link rel="stylesheet" href="CSS/admin_add_product.css">

<div class="admin-wrapper">
    <div class="admin-form-container">
        
        <div class="form-header">
            <h2><i class="fa-solid fa-plus-circle"></i> Thêm Sản Phẩm Quyên Góp</h2>
            <p>Bảng điều khiển Admin - Dự Án Nuôi Em</p>
        </div>

        <!-- Block hiển thị thông báo -->
        <?php if ($successMsg): ?>
            <div class="alert alert-success">
                <span class="alert-icon"><i class="fa-solid fa-circle-check"></i></span> 
                <span><?php echo $successMsg; ?></span>
            </div>
        <?php endif; ?>

        <?php if ($errorMsg): ?>
            <div class="alert alert-error">
                <span class="alert-icon"><i class="fa-solid fa-triangle-exclamation"></i></span> 
                <span><?php echo $errorMsg; ?></span>
            </div>
        <?php endif; ?>

        <!-- Khung Form -->
        <form action="" method="POST" enctype="multipart/form-data" class="admin-form">
            
            <!-- Tên Sản Phẩm -->
            <div class="form-group">
                <label for="ten">Tên Sản Phẩm / Tên Gọi Trang Phục <span class="required">*</span></label>
                <input type="text" id="ten" name="ten" placeholder="Ví dụ: Áo khoác mùa đông xanh navy..." required>
            </div>

            <!-- Box Upload Ảnh -->
            <div class="form-group file-upload-group">
                <label>Hình Ảnh Sản Phẩm (Giới hạn: 5MB) <span class="required">*</span></label>
                <div class="file-upload-box" id="file-upload-box">
                    <div class="upload-content">
                        <span class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></span>
                        <p class="upload-title">Kéo thả ảnh vào đây hoặc <strong>nhấn để chọn</strong></p>
                        <p class="file-desc">Hỗ trợ JPG, PNG, WEBP</p>
                    </div>
                    <input type="file" id="anh" name="anh" accept="image/*" required>
                </div>
                <!-- Box xem trước nội dung file đã chọn -->
                <div id="file-preview-name" class="file-preview-name"></div>
            </div>

            <div class="form-group-grid">
                <!-- Chất lượng -->
                <div class="form-group">
                    <label for="chat_luong">Tình Trạng (Chất Lượng) <span class="required">*</span></label>
                    <div class="select-wrapper">
                        <select id="chat_luong" name="chat_luong" required>
                            <option value="Mới nguyên">Mới nguyên (100% Fullbox)</option>
                            <option value="Mới 95%">Mới 95% (Đã sử dụng lướt)</option>
                            <option value="Cũ tốt">Cũ tốt (Xài được, không hỏng rách)</option>
                        </select>
                    </div>
                </div>

                <!-- Kiểu đối tượng -->
                <div class="form-group">
                    <label for="kieu">Kiểu Đối Tượng <span class="required">*</span></label>
                    <div class="select-wrapper">
                        <select id="kieu" name="kieu" required>
                            <option value="Trẻ em">Dành cho Trẻ em</option>
                            <option value="Người lớn">Dành cho Người lớn</option>
                        </select>
                    </div>
                </div>

                <!-- Phân loại -->
                <div class="form-group">
                    <label for="loai">Loại Trang Phục / Sản Phẩm <span class="required">*</span></label>
                    <div class="select-wrapper">
                        <select id="loai" name="loai" required>
                            <option value="Quần">Quần</option>
                            <option value="Áo">Áo</option>
                            <option value="Váy">Váy</option>
                            <option value="Bộ">Bộ (Bao gồm Quần + Áo)</option>
                            <option value="Dép">Dép</option>
                            <option value="Giày">Giày</option>
                            <option value="Sách vở">Sách vở</option>
                            <option value="Nhu yếu phẩm">Nhu yếu phẩm</option>
                        </select>
                    </div>
                </div>

                <!-- Mùa (Thời tiết sử dụng phù hợp) -->
                <div class="form-group">
                    <label for="mua">Mùa Phù Hợp <span class="required">*</span></label>
                    <div class="select-wrapper">
                        <select id="mua" name="mua" required>
                            <option value="Đông">Mùa Đông (Giữ nhiệt, nỉ, phao...)</option>
                            <option value="Hè">Mùa Hè (Thoáng mát, rực rỡ...)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Mô Tả Thêm -->
            <div class="form-group">
                <label for="mo_ta">Mô Tả Chi Tiết (Tùy chọn)</label>
                <textarea id="mo_ta" name="mo_ta" rows="4" placeholder="Nhập thêm chi tiết về tình trạng cũ như thế nào, size số cụ thể ra sao, màu sắc thực tế (nếu ảnh mờ) v.v..."></textarea>
            </div>

            <!-- Nút Lưu (Submit) -->
            <button type="submit" class="btn-submit">
                <span>Thêm & Lưu Trữ Sản Phẩm</span>
                <span class="btn-icon"><i class="fa-solid fa-arrow-right"></i></span>
            </button>
        </form>
        
    </div>
</div>

<script>
    // JS Handle Custom Box Chọn Ảnh & Cập nhật Box Review
    const fileInput = document.getElementById('anh');
    const uploadBox = document.getElementById('file-upload-box');
    const previewName = document.getElementById('file-preview-name');

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const fileName = this.files[0].name;
            const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2); // Convert to MB
            
            // Limit text alert preview
            if (this.files[0].size > 5 * 1024 * 1024) {
               previewName.innerHTML = `<span style="color: #ef4444"><i class="fa-solid fa-triangle-exclamation"></i> Tệp tin chọn: ${fileName} (${fileSize} MB) - Vượt quá 5MB </span>`;
               uploadBox.style.borderColor = '#ef4444';
               uploadBox.style.backgroundColor = '#fef2f2'; 
               return;
            }

            previewName.innerHTML = `<i class="fa-solid fa-circle-check"></i> Ảnh hoàn tất: <strong>${fileName}</strong> (${fileSize} MB)`;
            uploadBox.style.borderColor = '#10b981'; // Green
            uploadBox.style.backgroundColor = '#ecfdf5'; 
        } else {
            previewName.innerHTML = '';
            uploadBox.style.borderColor = '#cbd5e1';
            uploadBox.style.backgroundColor = '#f8fafc';
        }
    });

    uploadBox.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadBox.style.borderColor = '#28a745'; // Green Drag Effect
        uploadBox.style.backgroundColor = '#e8f5e9';
    });

    uploadBox.addEventListener('dragleave', () => {
        uploadBox.style.borderColor = '#cbd5e1';
        uploadBox.style.backgroundColor = '#f8fafc';
    });
</script>
