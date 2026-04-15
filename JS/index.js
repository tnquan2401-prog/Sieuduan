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
});
