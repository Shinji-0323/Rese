document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.rating__star').forEach(function(starContainer) {
        const rating = parseFloat(starContainer.getAttribute('data-rate'));
        const stars = starContainer.querySelectorAll('.star');

        stars.forEach((star, index) => {
            if (index < rating) {
                star.style.color = '#FFD700';
            } else {
                star.style.color = '#ccc';
            }
        });
    });
});