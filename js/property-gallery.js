document.addEventListener('DOMContentLoaded', function() {

    initImageCarousel();
});

function initImageCarousel() {
    const carouselSlides = document.querySelectorAll('.carousel-slide');
    const prevBtn = document.querySelector('.carousel-nav.prev');
    const nextBtn = document.querySelector('.carousel-nav.next');
    const indicators = document.querySelectorAll('.indicator');
    const thumbnails = document.querySelectorAll('.thumbnail');

    if (carouselSlides.length === 0) return;

    let currentIndex = 0;
    const maxIndex = carouselSlides.length - 1;

    function showSlide(index) {
        carouselSlides.forEach(slide => slide.classList.remove('active'));
        carouselSlides[index].classList.add('active');

        indicators.forEach(indicator => indicator.classList.remove('active'));
        if (indicators[index]) {
            indicators[index].classList.add('active');
        }

        thumbnails.forEach(thumb => thumb.classList.remove('active'));
        if (thumbnails[index]) {
            thumbnails[index].classList.add('active');
            const thumbContainer = document.querySelector('.thumbnail-container');
            if (thumbContainer) {
                const thumbPosition = thumbnails[index].offsetLeft;
                thumbContainer.scrollLeft = thumbPosition - (thumbContainer.clientWidth / 2) + (thumbnails[index].clientWidth / 2);
            }
        }

        currentIndex = index;
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function () {
            let newIndex = currentIndex - 1;
            if (newIndex < 0) newIndex = maxIndex;
            showSlide(newIndex);
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            let newIndex = currentIndex + 1;
            if (newIndex > maxIndex) newIndex = 0;
            showSlide(newIndex);
        });
    }

    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', function () {
            showSlide(index);
        });
    });

    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', function () {
            showSlide(index);
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowLeft') {
            let newIndex = currentIndex - 1;
            if (newIndex < 0) newIndex = maxIndex;
            showSlide(newIndex);
        } else if (e.key === 'ArrowRight') {
            let newIndex = currentIndex + 1;
            if (newIndex > maxIndex) newIndex = 0;
            showSlide(newIndex);
        }
    });

    let touchStartX = 0;
    let touchEndX = 0;

    const carousel = document.querySelector('.image-carousel');
    if (carousel) {
        carousel.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });

        carousel.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {

            if (touchEndX < touchStartX - 50) {
                let newIndex = currentIndex + 1;
                if (newIndex > maxIndex) newIndex = 0;
                showSlide(newIndex);
            } else if (touchEndX > touchStartX + 50) {
                let newIndex = currentIndex - 1;
                if (newIndex < 0) newIndex = maxIndex;
                showSlide(newIndex);
            }
        }
    }
}

