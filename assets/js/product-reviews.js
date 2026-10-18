
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.reviews-carousel').forEach((carousel) => {
        const viewport = carousel.querySelector(
            '.reviews-carousel__viewport'
        );

        const track = carousel.querySelector(
            '.reviews-carousel__track'
        );

        const prevButton = carousel.querySelector(
            '.reviews-carousel__button--prev'
        );

        const nextButton = carousel.querySelector(
            '.reviews-carousel__button--next'
        );

        if (!viewport || !track || !prevButton || !nextButton) {
            return;
        }

        const cards = Array.from(
            track.querySelectorAll('.review-card')
        );

        if (!cards.length) {
            prevButton.hidden = true;
            nextButton.hidden = true;
            return;
        }

        let page = 0;

        const getVisibleCount = () => {
            return window.matchMedia('(max-width: 640px)').matches
                ? 1
                : 2;
        };

        const getMaxPage = () => {
            return Math.max(
                0,
                Math.ceil(cards.length / getVisibleCount()) - 1
            );
        };

        const update = () => {
            const visibleCount = getVisibleCount();
            const gap = parseFloat(
                getComputedStyle(track).gap
            ) || 0;

            const cardWidth =
                cards[0].getBoundingClientRect().width;

            // Move by a complete page:
            // 2 cards on desktop, 1 card on mobile.
            const offset =
                page * (
                    visibleCount * cardWidth +
                    (visibleCount - 1) * gap
                );

            track.style.transform = `translateX(${-offset}px)`;

            prevButton.disabled = page === 0;
            nextButton.disabled = page >= getMaxPage();

            const hasOverflow = cards.length > visibleCount;

            prevButton.hidden = !hasOverflow;
            nextButton.hidden = !hasOverflow;
        };

        prevButton.addEventListener('click', () => {
            if (page > 0) {
                page--;
                update();
            }
        });

        nextButton.addEventListener('click', () => {
            if (page < getMaxPage()) {
                page++;
                update();
            }
        });

        window.addEventListener('resize', () => {
            page = Math.min(page, getMaxPage());
            update();
        });

        update();
    });
});
