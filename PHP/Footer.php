    <footer>
        <div class="footer-container">
            <div class="footer-col">
                <h3>Về Trường Mầm Non Mai Động</h3>
                <p>Nền tảng kết nối và chia sẻ vật phẩm từ thiện nhanh chóng, minh bạch.</p>
            </div>
            <div class="footer-col">
                <h3>Liên kết nhanh</h3>
                <ul>
                    <li><a href="index.php?type=VeChungToi">Về chúng tôi</a></li>
                    <li><a href="index.php?type=QuanAo">Danh sách vật phẩm</a></li>
                    <li><a href="index.php?type=VeChungToi">Tin tức hoạt động</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Thông tin liên hệ</h3>
                <p><i class="fa-solid fa-location-dot"></i> Hà Nội, Việt Nam</p>
                <p><i class="fa-solid fa-phone"></i> 02438621076</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Website Từ Thiện. Được phát triển bởi các học trò của Ths LeVanUy</p>
        </div>
    </footer>

    <!-- Nút Lên Đầu Trang -->
    <button id="backToTopBtn" title="Lên đầu trang"><i class="fa-solid fa-arrow-up"></i></button>
    <script src="JS/index.js"></script>
    <script src="JS/scripts.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-box input');
            const suggestionsDiv = document.getElementById('search-suggestions');
            let timeout;

            if (searchInput && suggestionsDiv) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(timeout);
                    const keyword = this.value.trim();
                    if (keyword.length < 2) {
                        suggestionsDiv.style.display = 'none';
                        return;
                    }
                    timeout = setTimeout(() => {
                        fetch(`PHP/search_api.php?keyword=${encodeURIComponent(keyword)}`)
                            .then(response => response.json())
                            .then(data => {
                                suggestionsDiv.innerHTML = '';
                                if (data.length > 0) {
                                    data.forEach(item => {
                                        const div = document.createElement('div');
                                        div.textContent = item.name;
                                        div.addEventListener('click', function() {
                                            searchInput.value = item.name;
                                            suggestionsDiv.style.display = 'none';
                                            // Submit form
                                            searchInput.form.submit();
                                        });
                                        suggestionsDiv.appendChild(div);
                                    });
                                    suggestionsDiv.style.display = 'block';
                                } else {
                                    suggestionsDiv.style.display = 'none';
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }, 300); // Debounce 300ms
                });

                // Hide suggestions when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                        suggestionsDiv.style.display = 'none';
                    }
                });
            }

            // --- XỬ LÝ NÚT LÊN ĐẦU TRANG (BACK TO TOP) ---
            const backToTopBtn = document.getElementById("backToTopBtn");
            if (backToTopBtn) {
                window.addEventListener("scroll", function() {
                    // Hiện nút khi cuộn xuống 300px
                    if (window.pageYOffset > 300) {
                        backToTopBtn.style.display = "block";
                    } else {
                        backToTopBtn.style.display = "none";
                    }
                });

                backToTopBtn.addEventListener("click", function() {
                    window.scrollTo({
                        top: 0,
                        behavior: "smooth"
                    });
                });
            }
        });
    </script>
</body>
</html>