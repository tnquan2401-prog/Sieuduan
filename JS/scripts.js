// Bạn có thể thêm các mã Javascript để xử lý giao diện tại đây
document.addEventListener("DOMContentLoaded", function() {
    // Ví dụ: Xử lý thông báo khi bấm nút tìm kiếm mà chưa nhập gì
    const searchForm = document.querySelector('.search-box form');
    const searchInput = document.querySelector('.search-box input');

    if(searchForm) {
        searchForm.addEventListener('submit', function(e) {
            if(searchInput.value.trim() === '') {
                e.preventDefault();
                alert('Vui lòng nhập từ khóa tìm kiếm!');
            }
        });
    }
});
