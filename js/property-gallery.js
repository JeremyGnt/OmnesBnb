/*document.addEventListener('DOMContentLoaded', function() {

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
    function calculateDays(start, end) {
        const startDate = new Date(start);
        const endDate = new Date(end);
        const diffTime = Math.abs(endDate - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays;
    }

    function updateBookingCalculation() {
        const checkIn = document.getElementById('check-in').value;
        const checkOut = document.getElementById('check-out').value;
        const pricePerNight = <?= $property['price'] ?>;

        if (checkIn && checkOut) {
            const nights = calculateDays(checkIn, checkOut);
            if (nights > 0) {
                const subtotal = nights * pricePerNight;

                document.getElementById('nights-count').textContent = nights;
                document.getElementById('subtotal').textContent = subtotal + '€';
                document.getElementById('total-price').textContent = subtotal + '€';
            }
        }
    }

    document.getElementById('check-in').addEventListener('change', updateBookingCalculation);
    document.getElementById('check-out').addEventListener('change', updateBookingCalculation);
}*/