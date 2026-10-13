// Carousel Gallery
document.addEventListener('DOMContentLoaded', function () {

    const gallery = document.querySelector('.product-gallery');

    if (!gallery) {
        return;
    }

    const mainImage = gallery.querySelector('.product-main-image');

    const thumbnails = gallery.querySelectorAll('.product-thumbnail');

    thumbnails.forEach(function (thumbnail) {

        thumbnail.addEventListener('click', function () {

            const imageUrl = thumbnail.dataset.image;

            mainImage.src = imageUrl;

            thumbnails.forEach(function (item) {
                item.classList.remove('is-active');
            });

            thumbnail.classList.add('is-active');

        });

    });

});