
document.addEventListener('DOMContentLoaded', function () {

    const forms = document.querySelectorAll(
        '.product-card__cart-form'
    );

    forms.forEach(function (form) {

        form.addEventListener('submit', async function (event) {

            event.preventDefault();

            const button = form.querySelector(
                '.product-card__add-to-cart'
            );

            
            const productId = button.value;

            const quantityInput = form.querySelector(
                'input[name="quantity"]'
            );

            const quantity = quantityInput
                ? parseInt(quantityInput.value, 10)
                : 1;
            

            if (
                !productId ||
                !Number.isInteger(quantity) ||
                quantity < 1
            ) {
                return;
            }

            

            // جلوگیری از کلیک چندباره
            button.disabled = true;

            const originalText = button.textContent;

            button.textContent = 'در حال افزودن...';

            try {

                const response = await fetch(
                    PezhaalCart.ajaxUrl,
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type':
                                'application/x-www-form-urlencoded'
                        },

                        body: new URLSearchParams({
                            action: 'pezhaal_add_to_cart',
                            product_id: productId,
                            quantity: quantity,
                            nonce: PezhaalCart.nonce
                        })
                    }
                );

                const result = await response.json();

                if (!result.success) {
                    throw new Error(
                        result.data.message || 'خطا در افزودن محصول'
                    );
                }

                // دریافت تعداد انواع محصولات
                const cartCount = result.data.cart_count;

                // به‌روزرسانی تمام Badgeهای موجود در صفحه
                document.querySelectorAll('.cart-count').forEach(function (badge) {

                    badge.textContent = cartCount;

                    badge.classList.toggle(
                        'is-visible',
                        cartCount > 0
                    );

                });

                // نمایش پیام موفقیت
                button.textContent = 'اضافه شد ✓';

                // اجرای رویداد استاندارد ووکامرس
                if (window.jQuery) {

                    jQuery(document.body).trigger(
                        'added_to_cart',
                        [
                            result.data.fragments,
                            result.data.cart_hash,
                            jQuery(button)
                        ]
                    );

                }

                setTimeout(function () {

                    button.textContent = originalText;
                    button.disabled = false;

                }, 1500);

            } catch (error) {

                console.error(error);

                button.textContent = 'خطا!';

                setTimeout(function () {

                    button.textContent = originalText;
                    button.disabled = false;

                }, 1500);

            }

        });

    });

});