<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang web Từ Thiện</title>
    <link rel="stylesheet" href="CSS/index.css">

</head>
<body>
    <main>
        <div class="banner">
            <img src="Resources\Image\z7726712605539_9af3710a6909247c4c7c55dd89b0bd00.jpg" alt="">
        </div>
        <section class="hero">
            <div class="hero-content">
                <h1>Chung tay sẻ chia, lan tỏa yêu thương</h1>
                <p>Nơi kết nối những tấm lòng vàng với những hoàn cảnh khó khăn.</p>
                <a href="index.php?type=products" class="btn">Xem vật phẩm quyên góp</a>
            </div>
        </section>


        <section>
            <div class="banner-carousel">
            <div class="carousel-container">
                <div class="carousel-track">
                    <div class="carousel-slide">
                        <img src="Resources\Image\z7482405814508_1f3ed253d3cfdc6ca3c1ed08ba27a6c2_592ca.jpg" alt="Slide 1">
                    </div>
                    <div class="carousel-slide">
                        <img src="Resources\Image\z7482406229735_1f156182f61004418e5a806db4aab13c_90d4f.jpg" alt="Slide 2">
                    </div>
                    <div class="carousel-slide">
                        <img src="Resources\Image\z7482406255720_85c4d9c38cf450f7186ed23dee51103e_7ddca.jpg"
                            alt="Slide 3">
                    </div>
                    <div class="carousel-slide">
                        <img src="Resources\Image\z7482406291537_0a3de99565dc2e988d19b0c23cfa5f9f_81765.jpg" alt="Slide 4">
                    </div>
                    <div class="carousel-slide">
                        <img src="Resources\Image\z7492712263837_dc2b3bf9d9e9ec69141272a281913970_5e48c.jpg" alt="Slide 5">
                    </div>
                </div>

                <!-- Navigation Arrows -->
                <button class="carousel-btn carousel-btn-prev" id="prevBtn">
                    <i class="fa-solid fa-angle-right fa-rotate-180"></i>
                </button>
                <button class="carousel-btn carousel-btn-next" id="nextBtn">
                    <i class="fa-solid fa-angle-right"></i>
                </button>

                <!-- Indicators -->
                <div class="carousel-indicators">
                    <span class="indicator active" data-index="0"></span>
                    <span class="indicator" data-index="1"></span>
                    <span class="indicator" data-index="2"></span>
                    <span class="indicator" data-index="3"></span>
                    <span class="indicator" data-index="4"></span>
                </div>
            </div>
        </div>
        </section>

        <section class="categories-section">
            <h2 class="section-title">Danh mục quyên góp</h2>
            <div class="category-grid">
                
                <div class="category-card">
                    <div class="category-img"><i class="fa-solid fa-shirt fa-3x" style="color: #28a745;"></i></div>
                    <h3>Quần áo</h3>
                    <a href="index.php?type=QuanAo" class="btn-outline">Xem chi tiết</a>
                </div>

                <!-- <div class="category-card">
                    <div class="category-img"><i class="fa-solid fa-bed fa-3x" style="color: #28a745;"></i></div>
                    <h3>Chăn</h3>
                    <a href="index.php?type=chan" class="btn-outline">Xem chi tiết</a>
                </div> -->

                <!-- <div class="category-card">
                    <div class="category-img"><i class="fa-solid fa-mitten fa-3x" style="color: #28a745;"></i></div>
                    <h3>Mũ len</h3>
                    <a href="index.php?type=mu-len" class="btn-outline">Xem chi tiết</a>
                </div> -->

                <div class="category-card">
                    <div class="category-img"><i class="fa-solid fa-person-dress fa-3x" style="color: #28a745;"></i></div>
                    <h3>Váy</h3>
                    <a href="index.php?type=vay" class="btn-outline">Xem chi tiết</a>
                </div>

                <div class="category-card">
                    <div class="category-img"><i class="fa-solid fa-shoe-prints fa-3x" style="color: #28a745;"></i></div>
                    <h3>Dép</h3>
                    <a href="index.php?type=dep" class="btn-outline">Xem chi tiết</a>
                </div>

                <div class="category-card">
                    <div class="category-img"><i class="fa-solid fa-shoe-prints fa-3x" style="color: #28a745;"></i></div>
                    <h3>Giày</h3>
                    <a href="index.php?type=giay" class="btn-outline">Xem chi tiết</a>
                </div>
            </div>
        </section>
    </main>