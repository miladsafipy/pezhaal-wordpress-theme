document.addEventListener("DOMContentLoaded", function () {

    const slides = document.querySelectorAll(".hero-slide");
    const dots = document.querySelectorAll(".hero-dot");
    const prevButton = document.querySelector(".hero-prev");
    const nextButton = document.querySelector(".hero-next");

    if (!slides.length) {
        return;
    }

    let currentSlide = 0;
    let autoPlay;

    function showSlide(index) {

        if (index < 0) {
            index = slides.length - 1;
        }

        if (index >= slides.length) {
            index = 0;
        }

        slides.forEach(function (slide) {
            slide.classList.remove("active");
        });

        dots.forEach(function (dot) {
            dot.classList.remove("active");
        });

        slides[index].classList.add("active");

        if (dots[index]) {
            dots[index].classList.add("active");
        }

        currentSlide = index;
    }


    function nextSlide() {
        showSlide(currentSlide + 1);
    }


    function previousSlide() {
        showSlide(currentSlide - 1);
    }


    function startAutoPlay() {
        clearInterval(autoPlay);
        autoPlay = setInterval(
            nextSlide,
            8000
        );
    }


    nextButton?.addEventListener(
        "click",
        function () {
            nextSlide();
            startAutoPlay();
        }
    );


    prevButton?.addEventListener(
        "click",
        function () {
            previousSlide();
            startAutoPlay();
        }
    );


    dots.forEach(function (dot, index) {

        dot.addEventListener(
            "click",
            function () {
                showSlide(index);
                startAutoPlay();
            }
        );
    });

    startAutoPlay();

});