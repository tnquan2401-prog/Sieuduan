class Carousel {
    constructor() {
        this.currentSlide = 0;
        this.autoRotateInterval = null;
        this.rotationDelay = 5000; // 5 seconds
        this.init();
    }

    init() {
        this.track = document.querySelector('.carousel-track');
        this.prevBtn = document.getElementById('prevBtn');
        this.nextBtn = document.getElementById('nextBtn');
        this.indicators = document.querySelectorAll('.indicator');

        if (!this.track) return; // Exit if carousel doesn't exist

        this.slideElements = this.track.querySelectorAll('.carousel-slide');
        this.slides = this.slideElements.length;
        this.track.style.transition = 'transform 0.5s ease-in-out';

        // Event listeners
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.goToPrevious());
        }
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.goToNext());
        }
        this.indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => this.goToSlide(index));
        });

        this.updateCarousel();
        this.startAutoRotate();

        const carouselContainer = this.track.parentElement;
        if (carouselContainer) {
            carouselContainer.addEventListener('mouseenter', () => this.stopAutoRotate());
            carouselContainer.addEventListener('mouseleave', () => this.startAutoRotate());

            // Hỗ trợ cảm ứng vuốt (Swipe) trên Mobile/Tablet
            let startX = 0;
            let endX = 0;

            carouselContainer.addEventListener('touchstart', (e) => {
                startX = e.changedTouches[0].screenX;
                this.stopAutoRotate();
            }, { passive: true });

            carouselContainer.addEventListener('touchend', (e) => {
                endX = e.changedTouches[0].screenX;
                this.handleSwipe(startX, endX);
                this.startAutoRotate();
            }, { passive: true });
        }
    }

    handleSwipe(startX, endX) {
        const threshold = 50; // Quãng đường vuốt tối thiểu để lướt trang
        if (startX - endX > threshold) {
            this.goToNext(); // Vuốt sang trái -> Ảnh tiếp theo
        } else if (endX - startX > threshold) {
            this.goToPrevious(); // Vuốt sang phải -> Ảnh trước đó
        }
    }

    goToSlide(slideIndex) {
        this.currentSlide = slideIndex;
        this.updateCarousel();
    }

    goToNext() {
        this.currentSlide = (this.currentSlide + 1) % this.slides;
        this.updateCarousel();
    }

    goToPrevious() {
        this.currentSlide = (this.currentSlide - 1 + this.slides) % this.slides;
        this.updateCarousel();
    }

    updateCarousel() {
        const offset = -this.currentSlide * 100;
        this.track.style.transform = `translateX(${offset}%)`;

        this.indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === this.currentSlide);
        });
    }

    startAutoRotate() {
        this.stopAutoRotate();
        this.autoRotateInterval = setInterval(() => {
            this.goToNext();
        }, this.rotationDelay);
    }

    stopAutoRotate() {
        if (this.autoRotateInterval) {
            clearInterval(this.autoRotateInterval);
            this.autoRotateInterval = null;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new Carousel();

    // Hiệu ứng xoay nghiêng thẻ bài (Pokemon Tilt Card)
    const card = document.querySelector('.story-img .img-placeholder');
    if (card) {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left; 
            const y = e.clientY - rect.top; 

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            // Tính góc xoay (Max 15 độ để không bị lật quá đà)
            const rotateX = ((y - centerY) / centerY) * -15; 
            const rotateY = ((x - centerX) / centerX) * 15;

            // Đưa tọa độ % vào cho CSS đổi màu Hologram theo tia sáng chiếu
            const glareX = (x / rect.width) * 100;
            const glareY = (y / rect.height) * 100;

            card.style.setProperty('--glare-x', `${glareX}%`);
            card.style.setProperty('--glare-y', `${glareY}%`);

            // Apply 3D Transform
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
            
            // Apply Dynamic Shadow
            card.style.boxShadow = `${-rotateY}px ${rotateX}px 30px rgba(0, 0, 0, 0.3)`;
        });

        // Khi chuột rời đi, trả bài về nguyên vẹn
        card.addEventListener('mouseleave', () => {
            card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
            card.style.boxShadow = `0 5px 15px rgba(0, 0, 0, 0.1)`;
        });
    }
});
